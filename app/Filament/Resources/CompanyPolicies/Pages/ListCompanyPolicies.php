<?php

namespace App\Filament\Resources\CompanyPolicies\Pages;

use App\Filament\Resources\CompanyPolicies\CompanyPolicyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCompanyPolicies extends ListRecords
{
    protected static string $resource = CompanyPolicyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
