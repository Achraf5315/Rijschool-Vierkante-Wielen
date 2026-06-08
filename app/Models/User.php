<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'rolename',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
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
        ];
    }

    /**
     * Mag deze gebruiker lesrijpakketten beheren (toevoegen/bewerken/verwijderen)?
     * Dit geldt voor zowel de administrator als de instructeur.
     */
    public function canManagePackages(): bool
    {
        return in_array(strtolower($this->rolename ?? ''), ['admin', 'instructor'], true);
    }

    /**
     * Mag deze gebruiker auto's beheren?
     * Dit geldt voor zowel de administrator als de instructeur.
     */
    public function canManageAutos(): bool
    {
        return in_array(strtolower($this->rolename ?? ''), ['admin', 'instructor'], true);
    }

    /**
     * Mag deze gebruiker instructeurs beheren?
     * Dit geldt ALLEEN voor de administrator.
     */
    public function canManageInstructeurs(): bool
    {
        return strtolower($this->rolename ?? '') === 'admin';
    }

    public function contact(): HasOne
    {
        return $this->hasOne(Contact::class, 'UserId');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'UserId');
    }
}
