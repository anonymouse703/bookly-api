<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use DateTimeInterface;
use App\Enums\User\Role;
use Illuminate\Support\Str;
use App\Cache\User\UserById;
use App\Cache\User\UserByEmail;
use App\Observers\UserObserver;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Contracts\Timezone;
use App\Models\Contracts\Cacheable;
use Laravel\Sanctum\NewAccessToken;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[ObservedBy([UserObserver::class])]
class User extends Authenticatable implements Cacheable, FilamentUser, Timezone
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'phone',
        'mobile',
        'address',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
            'is_active' => 'boolean'
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Filament config.
     */
    public function getFilamentName(): string
    {
        return "{$this->name}";
    }

    public function canAccessPanel(Panel $panel): bool
    {
        try {
            if (! $this->is_active) {
                return false;
            }

            if ($panel->getId() === 'admin') {
                return in_array($this->role, [
                    Role::Admin,
                    Role::Provider,
                ]);
            }
        } catch (\Exception $exception) {
        }
        return true;
    }
    

    /**
     * Delete all the user tokens.
     */
    public function clearTokens(): void
    {
        $tokens = $this->tokens;
        $tokens->each->delete();
    }

    /**
     * Flushes the model cache.
     */
    public function clearCache(): void
    {
        (new UserById($this->id))->invalidate();
        if ($this->email) (new UserByEmail($this->email))->invalidate();
    }

    /**
     * Other Methods.
     */
    public function getTimezone(): string
    {
        return 'Asia/Manila';
    }
}
