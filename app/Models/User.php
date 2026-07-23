<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    */

    public const ROLE_KARYAWAN =
        'karyawan';

    public const ROLE_MANAGER =
        'manager';

    public const ROLE_HR =
        'hr';

    public const ROLE_SUPER_ADMIN =
        'super_admin';

    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    public const STATUS_ACTIVE =
        'active';

    public const STATUS_INACTIVE =
        'inactive';

    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'name',
        'email',
        'password',
        'nik',
        'phone',
        'avatar',
        'department_id',
        'position_id',
        'manager_id',
        'role',
        'status',
        'semester',
        'annual_leave_quota',
        'join_date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Hidden
    |--------------------------------------------------------------------------
    */

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'email_verified_at' =>
                'datetime',

            'password' =>
                'hashed',

            'join_date' =>
                'date',

            'semester' =>
                'integer',

            'annual_leave_quota' =>
                'integer',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Filament Panel Access
    |--------------------------------------------------------------------------
    |
    | HANYA Super Admin.
    |
    | Employee, Manager, dan HR tidak menggunakan Filament.
    |
    */

    public function canAccessPanel(
    Panel $panel
    ): bool {
        return
            $panel->getId() === 'admin'
            && $this->isActive()
            && $this->isSuperAdmin();
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
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
                ): string =>
                    Str::upper(
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

    public function isPortalUser(): bool
    {
        return in_array(
            $this->role,
            [
                self::ROLE_KARYAWAN,
                self::ROLE_MANAGER,
                self::ROLE_HR,
            ],
            true
        );
    }

    public function isAdmin(): bool
    {
        return
            $this->isSuperAdmin();
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function department(): BelongsTo
    {
        return $this->belongsTo(
            Department::class
        );
    }

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

    public function enrollments(): HasMany
    {
        return $this->hasMany(
            Enrollment::class
        );
    }

    public function lessonProgresses(): HasMany
    {
        return $this->hasMany(
            LessonProgress::class
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
}