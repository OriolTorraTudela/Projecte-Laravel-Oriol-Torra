<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * ImageController — gestiona el CRUD complet d'imatges.
 *
 * Operacions:
 *  - index:   llistat de totes les imatges (home)
 *  - create:  formulari per pujar una nova imatge
 *  - store:   desa la nova imatge a BD i storage
 *  - show:    detall d'una imatge amb comentaris i likes
 *  - edit:    formulari per editar una imatge (only owner)
 *  - update:  actualitza la imatge a BD i storage (only owner)
 *  - destroy: elimina la imatge de BD i storage (only owner)
 */
class ImageController extends Controller
{
    /**
     * Formulari per crear/pujar una nova imatge.
     * Requereix autenticació (middleware 'auth' a les rutes).
     */
    public function create()
    {
        return view('images.create');
    }

    /**
     * Emmagatzema una nova imatge a la BD i al storage.
     *
     * Validacions:
     *  - image: obligatori, ha de ser una imatge, màx 5MB
     *  - description: opcional, text
     */
    public function store(Request $request)
    {
        // Validació de les dades del formulari
        $request->validate([
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'description' => 'nullable|string|max:1000',
        ], [
            'image.required' => 'Has de seleccionar una imatge.',
            'image.image'    => 'El fitxer ha de ser una imatge.',
            'image.max'      => 'La imatge no pot superar els 5MB.',
        ]);

        // Desa la imatge a storage/app/public/images amb nom únic
        $path = $request->file('image')->store('images', 'public');

        // Crea el registre a la BD
        Image::create([
            'user_id'     => Auth::id(),
            'image_path'  => $path,
            'description' => $request->description,
        ]);

        return redirect()->route('home')
                         ->with('success', 'Imatge pujada correctament!');
    }

    /**
     * Mostra el detall d'una imatge.
     *
     * Càrrega ansiosa de:
     *  - user:             propietari de la imatge
     *  - comments.user:    comentaris amb el seu usuari autor
     *  - likes:            likes per comptador i verificació
     */
    public function show(Image $image)
    {
        // Carrega les relacions necessàries per al detall
        $image->load([
            'user',
            'comments.user', // Comentaris ja estan ordenats al model (asc)
            'likes',
        ]);

        // Comprova si l'usuari autenticat ja ha donat like
        $userHasLiked = Auth::check()
            ? $image->likedBy(Auth::id())
            : false;

        return view('images.show', compact('image', 'userHasLiked'));
    }

    /**
     * Formulari per editar una imatge existent.
     * Comprova que l'usuari és el propietari.
     */
    public function edit(Image $image)
    {
        // Autorització: només el propietari pot editar
        if ($image->user_id !== Auth::id()) {
            abort(403, 'No tens permís per editar aquesta imatge.');
        }

        return view('images.edit', compact('image'));
    }

    /**
     * Actualitza la imatge a la BD (i opcionalment al storage si es puja nova).
     * Comprova que l'usuari és el propietari.
     */
    public function update(Request $request, Image $image)
    {
        // Autorització: només el propietari pot actualitzar
        if ($image->user_id !== Auth::id()) {
            abort(403, 'No tens permís per editar aquesta imatge.');
        }

        // Validació: la imatge nova és opcional en l'edició
        $request->validate([
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'description' => 'nullable|string|max:1000',
        ]);

        // Si s'ha pujat una nova imatge, reemplaça l'anterior
        if ($request->hasFile('image')) {
            // Elimina la imatge antiga del storage
            Storage::disk('public')->delete($image->image_path);

            // Desa la nova imatge
            $path = $request->file('image')->store('images', 'public');
            $image->image_path = $path;
        }

        // Actualitza la descripció
        $image->description = $request->description;
        $image->save();

        return redirect()->route('images.show', $image)
                         ->with('success', 'Imatge actualitzada correctament!');
    }

    /**
     * Elimina una imatge de la BD i del storage.
     * Comprova que l'usuari és el propietari.
     */
    public function destroy(Image $image)
    {
        // Autorització: només el propietari pot eliminar
        if ($image->user_id !== Auth::id()) {
            abort(403, 'No tens permís per eliminar aquesta imatge.');
        }

        // Elimina el fitxer físic del storage
        Storage::disk('public')->delete($image->image_path);

        // Elimina el registre de la BD (cascade elimina comentaris i likes)
        $image->delete();

        return redirect()->route('home')
                         ->with('success', 'Imatge eliminada correctament!');
    }
}
