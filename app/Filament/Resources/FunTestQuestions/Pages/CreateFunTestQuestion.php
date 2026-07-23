<?php

namespace App\Filament\Resources\FunTestQuestions\Pages;

use App\Filament\Resources\FunTestQuestions\FunTestQuestionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFunTestQuestion extends CreateRecord
{
    protected static string $resource = FunTestQuestionResource::class;
}
