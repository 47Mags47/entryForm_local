<?php

// use App\Models\User;
// use App\Models\UserRole;

require_once __DIR__ . '/Helpers/invalidDataGenerate.php';
// require_once __DIR__ . '/Helpers/TestLogger.php';

// pest()->beforeEach(function(){
//     $this->seed();

//     $this->admin = User::factory()->make(['role_id' => UserRole::byCode('admin')->id]);
//     $this->division_admin = User::factory()->make(['role_id' => UserRole::byCode('division_admin')->id]);
//     $this->division_worker = User::factory()->make(['role_id' => UserRole::byCode('division_worker')->id]);

//     Log::channel('test')->info('Start Test: ' . $this->getPrintableTestCaseMethodName(), [
//         'users' => [
//             'admin' => $this->admin,
//             'division_admin' => $this->division_admin,
//             'division_worker' => $this->division_worker,
//         ]
//     ]);
// });

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(Tests\Cases\ControllerCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature/*');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}

