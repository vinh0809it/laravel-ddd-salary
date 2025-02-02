<?php
namespace Src\User\Application\UseCases\Jobs;

use Carbon\Carbon;
use Src\User\Domain\Services\UserService;

class SendBirthdayEmailJob
{
    public function __construct(private UserService $userService)
    {}

    public function handle(): void
    {
        $usersHaveBirthdayToday = $this->userService->getUsersHaveBirthdayAt(Carbon::now());
        dd($usersHaveBirthdayToday);
        foreach($usersHaveBirthdayToday as $user) {
            $this->userService->sendEmailHappyBirthday($user);
        }
    }
}