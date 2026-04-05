<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Comment — representa un comentari fet a una imatge.
 *
 * Relacions:
 *  - user:  el comentari pertany a un usuari (N:1)
 *  - image: el comentari pertany a una imatge (N:1)
 */
class Comment extends Model
{
    use HasFactory;

    /**
     * Camps assignables massivament.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',   // Qui ha fet el comentari
        'image_id',  // A quina imatge pertany
        'content',   // Text del comentari
    ];

    // ─────────────────────── RELACIONS ───────────────────────

    /**
     * El comentari pertany a un usuari.
     * Relació: N Comments → 1 User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * El comentari pertany a una imatge.
     * Relació: N Comments → 1 Image
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }

    // ─────────────────────── HELPERS ───────────────────────

    /**
     * Comprova si un usuari és el propietari d'aquest comentari.
     *
     * @param  int  $userId
     * @return bool
     */
    public function isOwnedBy(int $userId): bool
    {
        return $this->user_id === $userId;
    }
}
