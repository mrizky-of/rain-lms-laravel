<?php

namespace App\Livewire\Pages;

use App\Models\ContributorProfile;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Kontributor Modul - RAIN LMS')]
class ContributorPage extends Component
{
    public function render()
    {
        return view(
            'livewire.pages.contributor-page',
            [
                'contributors' => ContributorProfile::query()
                    ->with([
                        'user.department',
                        'user.position',
                        'trainingModules',
                    ])
                    ->orderByDesc(
                        'rating'
                    )
                    ->get(),
            ]
        );
    }
}
