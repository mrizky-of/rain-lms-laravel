<?php

namespace App\Filament\Resources\CompanyPolicies\Pages;

use App\Filament\Resources\CompanyPolicies\CompanyPolicyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCompanyPolicy extends CreateRecord
{
    protected static string $resource = CompanyPolicyResource::class;
}
