<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\Enrollment;
use App\Models\TrainingModule;
use App\Models\User;
use App\Notifications\TrainingAssignedNotification;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AssignmentService
{
    public function assign(
        User $manager,
        User $employee,
        TrainingModule $module,
        ?Carbon $dueAt = null,
        ?string $notes = null
    ): Assignment {
        if (
            ! $manager->isManager()
            && ! $manager->isHr()
            && ! $manager->isSuperAdmin()
        ) {
            throw new AuthorizationException(
                'Anda tidak berhak memberikan assignment.'
            );
        }

        if (
            $employee->role
            !== User::ROLE_KARYAWAN
        ) {
            throw new AuthorizationException(
                'Assignment hanya dapat diberikan kepada karyawan.'
            );
        }

        if (
            $manager->isManager()
            && (int) $manager->department_id
                !== (int) $employee->department_id
        ) {
            throw new AuthorizationException(
                'Manager hanya dapat memberikan assignment kepada anggota divisinya.'
            );
        }

        $assignment =
            DB::transaction(
                function () use (
                    $manager,
                    $employee,
                    $module,
                    $dueAt,
                    $notes
                ): Assignment {

                    $assignment =
                        Assignment::query()
                            ->updateOrCreate(
                                [
                                    'manager_id' => $manager->id,

                                    'user_id' => $employee->id,

                                    'training_module_id' => $module->id,

                                    'status' => 'assigned',
                                ],
                                [
                                    'notes' => $notes,

                                    'due_at' => $dueAt,
                                ]
                            );

                    $enrollment =
                        Enrollment::firstOrCreate(
                            [
                                'user_id' => $employee->id,

                                'training_module_id' => $module->id,
                            ],
                            [
                                'source' => 'manager',

                                'status' => 'not_started',

                                'progress_percentage' => 0,

                                'due_at' => $dueAt,
                            ]
                        );

                    /*
                     * Jangan reset training yang sudah completed.
                     */
                    if (
                        $enrollment->status
                        !== 'completed'
                    ) {
                        $enrollment->update([
                            'source' => 'manager',

                            'due_at' => $dueAt,
                        ]);
                    }

                    return $assignment;
                }
            );

        /*
         * Notification setelah transaksi database sukses.
         */
        $employee->notify(
            new TrainingAssignedNotification(
                $assignment->load(
                    'trainingModule'
                )
            )
        );

        return $assignment;
    }
}
