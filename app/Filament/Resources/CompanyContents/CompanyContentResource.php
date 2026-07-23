<?php

namespace App\Filament\Resources\CompanyContents;

use App\Filament\Resources\CompanyContents\Pages\CreateCompanyContent;
use App\Filament\Resources\CompanyContents\Pages\EditCompanyContent;
use App\Filament\Resources\CompanyContents\Pages\ListCompanyContents;
use App\Filament\Resources\CompanyContents\Schemas\CompanyContentForm;
use App\Filament\Resources\CompanyContents\Tables\CompanyContentsTable;
use App\Models\CompanyContent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CompanyContentResource extends Resource
{
    protected static ?string $model = CompanyContent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return CompanyContentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompanyContentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompanyContents::route('/'),
            'create' => CreateCompanyContent::route('/create'),
            'edit' => EditCompanyContent::route('/{record}/edit'),
        ];
    }
}
