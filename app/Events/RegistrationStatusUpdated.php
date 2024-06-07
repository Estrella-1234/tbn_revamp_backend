<?php

namespace App\Events;


use App\Models\EventRegistration;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RegistrationStatusUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $registration;

    public function __construct(EventRegistration $registration)
    {
        $this->registration = $registration;
    }
}
