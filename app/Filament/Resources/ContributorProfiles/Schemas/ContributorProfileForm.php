<?php

namespace App\Filament\Resources\ContributorProfiles\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContributorProfileForm
{
    public static function configure(
        Schema $schema
    ): Schema {
        return $schema
            ->components([
                Section::make(
                    'Contributor'
                )
                    ->schema([
                        Select::make('user_id')
                            ->label('User')
                            ->options(
                                fn (): array => User::query()
                                    ->where(
                                        'status',
                                        User::STATUS_ACTIVE
                                    )
                                    ->where(
                                        'role',
                                        '!=',
                                        User::ROLE_SUPER_ADMIN
                                    )
                                    ->orderBy('name')
                                    ->pluck(
                                        'name',
                                        'id'
                                    )
                                    ->all()
                            )
                            ->searchable()
                            ->required()
                            ->unique(
                                'contributor_profiles',
                                'user_id',
                                ignoreRecord: true
                            ),

                        TextInput::make(
                            'nickname'
                        )
                            ->label(
                                'Nickname'
                            ),

                        Textarea::make('bio')
                            ->rows(5)
                            ->columnSpanFull(),

                        Textarea::make(
                            'expertise'
                        )
                            ->label(
                                'Keahlian / Expertise'
                            )
                            ->rows(4)
                            ->columnSpanFull(),

                        Toggle::make(
                            'is_active'
                        )
                            ->label(
                                'Contributor Aktif'
                            )
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }
}
