<?php

namespace App\Filament\Resources\TrainingModules\Pages;

use App\Filament\Resources\TrainingModules\TrainingModuleResource;
use App\Models\TrainingModule;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTrainingModule extends EditRecord
{
    protected static string $resource =
        TrainingModuleResource::class;

    protected function mutateFormDataBeforeSave(
        array $data
    ): array {
        $data['is_mandatory'] =
            (
                $data['category']
                ?? null
            )
            ===
            TrainingModule::CATEGORY_MANDATORY;

        return $data;
    }

    protected function afterSave(): void
    {
        $user =
            auth()->user();

        if (
            $user->isContributor()
            && ! $user->isSuperAdmin()
        ) {
            $this->record
                ->departments()
                ->sync([
                    $user->department_id,
                ]);
        }

        if (
            $this->record->category
            ===
            TrainingModule::CATEGORY_MANDATORY
            && $user->isSuperAdmin()
        ) {
            $this->record
                ->departments()
                ->detach();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
