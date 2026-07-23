<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(
        Schema $schema
    ): Schema {
        return $schema
            ->components([
                TextInput::make('nik')
                    ->label('NIK')
                    ->unique(
                        ignoreRecord: true
                    )
                    ->maxLength(50),

                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(
                        ignoreRecord: true
                    ),

                TextInput::make('phone')
                    ->label('No. Telepon')
                    ->maxLength(30),

                Select::make('department_id')
                    ->label('Divisi')
                    ->relationship(
                        'department',
                        'name'
                    )
                    ->searchable()
                    ->preload(),

                Select::make('position_id')
                    ->label('Jabatan')
                    ->relationship(
                        'position',
                        'name'
                    )
                    ->searchable()
                    ->preload(),

                Select::make('manager_id')
                    ->label('Atasan')
                    ->relationship(
                        'manager',
                        'name'
                    )
                    ->searchable()
                    ->preload(),

                Select::make('role')
                    ->required()
                    ->options([
                        User::ROLE_KARYAWAN => 'Karyawan',

                        User::ROLE_MANAGER => 'Manager',

                        User::ROLE_HR => 'HR',

                        User::ROLE_SUPER_ADMIN => 'Super Admin',
                    ])
                    ->default(
                        User::ROLE_KARYAWAN
                    ),

                Select::make('status')
                    ->required()
                    ->options([
                        User::STATUS_ACTIVE => 'Active',

                        User::STATUS_INACTIVE => 'Inactive',
                    ])
                    ->default(
                        User::STATUS_ACTIVE
                    ),

                TextInput::make('semester')
                    ->numeric()
                    ->minValue(1)
                    ->default(1),

                TextInput::make(
                    'annual_leave_quota'
                )
                    ->label(
                        'Kuota Cuti Tahunan'
                    )
                    ->numeric()
                    ->default(12),

                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->required(
                        fn (
                            string $operation
                        ): bool => $operation === 'create'
                    )
                    ->dehydrated(
                        fn (
                            ?string $state
                        ): bool => filled($state)
                    ),
            ]);
    }
}
