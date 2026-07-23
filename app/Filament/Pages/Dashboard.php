<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    /*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
    */

    protected static ?string $navigationLabel =
        'Dashboard';

    /*
    |--------------------------------------------------------------------------
    | Page Title
    |--------------------------------------------------------------------------
    */

    protected static ?string $title =
        'Dashboard';

    /*
    |--------------------------------------------------------------------------
    | Heading
    |--------------------------------------------------------------------------
    */

    public function getHeading(): string
    {
        return
            'Dashboard Administration';
    }

    /*
    |--------------------------------------------------------------------------
    | Subheading
    |--------------------------------------------------------------------------
    */

    public function getSubheading(): ?string
    {
        return
            'Monitor aktivitas pengguna, training, assignment, dan performa RAIN LMS.';
    }

    /*
    |--------------------------------------------------------------------------
    | Widget Grid
    |--------------------------------------------------------------------------
    |
    | md:
    |
    | 2 columns
    |
    | xl:
    |
    | 4 columns
    |
    */

    public function getColumns(): int|array
    {
        return [

            'md' =>
                2,

            'xl' =>
                4,

        ];
    }
}