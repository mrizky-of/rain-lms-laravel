<?php

namespace App\Filament\Resources\FunTestQuestions\Pages;

use App\Filament\Resources\FunTestQuestions\FunTestQuestionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFunTestQuestions extends ListRecords
{
    protected static string $resource = FunTestQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
