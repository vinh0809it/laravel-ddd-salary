<?php

namespace Src\User\Application\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Src\User\Domain\Events\StoreUserEvent;
use Src\User\Domain\Entities\User;

class StoreUserListener
{
    public function handle(StoreUserEvent $event)
    {
        $this->sendMailStoreUser();
        $this->sendNotiStoreUser();
        $this->logStoreUser();
    }

    public function sendMailStoreUser() {
        // Mail::to($event->user->email)->send(new WelcomeEmail($event->user));
        Log::info('storeUser sendmail');
    }

    public function sendNotiStoreUser() {
        Log::info('storeUser noti');
    }

    public function logStoreUser() {
        Log::info('storeUser log');
    }
}