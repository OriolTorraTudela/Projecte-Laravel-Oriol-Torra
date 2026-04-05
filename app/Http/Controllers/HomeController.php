<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

/**
 * HomeController — gestiona la pàgina principal de l'aplicació.
 *
 * Mostra totes les imatges pujades per tots els usuaris,
 * ordenades de la més nova a la més antiga, amb paginació.
 */
class HomeController extends Controller
{
    /**
     * Mostra la pàgina principal amb el llistat de totes les imatges.
     *
     * Utilitza eager loading (càrrega ansiosa) per evitar el problema N+1:
     * - with('user'):     carrega l'usuari propietari de cada imatge
     * - with('likes'):    carrega els likes per mostrar el comptador
     * - with('comments'): carrega els comentaris per mostrar el comptador
     *
     * paginate(9): mostra 9 imatges per pàgina (3 columnes × 3 files)
     */
    public function index()
    {
        // Consulta optimitzada amb càrrega ansiosa de totes les relacions necessàries
        $images = Image::with(['user', 'likes', 'comments'])
                       ->orderBy('created_at', 'desc')
                       ->paginate(9);

        return view('home.index', compact('images'));
    }
}
