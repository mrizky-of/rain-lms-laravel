<?php

namespace App\Filament\Resources\Quizzes\Schemas;

use App\Models\TrainingModule;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QuizForm
{
    public static function configure(
        Schema $schema
    ): Schema {
        return $schema
            ->components([
                Section::make(
                    'Quiz'
                )
                    ->schema([
                        Select::make(
                            'training_module_id'
                        )
                            ->label(
                                'Training Module'
                            )

                            ->options(
                                fn (): array => TrainingModule::query()
                                    ->manageableBy(
                                        auth()->user()
                                    )
                                    ->orderBy('title')
                                    ->pluck(
                                        'title',
                                        'id'
                                    )
                                    ->all()
                            )

                            ->searchable()
                            ->required(),

                        TextInput::make('title')
                            ->label(
                                'Judul Quiz'
                            )
                            ->required()
                            ->maxLength(255),

                        TextInput::make(
                            'question_count'
                        )
                            ->label(
                                'Jumlah Soal'
                            )
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(100)
                            ->default(10),

                        TextInput::make(
                            'time_limit_minutes'
                        )
                            ->label(
                                'Durasi (menit)'
                            )
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->default(20),

                        TextInput::make(
                            'max_attempts'
                        )
                            ->label(
                                'Maksimal Percobaan'
                            )
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->default(3),

                        Toggle::make(
                            'is_active'
                        )
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }
}
