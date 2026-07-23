<?php

namespace App\Filament\Resources\ContributorProfiles\Pages;

use App\Filament\Resources\ContributorProfiles\ContributorProfileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListContributorProfiles extends ListRecords
{
    protected static string $resource = ContributorProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
