<?php

namespace App\Filament\Resources\CompanyPolicies\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CompanyPolicyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('summary')
                    ->columnSpanFull(),
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('legal_reference'),
                TextInput::make('category'),
                TextInput::make('target')
                    ->required()
                    ->default('all'),
                TextInput::make('version')
                    ->required()
                    ->default('1.0'),
                DatePicker::make('effective_date'),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
