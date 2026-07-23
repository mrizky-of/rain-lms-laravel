<?php

namespace App\Filament\Resources\ManagerReviews\Pages;

use App\Filament\Resources\ManagerReviews\ManagerReviewResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditManagerReview extends EditRecord
{
    protected static string $resource = ManagerReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
