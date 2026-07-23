<?php

namespace App\Filament\Resources\TrainingModules;

use App\Filament\Resources\TrainingModules\Pages\CreateTrainingModule;
use App\Filament\Resources\TrainingModules\Pages\EditTrainingModule;
use App\Filament\Resources\TrainingModules\Pages\ListTrainingModules;
use App\Filament\Resources\TrainingModules\Schemas\TrainingModuleForm;
use App\Filament\Resources\TrainingModules\Tables\TrainingModulesTable;
use App\Models\TrainingModule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TrainingModuleResource extends Resource
{
    protected static ?string $model = TrainingModule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return TrainingModuleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TrainingModulesTable::configure($table);
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
            'index' => ListTrainingModules::route('/'),
            'create' => CreateTrainingModule::route('/create'),
            'edit' => EditTrainingModule::route('/{record}/edit'),
        ];
    }
}
