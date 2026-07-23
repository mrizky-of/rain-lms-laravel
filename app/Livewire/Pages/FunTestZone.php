<?php

namespace App\Livewire\Pages;

use App\Models\FunTestQuestion;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Fun Test Zone - RAIN LMS')]
class FunTestZone extends Component
{
    public string $mode =
        'true_false';

    public array $answers = [];

    public ?int $score = null;

    public function updatedMode(): void
    {
        $this->reset(
            'answers',
            'score'
        );
    }

    public function resetTest(): void
    {
        $this->reset(
            'answers',
            'score'
        );
    }

    public function check(): void
    {
        $questions =
            $this->getQuestions();

        $correct = 0;

        foreach (
            $questions as $question
        ) {
            $expected =
                $question->answer[
                    'value'
                ] ?? null;

            $given =
                $this->answers[
                    $question->id
                ] ?? null;

            if (
                mb_strtolower(
                    trim(
                        (string) $expected
                    )
                )
                ===
                mb_strtolower(
                    trim(
                        (string) $given
                    )
                )
            ) {
                $correct++;
            }
        }

        $this->score =
            $questions->count() > 0
                ? (int) round(
                    (
                        $correct
                        / $questions->count()
                    ) * 100
                )
                : 0;
    }

    private function getQuestions()
    {
        return FunTestQuestion::query()
            ->where(
                'mode',
                $this->mode
            )
            ->where(
                'is_active',
                true
            )
            ->get();
    }

    public function render()
    {
        return view(
            'livewire.pages.fun-test-zone',
            [
                'questions' => $this->getQuestions(),

                'modes' => [
                    'drag_drop' => 'Drag & Drop',

                    'match_it' => 'Match It',

                    'word_scramble' => 'Word Scramble',

                    'speed_quiz' => 'Speed Quiz',

                    'true_false' => 'True or False',

                    'fill_blank' => 'Fill the Blank',
                ],
            ]
        );
    }
}
