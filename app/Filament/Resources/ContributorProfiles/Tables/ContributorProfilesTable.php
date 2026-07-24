<?php

namespace App\Filament\Resources\ContributorProfiles\Tables;

use App\Models\ContributorProfile;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContributorProfilesTable
{
    public static function configure(
        Table $table
    ): Table {
        return $table

            ->columns([
                TextColumn::make(
                    'user.name'
                )
                    ->label('Nama')
                    ->searchable(),

                TextColumn::make(
                    'user.department.name'
                )
                    ->label(
                        'Department'
                    ),

                TextColumn::make(
                    'user.position_name'
                )
                    ->label('Jabatan'),

                TextColumn::make(
                    'contribution_count'
                )
                    ->label(
                        'Jumlah Kontribusi'
                    )
                    ->getStateUsing(
                        fn (
                            ContributorProfile $record
                        ): int => $record
                            ->contributionCount()
                    ),

                TextColumn::make(
                    'computed_rating'
                )
                    ->label('Rating')
                    ->suffix('%')
                    ->getStateUsing(
                        fn (
                            ContributorProfile $record
                        ): int => $record
                            ->computedRating()
                    ),

                IconColumn::make(
                    'is_active'
                )
                    ->boolean(),
            ])

            ->recordActions([
                EditAction::make(),
            ]);
    }
}
