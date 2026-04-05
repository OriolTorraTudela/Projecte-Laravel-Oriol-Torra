<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

/**
 * Model Image — representa una imatge pujada per un usuari.
 *
 * Relacions:
 *  - user:     la imatge pertany a un usuari (N:1)
 *  - comments: la imatge pot tenir molts comentaris (1:N)
 *  - likes:    la imatge pot tenir molts likes (1:N)
 */
class Image extends Model
{
    use HasFactory;

    /**
     * Camps assignables massivament.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'image_path',   // Ruta relativa dins storage/app/public
        'description',  // Descripció opcional
    ];

    // ─────────────────────── RELACIONS ───────────────────────

    /**
     * La imatge pertany a un usuari (propietari).
     * Relació: N Images → 1 User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * La imatge pot tenir molts comentaris.
     * Relació: 1 Image → N Comments
     */
    public function comments(): HasMany
    {
        // Ordenats del més antic al més nou (com demana l'enunciat)
        return $this->hasMany(Comment::class)->orderBy('created_at', 'asc');
    }

    /**
     * La imatge pot tenir molts likes.
     * Relació: 1 Image → N Likes
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    // ─────────────────────── HELPERS ───────────────────────

    /**
     * Retorna la URL pública de la imatge per mostrar-la al navegador.
     */
    public function imageUrl(): string
    {
        return Storage::url($this->image_path);
    }

    /**
     * Comprova si un usuari concret ja ha donat like a aquesta imatge.
     *
     * @param  int  $userId
     * @return bool
     */
    public function likedBy(int $userId): bool
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    /**
     * Retorna el nombre total de likes de la imatge.
     */
    public function likesCount(): int
    {
        return $this->likes()->count();
    }
}
