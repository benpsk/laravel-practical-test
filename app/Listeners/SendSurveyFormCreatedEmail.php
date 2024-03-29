<?php

namespace App\Listeners;

use App\Events\SurveyFormCreated;
use App\Mail\SendEmail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendSurveyFormCreatedEmail implements ShouldQueue
{

    /**
     * The number of times the queued listener may be attempted.
     *
     * @var int
     */
    public int $tries = 1;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SurveyFormCreated $event): void
    {
        /** @var User $user */
        $user = $event->surveyForm->user;
        Mail::to($user->email)
            ->send(new SendEmail($event->surveyForm));
    }

    /**
     * Handle a job failure.
     */
    public function failed(SurveyFormCreated $event, Throwable $e): void
    {
        logger()->debug($e->getMessage() . $e->getLine() . ' ----- ' . $e->getFile());
    }
}
