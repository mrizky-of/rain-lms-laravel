<?php

namespace App\Filament\Resources\ManagerReviews;

use App\Filament\Resources\ManagerReviews\Pages\CreateManagerReview;
use App\Filament\Resources\ManagerReviews\Pages\EditManagerReview;
use App\Filament\Resources\ManagerReviews\Pages\ListManagerReviews;
use App\Filament\Resources\ManagerReviews\Schemas\ManagerReviewForm;
use App\Filament\Resources\ManagerReviews\Tables\ManagerReviewsTable;
use App\Models\ManagerReview;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ManagerReviewResource extends Resource
{
    protected static ?string $model =
        ManagerReview::class;

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedRectangleStack;

    public static function form(
        Schema $schema
    ): Schema {
        return ManagerReviewForm::configure(
            $schema
        );
    }

    public static function table(
        Table $table
    ): Table {
        return ManagerReviewsTable::configure(
            $table
        );
    }

    public static function getEloquentQuery(): Builder
    {
        $query =
            parent::getEloquentQuery();

        /** @var User|null $user */
        $user =
            auth()->user();

        if (! $user) {
            return $query->whereRaw(
                '1 = 0'
            );
        }

        if (
            $user->isManager()
        ) {
            return $query->where(
                'manager_id',
                $user->id
            );
        }

        return $query;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListManagerReviews::route(
                '/'
            ),

            'create' => CreateManagerReview::route(
                '/create'
            ),

            'edit' => EditManagerReview::route(
                '/{record}/edit'
            ),
        ];
    }
}
