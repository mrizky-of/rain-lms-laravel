<?php

namespace App\Filament\Resources\FunTestQuestions\Pages;

use App\Filament\Resources\FunTestQuestions\FunTestQuestionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFunTestQuestion extends EditRecord
{
    protected static string $resource = FunTestQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
