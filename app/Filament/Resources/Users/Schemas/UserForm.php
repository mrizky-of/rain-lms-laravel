<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(
        Schema $schema
    ): Schema {
        return $schema
            ->components([

                Section::make(
                    'Informasi Karyawan'
                )
                    ->schema([
                        TextInput::make('nik')
                            ->label('NIK')
                            ->maxLength(100)
                            ->unique(
                                ignoreRecord: true
                            ),

                        TextInput::make('name')
                            ->label(
                                'Nama Lengkap'
                            )
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(
                                ignoreRecord: true
                            ),

                        TextInput::make('phone')
                            ->label(
                                'Nomor Telepon'
                            )
                            ->tel(),

                        Select::make(
                            'department_id'
                        )
                            ->label(
                                'Department'
                            )
                            ->relationship(
                                'department',
                                'name'
                            )
                            ->searchable()
                            ->preload(),

                        TextInput::make(
                            'position_name'
                        )
                            ->label(
                                'Jabatan / Position'
                            )
                            ->placeholder(
                                'Contoh: SCM CI Manager'
                            )
                            ->maxLength(255),

                        Select::make(
                            'manager_id'
                        )
                            ->label(
                                'Atasan / Manager'
                            )
                            ->options(
                                fn (): array => User::query()
                                    ->where(
                                        'status',
                                        User::STATUS_ACTIVE
                                    )
                                    ->whereIn(
                                        'role',
                                        [
                                            User::ROLE_MANAGER,
                                            User::ROLE_HR,
                                        ]
                                    )
                                    ->orderBy('name')
                                    ->pluck(
                                        'name',
                                        'id'
                                    )
                                    ->all()
                            )
                            ->searchable(),

                        Select::make('role')
                            ->required()
                            ->options([
                                User::ROLE_KARYAWAN => 'Karyawan',

                                User::ROLE_MANAGER => 'Manager',

                                User::ROLE_HR => 'HR',

                                User::ROLE_SUPER_ADMIN => 'Super Admin',
                            ]),

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
                            ->minValue(0)
                            ->default(12),

                        DatePicker::make(
                            'join_date'
                        )
                            ->label(
                                'Tanggal Bergabung'
                            ),
                    ])
                    ->columns(2),

                Section::make(
                    'Authentication'
                )
                    ->schema([
                        TextInput::make(
                            'password'
                        )
                            ->password()
                            ->revealable()
                            ->minLength(8)

                            ->required(
                                fn (
                                    string $operation
                                ): bool => $operation
                                    === 'create'
                            )

                            ->dehydrated(
                                fn (
                                    ?string $state
                                ): bool => filled(
                                    $state
                                )
                            )

                            ->helperText(
                                'Kosongkan saat edit jika password tidak ingin diubah.'
                            ),
                    ]),
            ]);
    }
}
