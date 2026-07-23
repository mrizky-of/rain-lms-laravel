<?php

namespace App\Livewire\Pages;

use App\Models\TrainingModule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Kurikulum - RAIN LMS')]
class Curriculum extends Component
{
    public string $category = 'all';

    public function render()
    {
        $query =
            TrainingModule::query()
                ->visibleFor(
                    auth()->user()
                )
                ->with([
                    'departments',
                    'lessons',
                    'quizzes',
                ])
                ->orderByDesc(
                    'published_at'
                );

        if (
            $this->category !== 'all'
        ) {
            $query->where(
                'category',
                $this->category
            );
        }

        return view(
            'livewire.pages.curriculum',
            [
                'modules' => $query->get(),
            ]
        );
    }
}
