<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(
        Table $table
    ): Table {
        return $table

            ->columns([
                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make(
                    'department.name'
                )
                    ->label(
                        'Department'
                    )
                    ->sortable(),

                TextColumn::make(
                    'position_name'
                )
                    ->label(
                        'Jabatan'
                    )
                    ->searchable(),

                TextColumn::make('role')
                    ->badge()
                    ->formatStateUsing(
                        fn (
                            string $state
                        ): string => match ($state) {
                            User::ROLE_KARYAWAN => 'Karyawan',

                            User::ROLE_MANAGER => 'Manager',

                            User::ROLE_HR => 'HR',

                            User::ROLE_SUPER_ADMIN => 'Super Admin',

                            default => $state,
                        }
                    ),

                TextColumn::make('status')
                    ->badge()
                    ->color(
                        fn (
                            string $state
                        ): string => $state
                            === User::STATUS_ACTIVE
                                ? 'success'
                                : 'danger'
                    ),

                TextColumn::make(
                    'contributorProfile.is_active'
                )
                    ->label(
                        'Contributor'
                    )
                    ->formatStateUsing(
                        fn (
                            $state
                        ): string => $state
                                ? 'Ya'
                                : '-'
                    ),
            ])

            ->filters([
                SelectFilter::make(
                    'role'
                )
                    ->options([
                        User::ROLE_KARYAWAN => 'Karyawan',

                        User::ROLE_MANAGER => 'Manager',

                        User::ROLE_HR => 'HR',

                        User::ROLE_SUPER_ADMIN => 'Super Admin',
                    ]),

                SelectFilter::make(
                    'department'
                )
                    ->relationship(
                        'department',
                        'name'
                    ),
            ])

            ->recordActions([
                EditAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
