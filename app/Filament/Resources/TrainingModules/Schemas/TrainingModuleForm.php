<?php

namespace App\Filament\Resources\TrainingModules\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TrainingModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('thumbnail'),
                TextInput::make('category')
                    ->required()
                    ->default('mandatory'),
                TextInput::make('duration_minutes')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('kpi_points')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('passing_score')
                    ->required()
                    ->numeric()
                    ->default(70.0),
                Toggle::make('is_mandatory')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
                DateTimePicker::make('published_at'),
                TextInput::make('created_by')
                    ->numeric(),
            ]);
    }
}
