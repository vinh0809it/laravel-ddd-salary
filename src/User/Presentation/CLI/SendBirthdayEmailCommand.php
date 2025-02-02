<?php
namespace Src\User\Presentation\CLI;

use Illuminate\Console\Command;
use Src\User\Application\UseCases\Jobs\SendBirthdayEmailJob;

class SendBirthdayEmailCommand extends Command
{
    protected $signature = 'send-email-birthday';
    protected $description = 'Send birthday email to users';

    public function __construct(private SendBirthdayEmailJob $job) {
        parent::__construct();
    }

    public function handle()
    {
        $this->job->handle();
    }
}