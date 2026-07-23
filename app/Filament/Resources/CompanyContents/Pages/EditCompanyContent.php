<?php

namespace App\Filament\Resources\CompanyContents\Pages;

use App\Filament\Resources\CompanyContents\CompanyContentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCompanyContent extends EditRecord
{
    protected static string $resource = CompanyContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
