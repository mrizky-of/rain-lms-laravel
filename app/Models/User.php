<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    public const ROLE_KARYAWAN =
        'karyawan';

    public const ROLE_MANAGER =
        'manager';

    public const ROLE_HR =
        'hr';

    public const ROLE_SUPER_ADMIN =
        'super_admin';

    public const STATUS_ACTIVE =
        'active';

    public const STATUS_INACTIVE =
        'inactive';

    protected $fillable = [
        'name',
        'email',
        'password',
        'nik',
        'phone',
        'avatar',
        'department_id',

        /*
         * position_id masih legacy.
         * Data baru memakai position_name.
         */

        'position_name',

        'manager_id',
        'role',
        'status',
        'semester',
        'annual_leave_quota',
        'join_date',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',

            'password' => 'hashed',

            'join_date' => 'date',

            'semester' => 'integer',

            'annual_leave_quota' => 'integer',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | FILAMENT ACCESS
    |--------------------------------------------------------------------------
    |
    | Super Admin:
    | full admin.
    |
    | HR:
    | Information & Regulation.
    |
    | Contributor:
    | Learning Management sesuai divisi.
    |
    */

    public function canAccessPanel(
        Panel $panel
    ): bool {
        if (
            $panel->getId()
            !== 'admin'
        ) {
            return false;
        }

        if (! $this->isActive()) {
            return false;
        }

        return
            $this->isSuperAdmin()
            || $this->isHr()
            || $this->isContributor();
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function initials(): string
    {
        return Str::of(
            $this->name
        )
            ->explode(' ')
            ->filter()
            ->take(2)
            ->map(
                fn (
                    string $name
                ): string => Str::upper(
                    Str::substr(
                        $name,
                        0,
                        1
                    )
                )
            )
            ->implode('');
    }

    public function isActive(): bool
    {
        return
            $this->status
            === self::STATUS_ACTIVE;
    }

    public function isKaryawan(): bool
    {
        return
            $this->role
            === self::ROLE_KARYAWAN;
    }

    public function isManager(): bool
    {
        return
            $this->role
            === self::ROLE_MANAGER;
    }

    public function isHr(): bool
    {
        return
            $this->role
            === self::ROLE_HR;
    }

    public function isSuperAdmin(): bool
    {
        return
            $this->role
            === self::ROLE_SUPER_ADMIN;
    }

    public function isContributor(): bool
    {
        return $this
            ->contributorProfile()
            ->where(
                'is_active',
                true
            )
            ->exists();
    }

    public function canManageLearningContent(): bool
    {
        return
            $this->isSuperAdmin()
            || $this->isContributor();
    }

    public function canManageCorporateContent(): bool
    {
        return
            $this->isSuperAdmin()
            || $this->isHr();
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function department(): BelongsTo
    {
        return $this->belongsTo(
            Department::class
        );
    }

    /*
     * Legacy relationship boleh tetap ada
     * selama positions table belum dibuang.
     */

    public function position(): BelongsTo
    {
        return $this->belongsTo(
            Position::class
        );
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'manager_id'
        );
    }

    public function subordinates(): HasMany
    {
        return $this->hasMany(
            User::class,
            'manager_id'
        );
    }

    public function contributorProfile(): HasOne
    {
        return $this->hasOne(
            ContributorProfile::class
        );
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(
            Enrollment::class
        );
    }

    public function quizAttempts(): HasMany
    {
        return $this->hasMany(
            QuizAttempt::class
        );
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(
            Assignment::class,
            'user_id'
        );
    }

    public function assignedTrainings(): HasMany
    {
        return $this->hasMany(
            Assignment::class,
            'manager_id'
        );
    }

    public function kpiLedgers(): HasMany
    {
        return $this->hasMany(
            KpiLedger::class
        );
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(
            Certificate::class
        );
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(
            ManagerReview::class,
            'user_id'
        );
    }

    public function reviewsGiven(): HasMany
    {
        return $this->hasMany(
            ManagerReview::class,
            'manager_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATED CONTENT
    |--------------------------------------------------------------------------
    */

    public function createdTrainingModules(): HasMany
    {
        return $this->hasMany(
            TrainingModule::class,
            'created_by'
        );
    }

    public function createdQuizzes(): HasMany
    {
        return $this->hasMany(
            Quiz::class,
            'created_by'
        );
    }

    public function createdFunTests(): HasMany
    {
        return $this->hasMany(
            FunTestQuestion::class,
            'created_by'
        );
    }
}
