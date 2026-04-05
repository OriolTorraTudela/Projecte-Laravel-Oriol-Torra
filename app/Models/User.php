<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model User — representa un usuari de l'aplicació.
 *
 * Relacions:
 *  - images:   l'usuari pot tenir moltes imatges (1:N)
 *  - comments: l'usuari pot fer molts comentaris (1:N)
 *  - likes:    l'usuari pot donar like a moltes imatges (1:N)
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Camps que es poden assignar massivament (mass assignment).
     * Tots els camps del formulari que s'han de desar a la BD.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'surname',      // Cognom
        'nick',         // Nom d'usuari únic
        'email',
        'password',
        'image',        // Ruta de l'avatar
        'phone_number', // Telèfon
        'role',         // Rol: 'user' o 'admin'
    ];

    /**
     * Camps que mai s'han de serialitzar (ocultar en JSON/arrays).
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Càsting de tipus per als camps especials.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ─────────────────────── RELACIONS ───────────────────────

    /**
     * Un usuari pot tenir moltes imatges pujades.
     * Relació: 1 User → N Images
     */
    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    /**
     * Un usuari pot fer molts comentaris.
     * Relació: 1 User → N Comments
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Un usuari pot donar like a moltes imatges.
     * Relació: 1 User → N Likes
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    // ─────────────────────── HELPERS ───────────────────────

    /**
     * Retorna la URL de l'avatar de l'usuari.
     * Si no té avatar, retorna un avatar per defecte.
     */
    public function avatarUrl(): string
    {
        if ($this->image && \Storage::disk('public')->exists($this->image)) {
            return \Storage::url($this->image);
        }
        // Avatar per defecte basat en les inicials
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=6366f1&color=fff';
    }

    /**
     * Retorna el nom d'usuari a mostrar (nick o name si no té nick).
     */
    public function displayName(): string
    {
        return $this->nick ?? $this->name;
    }
}
