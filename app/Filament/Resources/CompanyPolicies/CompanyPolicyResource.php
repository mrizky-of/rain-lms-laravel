<?php

namespace App\Filament\Resources\CompanyPolicies;

use App\Filament\Resources\CompanyPolicies\Pages\CreateCompanyPolicy;
use App\Filament\Resources\CompanyPolicies\Pages\EditCompanyPolicy;
use App\Filament\Resources\CompanyPolicies\Pages\ListCompanyPolicies;
use App\Filament\Resources\CompanyPolicies\Schemas\CompanyPolicyForm;
use App\Filament\Resources\CompanyPolicies\Tables\CompanyPoliciesTable;
use App\Models\CompanyPolicy;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CompanyPolicyResource extends Resource
{
    protected static ?string $model =
        CompanyPolicy::class;

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedRectangleStack;

    public static function form(
        Schema $schema
    ): Schema {
        return CompanyPolicyForm::configure(
            $schema
        );
    }

    public static function table(
        Table $table
    ): Table {
        return CompanyPoliciesTable::configure(
            $table
        );
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompanyPolicies::route(
                '/'
            ),

            'create' => CreateCompanyPolicy::route(
                '/create'
            ),

            'edit' => EditCompanyPolicy::route(
                '/{record}/edit'
            ),
        ];
    }
}
