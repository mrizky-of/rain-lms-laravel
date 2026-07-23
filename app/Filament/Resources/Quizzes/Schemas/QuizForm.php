<?php

namespace App\Filament\Resources\Quizzes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class QuizForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('training_module_id')
                    ->relationship('trainingModule', 'title')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('time_limit_minutes')
                    ->required()
                    ->numeric()
                    ->default(30),
                TextInput::make('max_attempts')
                    ->required()
                    ->numeric()
                    ->default(3),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
