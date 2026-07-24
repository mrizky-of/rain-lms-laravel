<?php

namespace App\Filament\Resources\Quizzes;

use App\Filament\Resources\Quizzes\Pages\CreateQuiz;
use App\Filament\Resources\Quizzes\Pages\EditQuiz;
use App\Filament\Resources\Quizzes\Pages\ListQuizzes;
use App\Filament\Resources\Quizzes\Schemas\QuizForm;
use App\Filament\Resources\Quizzes\Tables\QuizzesTable;
use App\Models\Quiz;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class QuizResource extends Resource
{
    protected static ?string $model =
        Quiz::class;

    protected static ?string $navigationLabel =
        'Quizzes';

    protected static ?string $recordTitleAttribute =
        'title';

    public static function form(
        Schema $schema
    ): Schema {
        return QuizForm::configure(
            $schema
        );
    }

    public static function table(
        Table $table
    ): Table {
        return QuizzesTable::configure(
            $table
        );
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->manageableBy(
                auth()->user()
            );
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQuizzes::route('/'),

            'create' => CreateQuiz::route(
                '/create'
            ),

            'edit' => EditQuiz::route(
                '/{record}/edit'
            ),
        ];
    }
}
