<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;

/**
 * Model : User
 * Table : users
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'username', 'email', 'password', 'role', 'status', 'avatar', 'derniere_activite'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = ['password'];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // 'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'derniere_activite' => 'datetime',
        ];
    }
    /**
     * Relation : un utilisateur possede un seul profil
     */
    public function profil()
    {
        return $this->hasOne(Profil::class);
    }
    /**
     * Relation : un utilisateur possede plusieurs services
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }
    /**
     * Evenement declenche apres la creation d'un utilisateur
     * Si l'utilisateur est un freelance, cree automatiquement un profil vide
     */
    protected static function booted()
    {
        static::created(function ($user) {
            if ($user->role === 'freelance') {
                $user->profil()->create();
            }
        });
    }
}
