<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DepartmentForm
{
    public static function configure(
        Schema $schema
    ): Schema {
        return $schema
            ->components([
                Section::make(
                    'Department'
                )
                    ->schema([
                        TextInput::make('code')
                            ->label(
                                'Kode'
                            )
                            ->required()
                            ->maxLength(50)
                            ->unique(
                                ignoreRecord: true
                            ),

                        TextInput::make('name')
                            ->label(
                                'Nama Department'
                            )
                            ->required()
                            ->maxLength(255),

                        Textarea::make(
                            'description'
                        )
                            ->label(
                                'Deskripsi'
                            )
                            ->rows(4)
                            ->columnSpanFull(),

                        Toggle::make(
                            'is_active'
                        )
                            ->label(
                                'Aktif'
                            )
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }
}
