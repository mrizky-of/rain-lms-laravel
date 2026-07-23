<?php

namespace App\Filament\Resources\FunTestQuestions;

use App\Filament\Resources\FunTestQuestions\Pages\CreateFunTestQuestion;
use App\Filament\Resources\FunTestQuestions\Pages\EditFunTestQuestion;
use App\Filament\Resources\FunTestQuestions\Pages\ListFunTestQuestions;
use App\Filament\Resources\FunTestQuestions\Schemas\FunTestQuestionForm;
use App\Filament\Resources\FunTestQuestions\Tables\FunTestQuestionsTable;
use App\Models\FunTestQuestion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FunTestQuestionResource extends Resource
{
    protected static ?string $model = FunTestQuestion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return FunTestQuestionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FunTestQuestionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFunTestQuestions::route('/'),
            'create' => CreateFunTestQuestion::route('/create'),
            'edit' => EditFunTestQuestion::route('/{record}/edit'),
        ];
    }
}
