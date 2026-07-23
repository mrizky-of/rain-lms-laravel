<?php

namespace App\Filament\Resources\ContributorProfiles\Pages;

use App\Filament\Resources\ContributorProfiles\ContributorProfileResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContributorProfile extends EditRecord
{
    protected static string $resource = ContributorProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
