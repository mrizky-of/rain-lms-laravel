<?php

namespace App\Filament\Resources\TrainingModules\Schemas;

use App\Models\TrainingModule;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TrainingModuleForm
{
    public static function configure(
        Schema $schema
    ): Schema {
        return $schema
            ->components([

                Section::make(
                    'Informasi Modul'
                )
                    ->schema([
                        TextInput::make('title')
                            ->label(
                                'Judul Modul'
                            )
                            ->required()
                            ->maxLength(255),

                        Select::make(
                            'category'
                        )
                            ->label(
                                'Category'
                            )
                            ->options(
                                fn (): array => auth()
                                    ->user()
                                    ->isSuperAdmin()
                                    ? [
                                        TrainingModule::CATEGORY_MANDATORY => 'Mandatory',

                                        TrainingModule::CATEGORY_BASIC_DIVISION => 'Basic Division',

                                        TrainingModule::CATEGORY_DIVISION_KPI => 'Division KPI',
                                    ]
                                    : [
                                        TrainingModule::CATEGORY_BASIC_DIVISION => 'Basic Division',

                                        TrainingModule::CATEGORY_DIVISION_KPI => 'Division KPI',
                                    ]
                            )
                            ->required(),

                        Select::make(
                            'departments'
                        )
                            ->label(
                                'Target Department'
                            )
                            ->relationship(
                                'departments',
                                'name'
                            )
                            ->multiple()
                            ->searchable()
                            ->preload()

                            ->disabled(
                                fn (): bool => ! auth()
                                    ->user()
                                    ->isSuperAdmin()
                            )

                            ->helperText(
                                fn (): string => auth()
                                    ->user()
                                    ->isSuperAdmin()
                                    ? 'Mandatory dapat berlaku global. Basic Division / Division KPI pilih target department.'
                                    : 'Contributor otomatis hanya dapat mengelola konten department sendiri.'
                            ),

                        Textarea::make(
                            'description'
                        )
                            ->label(
                                'Deskripsi'
                            )
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),

                        TextInput::make(
                            'duration_minutes'
                        )
                            ->label(
                                'Durasi (menit)'
                            )
                            ->numeric()
                            ->required()
                            ->minValue(1),

                        TextInput::make(
                            'kpi_points'
                        )
                            ->label(
                                'KPI Points'
                            )
                            ->numeric()
                            ->default(0)
                            ->minValue(0),

                        TextInput::make(
                            'passing_score'
                        )
                            ->label(
                                'Passing Score'
                            )
                            ->numeric()
                            ->default(75)
                            ->minValue(0)
                            ->maxValue(100),

                        DateTimePicker::make(
                            'published_at'
                        )
                            ->label(
                                'Publish Date'
                            ),

                        Toggle::make(
                            'is_active'
                        )
                            ->label(
                                'Aktif'
                            )
                            ->default(true),
                    ])
                    ->columns(2),

                Section::make(
                    'Materi Training'
                )
                    ->description(
                        'Upload video, PDF, Word, atau PowerPoint. File path akan dikelola otomatis oleh sistem.'
                    )
                    ->schema([
                        FileUpload::make(
                            'material_files'
                        )
                            ->label(
                                'Upload Materi'
                            )
                            ->multiple()

                            ->disk('public')

                            ->directory(
                                'training-modules'
                            )

                            ->acceptedFileTypes([
                                'video/mp4',
                                'video/quicktime',
                                'video/webm',

                                'application/pdf',

                                'application/msword',

                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',

                                'application/vnd.ms-powerpoint',

                                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                            ])

                            ->storeFileNamesIn(
                                'material_file_names'
                            )

                            ->reorderable()
                            ->downloadable()
                            ->openable()

                            ->maxFiles(10)

                            ->maxSize(
                                102400
                            )

                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
