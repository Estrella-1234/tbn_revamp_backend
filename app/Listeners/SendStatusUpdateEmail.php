<?php

namespace App\Listeners;

use App\Events\RegistrationStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendStatusUpdateEmail implements ShouldQueue
{
    public function handle(RegistrationStatusUpdated $event)
    {
        $registration = $event->registration;
        $eventTitle = $registration->event->judul;
        $registrationStatus = $registration->status;
        $userName = $registration->user->name; // Assuming the registration has a 'user' relationship and the user has a 'name' attribute

        $subject = "Your Registration Status for {$eventTitle} Has Been Updated";
        $messageBody = "Hello {$userName},\n\nWe are writing to inform you that your registration status for the event '{$eventTitle}' has been  {$registrationStatus}.\n\nThank you for your interest in our event.\n\nBest regards,\nTBN Indonesia";

        Mail::raw($messageBody, function ($message) use ($registration, $subject) {
            $message->to($registration->email)
                ->subject($subject);
        });
    }

}
