<?php

namespace Src\User\Application\Listeners;

use Illuminate\Support\Facades\Log;
use Src\User\Domain\Events\StoreUserEvent;

class StoreUserListener
{
    public function handle(StoreUserEvent $event)
    {
        $this->sendMailStoreUser();
        $this->sendNotiStoreUser();
        $this->logStoreUser();
    }

    public function sendMailStoreUser() {
        Log::info('storeUser sendmail');
    }

    public function sendNotiStoreUser() {
        Log::info('storeUser noti');
    }

    public function logStoreUser() {
        Log::info('storeUser log');
    }
}