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

        Mail::raw("Your registration status for event '{$registration->event->judul}' has been updated to '{$registration->status}'", function ($message) use ($registration) {
            $message->to($registration->email)
                ->subject('Registration Status Update');
        });
    }
}
