<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Like — representa un "m'agrada" d'un usuari a una imatge.
 * Un usuari només pot donar un like per imatge (restricció única a la BD).
 *
 * Relacions:
 *  - user:  el like pertany a un usuari (N:1)
 *  - image: el like pertany a una imatge (N:1)
 */
class Like extends Model
{
    use HasFactory;

    /**
     * Camps assignables massivament.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',  // Qui ha fet el like
        'image_id', // A quina imatge
    ];

    // ─────────────────────── RELACIONS ───────────────────────

    /**
     * El like pertany a un usuari.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * El like pertany a una imatge.
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}
