<?php

namespace App\Filament\Widgets;

use App\Models\Assignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentAssignments
    extends TableWidget
{
    /*
    |--------------------------------------------------------------------------
    | Position
    |--------------------------------------------------------------------------
    */

    protected static ?int $sort =
        4;

    /*
    |--------------------------------------------------------------------------
    | Full Width
    |--------------------------------------------------------------------------
    */

    protected int|string|array
        $columnSpan =
            'full';

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    public function table(
        Table $table
    ): Table {
        return $table

            /*
            |--------------------------------------------------------------------------
            | Query
            |--------------------------------------------------------------------------
            */

            ->query(

                Assignment::query()

                    ->with([

                        'user',

                        'trainingModule',

                        'manager',

                    ])

                    ->latest()

            )

            /*
            |--------------------------------------------------------------------------
            | Columns
            |--------------------------------------------------------------------------
            */

            ->columns([

                /*
                |--------------------------------------------------------------------------
                | Employee
                |--------------------------------------------------------------------------
                */

                TextColumn::make(
                    'user.name'
                )
                    ->label(
                        'Karyawan'
                    )
                    ->searchable()
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | Module
                |--------------------------------------------------------------------------
                */

                TextColumn::make(
                    'trainingModule.title'
                )
                    ->label(
                        'Training Module'
                    )
                    ->searchable()
                    ->wrap(),

                /*
                |--------------------------------------------------------------------------
                | Manager
                |--------------------------------------------------------------------------
                */

                TextColumn::make(
                    'manager.name'
                )
                    ->label(
                        'Assigned By'
                    )
                    ->placeholder(
                        '-'
                    ),

                /*
                |--------------------------------------------------------------------------
                | Status
                |--------------------------------------------------------------------------
                */

                TextColumn::make(
                    'status'
                )
                    ->label(
                        'Status'
                    )
                    ->badge()

                    ->formatStateUsing(

                        fn (
                            string $state
                        ): string =>

                            match (
                                $state
                            ) {

                                'assigned' =>
                                    'Assigned',

                                'not_started' =>
                                    'Belum Mulai',

                                'in_progress' =>
                                    'In Progress',

                                'completed' =>
                                    'Completed',

                                default =>
                                    ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $state
                                        )
                                    ),

                            }

                    )

                    ->color(

                        fn (
                            string $state
                        ): string =>

                            match (
                                $state
                            ) {

                                'completed' =>
                                    'success',

                                'in_progress' =>
                                    'info',

                                'assigned' =>
                                    'warning',

                                'not_started' =>
                                    'gray',

                                default =>
                                    'gray',

                            }

                    ),

                /*
                |--------------------------------------------------------------------------
                | Deadline
                |--------------------------------------------------------------------------
                */

                TextColumn::make(
                    'due_at'
                )
                    ->label(
                        'Deadline'
                    )
                    ->dateTime(
                        'd M Y H:i'
                    )
                    ->placeholder(
                        '-'
                    )
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | Created
                |--------------------------------------------------------------------------
                */

                TextColumn::make(
                    'created_at'
                )
                    ->label(
                        'Dibuat'
                    )
                    ->since()
                    ->sortable(),

            ])

            /*
            |--------------------------------------------------------------------------
            | Sort
            |--------------------------------------------------------------------------
            */

            ->defaultSort(
                'created_at',
                'desc'
            )

            /*
            |--------------------------------------------------------------------------
            | Pagination
            |--------------------------------------------------------------------------
            |
            | Dashboard hanya tampil 5 per halaman.
            |
            */

            ->paginated([
                5,
            ])

            ->defaultPaginationPageOption(
                5
            )

            /*
            |--------------------------------------------------------------------------
            | Empty State
            |--------------------------------------------------------------------------
            */

            ->emptyStateHeading(
                'Belum ada assignment'
            )

            ->emptyStateDescription(
                'Assignment training terbaru akan muncul di sini.'
            );
    }
}