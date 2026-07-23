<?php

namespace App\Filament\Resources\ManagerReviews\Pages;

use App\Filament\Resources\ManagerReviews\ManagerReviewResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListManagerReviews extends ListRecords
{
    protected static string $resource = ManagerReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
