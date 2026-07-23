<?php

namespace App\Filament\Resources\ContributorProfiles;

use App\Filament\Resources\ContributorProfiles\Pages\CreateContributorProfile;
use App\Filament\Resources\ContributorProfiles\Pages\EditContributorProfile;
use App\Filament\Resources\ContributorProfiles\Pages\ListContributorProfiles;
use App\Filament\Resources\ContributorProfiles\Schemas\ContributorProfileForm;
use App\Filament\Resources\ContributorProfiles\Tables\ContributorProfilesTable;
use App\Models\ContributorProfile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ContributorProfileResource extends Resource
{
    protected static ?string $model = ContributorProfile::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ContributorProfileForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContributorProfilesTable::configure($table);
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
            'index' => ListContributorProfiles::route('/'),
            'create' => CreateContributorProfile::route('/create'),
            'edit' => EditContributorProfile::route('/{record}/edit'),
        ];
    }
}
