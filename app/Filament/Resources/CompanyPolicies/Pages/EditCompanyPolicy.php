<?php

namespace App\Filament\Resources\CompanyPolicies\Pages;

use App\Filament\Resources\CompanyPolicies\CompanyPolicyResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCompanyPolicy extends EditRecord
{
    protected static string $resource = CompanyPolicyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
