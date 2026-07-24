<?php

namespace App\Filament\Widgets;

use App\Models\TrainingModule;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentTrainingModules extends TableWidget
{
    protected static ?int $sort =
        4;

    protected int|string|array $columnSpan =
        'full';

    public static function canView(): bool
    {
        return
            auth()->user()
                ?->canManageLearningContent()
            ?? false;
    }

    public function table(
        Table $table
    ): Table {
        return $table

            ->query(
                TrainingModule::query()
                    ->manageableBy(
                        auth()->user()
                    )
                    ->latest()
            )

            ->columns([
                TextColumn::make('title')
                    ->label(
                        'Module'
                    ),

                TextColumn::make(
                    'category'
                )
                    ->badge(),

                TextColumn::make(
                    'departments.name'
                )
                    ->label(
                        'Department'
                    )
                    ->badge(),

                TextColumn::make(
                    'creator.name'
                )
                    ->label(
                        'Contributor'
                    ),

                TextColumn::make(
                    'created_at'
                )
                    ->label(
                        'Created'
                    )
                    ->since(),
            ])

            ->defaultSort(
                'created_at',
                'desc'
            );
    }
}
