<?php

namespace App\Filament\Resources\TrainingModules\Tables;

use App\Models\TrainingModule;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TrainingModulesTable
{
    public static function configure(
        Table $table
    ): Table {
        return $table

            ->columns([
                TextColumn::make('title')
                    ->label(
                        'Module'
                    )
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category')
                    ->badge()
                    ->formatStateUsing(
                        fn (
                            string $state
                        ): string => match ($state) {
                            TrainingModule::CATEGORY_MANDATORY => 'Mandatory',

                            TrainingModule::CATEGORY_BASIC_DIVISION => 'Basic Division',

                            TrainingModule::CATEGORY_DIVISION_KPI => 'Division KPI',

                            default => $state,
                        }
                    ),

                TextColumn::make(
                    'departments.name'
                )
                    ->label(
                        'Department'
                    )
                    ->badge()
                    ->separator(', '),

                TextColumn::make(
                    'creator.name'
                )
                    ->label(
                        'Contributor'
                    ),

                TextColumn::make(
                    'material_files'
                )
                    ->label('Files')
                    ->getStateUsing(
                        fn (
                            TrainingModule $record
                        ): int => count(
                            $record
                                ->material_files
                            ?? []
                        )
                    ),

                TextColumn::make(
                    'kpi_points'
                )
                    ->label(
                        'KPI'
                    ),

                IconColumn::make(
                    'is_active'
                )
                    ->boolean(),
            ])

            ->filters([
                SelectFilter::make(
                    'category'
                )
                    ->options([
                        TrainingModule::CATEGORY_MANDATORY => 'Mandatory',

                        TrainingModule::CATEGORY_BASIC_DIVISION => 'Basic Division',

                        TrainingModule::CATEGORY_DIVISION_KPI => 'Division KPI',
                    ]),
            ])

            ->recordActions([
                EditAction::make(),
            ]);
    }
}
