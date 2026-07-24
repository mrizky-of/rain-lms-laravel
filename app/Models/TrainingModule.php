<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class TrainingModule extends Model
{
    public const CATEGORY_MANDATORY =
        'mandatory';

    public const CATEGORY_BASIC_DIVISION =
        'basic_division';

    public const CATEGORY_DIVISION_KPI =
        'division_kpi';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail',
        'category',
        'duration_minutes',
        'kpi_points',
        'passing_score',

        'material_files',
        'material_file_names',

        'is_mandatory',
        'is_active',
        'published_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'duration_minutes' => 'integer',

            'kpi_points' => 'integer',

            'passing_score' => 'decimal:2',

            'material_files' => 'array',

            'material_file_names' => 'array',

            'is_mandatory' => 'boolean',

            'is_active' => 'boolean',

            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(
            function (
                TrainingModule $module
            ): void {
                if (
                    blank(
                        $module->slug
                    )
                ) {
                    $module->slug =
                        Str::slug(
                            $module->title
                        );
                }
            }
        );

        static::updating(
            function (
                TrainingModule $module
            ): void {
                if (
                    $module
                        ->isDirty(
                            'title'
                        )
                    && blank(
                        $module->slug
                    )
                ) {
                    $module->slug =
                        Str::slug(
                            $module->title
                        );
                }
            }
        );
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(
            Department::class,
            'department_training_module'
        )->withTimestamps();
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(
            Quiz::class
        );
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(
            Enrollment::class
        );
    }

    /*
     * Legacy relation dibiarkan sementara.
     * Admin baru tidak lagi membuat Lessons.
     */

    public function lessons(): HasMany
    {
        return $this->hasMany(
            Lesson::class
        )->orderBy('sort_order');
    }

    /*
    |--------------------------------------------------------------------------
    | PORTAL VISIBILITY
    |--------------------------------------------------------------------------
    */

    public function scopeVisibleFor(
        Builder $query,
        User $user
    ): Builder {
        return $query

            ->where(
                'is_active',
                true
            )

            ->where(
                function (
                    Builder $publishQuery
                ): void {
                    $publishQuery
                        ->whereNull(
                            'published_at'
                        )
                        ->orWhere(
                            'published_at',
                            '<=',
                            now()
                        );
                }
            )

            ->where(
                function (
                    Builder $visibilityQuery
                ) use (
                    $user
                ): void {
                    $visibilityQuery

                        ->where(
                            'category',
                            self::CATEGORY_MANDATORY
                        )

                        ->orWhereHas(
                            'departments',
                            function (
                                Builder $departmentQuery
                            ) use (
                                $user
                            ): void {
                                $departmentQuery
                                    ->where(
                                        'departments.id',
                                        $user
                                            ->department_id
                                    );
                            }
                        );
                }
            );
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN / CONTRIBUTOR SCOPE
    |--------------------------------------------------------------------------
    */

    public function scopeManageableBy(
        Builder $query,
        User $user
    ): Builder {
        if (
            $user->isSuperAdmin()
        ) {
            return $query;
        }

        if (
            $user->isContributor()
            && $user->department_id
        ) {
            return $query
                ->whereHas(
                    'departments',
                    fn (
                        Builder $departmentQuery
                    ) => $departmentQuery
                        ->where(
                            'departments.id',
                            $user
                                ->department_id
                        )
                );
        }

        return $query->whereRaw(
            '1 = 0'
        );
    }
}
