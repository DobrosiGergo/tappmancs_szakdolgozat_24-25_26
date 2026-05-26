<?php

namespace App\Notifications;

use App\Models\Shelter;
use Illuminate\Notifications\Notification;

class WorkerAssigned extends Notification
{
    public function __construct(public readonly Shelter $shelter) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'shelter_uuid' => $this->shelter->uuid,
            'shelter_name' => $this->shelter->name,
            'message'      => "Felvettek a(z) {$this->shelter->name} menhely csapatába.",
        ];
    }
}
