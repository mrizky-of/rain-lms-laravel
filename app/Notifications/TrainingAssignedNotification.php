<?php

namespace App\Notifications;

use App\Models\Assignment;
use Illuminate\Notifications\Notification;

class TrainingAssignedNotification extends Notification
{
    public function __construct(
        public Assignment $assignment
    ) {}

    public function via(
        object $notifiable
    ): array {
        return [
            'database',
        ];
    }

    public function toArray(
        object $notifiable
    ): array {
        return [
            'title' => 'Training Baru',

            'message' => 'Anda mendapatkan assignment training: '
                .(
                    $this->assignment
                        ->trainingModule
                        ?->title
                    ?? 'Training'
                ),

            'assignment_id' => $this->assignment->id,

            'training_module_id' => $this->assignment
                ->training_module_id,

            'due_at' => $this->assignment
                ->due_at
                ?->toISOString(),
        ];
    }
}
