<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\StoreChangeEmailTokenRequest;
use App\Jobs\SendChangeEmailJob;
use App\Models\ChangeEmailToken;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Inertia\Inertia;

class SecurityController
{
    public function forgotPasswordGet()
    {
        return Inertia::render('pages/security/forgot-password');
    }
    //Отправка письма на почту для смены пароля

    public function forgotPasswordPost(ForgotPasswordRequest $request)
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'На почту отправлено письмо для смены пароля');
        }

        $errorMessages = [
            Password::INVALID_USER => 'Пользователь с таким email не найден',
        ];

        $errorMessage = $errorMessages[$status] ?? __($status);

        return back()->withErrors(['email' => $errorMessage]);
    }
    public function passwordResetGet(Request $request, string $token, )
    {
        return Inertia::render('pages/security/reset-password', ['token' => $token, 'email' => $request->input('email')]);
    }

    public function passwordResetPost(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
    public function passwordChangeGet()
    {
        return Inertia::render('pages/security/change-password');
    }
    public function passwordChangePut(ChangePasswordRequest $request)
    {
        $user = $request->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors([
                'old_password' => 'Старый пароль введён неверно.',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('user.show', ['user' => $user->id])->with('success', 'Пароль успешно изменён!');
    }
    public function changeEmailGet()
    {
        return Inertia::render('pages/security/change-email');
    }

    public function changeEmailPost(StoreChangeEmailTokenRequest $request)
    {
        user()->changeEmailTokens()->delete();

        $changeEmail = ChangeEmailToken::create([
            'email' => $request->input('email'),
            'user_id' => user()->id,
            'token' => Str::random(40),
        ]);

        SendChangeEmailJob::dispatch($changeEmail);

        return redirect()->route("user.show", ['user' => user()->id])
            ->with("message", "На указанный email было высланно письмо с подтверждением");
    }

    public function accept(string $token)
    {
        $changeEmail = ChangeEmailToken::where('token', $token)->first();

        if ($changeEmail === null)
            return abort(404);

        if (user()->id !== $changeEmail->user->id)
            return abort(403);


        user()->update(['email' => $changeEmail->email]);

        $changeEmail->delete();

        return redirect()->route("user.show", ['user' => user()->id])->with("message", 'Emal был успешно изменен');
    }

}
