<?php

namespace App\Filament\Resources\Sops\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SopForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('department_id')
                    ->numeric(),
                TextInput::make('code')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('version')
                    ->required()
                    ->default('1.0'),
                DatePicker::make('effective_date'),
                TextInput::make('owner'),
                Textarea::make('objective')
                    ->columnSpanFull(),
                Textarea::make('procedure')
                    ->columnSpanFull(),
                Textarea::make('parameters')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
