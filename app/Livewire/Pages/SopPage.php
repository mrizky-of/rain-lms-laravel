<?php

namespace App\Livewire\Pages;

use App\Models\Department;
use App\Models\Sop;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('SOP - RAIN LMS')]
class SopPage extends Component
{
    public string $departmentId = '';

    public function render()
    {
        $query =
            Sop::query()
                ->with(
                    'department'
                )
                ->where(
                    'is_active',
                    true
                )
                ->orderBy('code');

        if (
            filled($this->departmentId)
        ) {
            $query->where(
                'department_id',
                (int) $this->departmentId
            );
        }

        return view(
            'livewire.pages.sop-page',
            [
                'sops' => $query->get(),

                'departments' => Department::query()
                    ->where(
                        'is_active',
                        true
                    )
                    ->orderBy('name')
                    ->get(),
            ]
        );
    }
}
