<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * LikeController — gestiona el sistema de likes (m'agrada).
 *
 * Funcionalitat de toggle (like/dislike):
 *  - Si l'usuari JA ha donat like → elimina el like (dislike)
 *  - Si l'usuari NO ha donat like → crea un nou like
 *
 * Respon amb JSON per permetre actualització reactiva sense recarregar la pàgina.
 */
class LikeController extends Controller
{
    /**
     * Commuta el like d'un usuari a una imatge (toggle).
     *
     * Retorna JSON amb:
     *  - liked:      boolean — si ara l'usuari ha donat like (true) o no (false)
     *  - likesCount: int — nombre total de likes actualitzat
     *
     * Endpoint: POST /images/{image}/like
     */
    public function toggle(Request $request, Image $image)
    {
        $userId = Auth::id();

        // Busca si ja existeix un like d'aquest usuari a aquesta imatge
        $existingLike = Like::where('user_id', $userId)
                            ->where('image_id', $image->id)
                            ->first();

        if ($existingLike) {
            // L'usuari ja havia donat like → elimina (dislike)
            $existingLike->delete();
            $liked = false;
        } else {
            // L'usuari no havia donat like → crea un nou like
            Like::create([
                'user_id'  => $userId,
                'image_id' => $image->id,
            ]);
            $liked = true;
        }

        // Retorna la resposta JSON per actualitzar la UI sense recarregar
        return response()->json([
            'liked'      => $liked,
            'likesCount' => $image->likes()->count(),
        ]);
    }
}
