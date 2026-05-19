<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Приглашение в services</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body{
            width: 100vw;
            height: 100vh;
        }

        .mail-layout {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .email-container {
            max-width: 600px;
            margin: auto;
            background-color: #f8fafd;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 20px 10px #eee;
            font-family: sans-serif;
            color: #333333;
            line-height: 1.6;
        }

        .logo {
            text-align: center;
            font-size: 30px;
            font-weight: bold;
            color: rgb(34, 34, 34);
            margin-bottom: 20px;
        }

        .text {
            text-align: center;
            font-size: 18px;
            color: rgb(34, 34, 34);
            margin-bottom: 10px;
        }

        .role {
            font-weight: bold;
            color: #0e2938;
        }

        .accept-button-wrapper {
            text-align: center;
            margin: 10px 0;
            padding: 10px 0px 20px
        }

        .accept-button {
            background-color: #83adf0;
            width: 300px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 6px;
            display: inline-block;
            border: none;
            cursor: pointer;
        }

        .footer {
            margin-top: 10px;
            font-size: 12px;
            color: #888;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="mail-layout">
        <div class="email-container">
            <div class="logo">
                {{ config('app.name') }}
            </div>
            <div class="text">
                Вы приглашены в {{ config('app.name') }}, подразделение:
                <span class="role">
                    {{ $invite->division->name }}
                </span><br>
            </div>
            <div class="accept-button-wrapper">
                <a href="{{ route('invites.accept', [
                    'token' => $invite->token,
                ]) }}"
                    class="accept-button" style="color: white;">
                    Подтвердить
                </a>
            </div>
            <div class="footer">
                &copy; Copyright {{ date('Y') }} {{ config('app.name') }}. Все права защищены.
            </div>
        </div>
    </div>
</body>

</html>
