<?php

namespace App\Filament\Resources\TrainingModules\Pages;

use App\Filament\Resources\TrainingModules\TrainingModuleResource;
use App\Models\TrainingModule;
use Filament\Resources\Pages\CreateRecord;

class CreateTrainingModule extends CreateRecord
{
    protected static string $resource =
        TrainingModuleResource::class;

    protected function mutateFormDataBeforeCreate(
        array $data
    ): array {
        $data['created_by'] =
            auth()->id();

        $data['is_mandatory'] =
            (
                $data['category']
                ?? null
            )
            ===
            TrainingModule::CATEGORY_MANDATORY;

        return $data;
    }

    protected function afterCreate(): void
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
            /*
             * Mandatory dianggap global.
             */
            $this->record
                ->departments()
                ->detach();
        }
    }
}
