<?php

namespace App\Filament\Resources\Quizzes\Tables;

use App\Models\Quiz;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QuizzesTable
{
    public static function configure(
        Table $table
    ): Table {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),

                TextColumn::make(
                    'trainingModule.title'
                )
                    ->label(
                        'Module'
                    ),

                TextColumn::make(
                    'question_count'
                )
                    ->label(
                        'Target Soal'
                    ),

                TextColumn::make(
                    'actual_questions'
                )
                    ->label(
                        'Terisi'
                    )
                    ->getStateUsing(
                        fn (
                            Quiz $record
                        ): string => $record
                            ->questions()
                            ->count()
                            .
                            ' / '
                            .
                            $record
                                ->question_count
                    ),

                TextColumn::make(
                    'readiness'
                )
                    ->label(
                        'Status'
                    )
                    ->badge()
                    ->getStateUsing(
                        fn (
                            Quiz $record
                        ): string => $record
                            ->isQuestionComplete()
                            ? 'Ready'
                            : 'Belum Lengkap'
                    )
                    ->color(
                        fn (
                            string $state
                        ): string => $state === 'Ready'
                                ? 'success'
                                : 'warning'
                    ),

                TextColumn::make(
                    'creator.name'
                )
                    ->label(
                        'Contributor'
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
