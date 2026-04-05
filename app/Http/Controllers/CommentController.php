<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * CommentController — gestiona el CRUD de comentaris.
 *
 * Regles de negoci:
 *  - Qualsevol usuari autenticat pot AFEGIR comentaris a qualsevol imatge.
 *  - Només el PROPIETARI del comentari pot EDITAR o ELIMINAR el seu comentari.
 */
class CommentController extends Controller
{
    /**
     * Emmagatzema un nou comentari a la BD.
     * El comentari s'associa a la imatge i a l'usuari autenticat.
     */
    public function store(Request $request, Image $image)
    {
        // Validació del contingut del comentari
        $request->validate([
            'content' => 'required|string|max:500|min:1',
        ], [
            'content.required' => 'El comentari no pot estar buit.',
            'content.max'      => 'El comentari no pot superar els 500 caràcters.',
        ]);

        // Crea el comentari associat a la imatge i l'usuari autenticat
        Comment::create([
            'user_id'  => Auth::id(),
            'image_id' => $image->id,
            'content'  => $request->content,
        ]);

        return redirect()->route('images.show', $image)
                         ->with('success', 'Comentari afegit correctament!');
    }

    /**
     * Formulari per editar un comentari existent.
     * Comprova que l'usuari és el propietari del comentari.
     */
    public function edit(Comment $comment)
    {
        // Autorització: només el propietari pot editar
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'No tens permís per editar aquest comentari.');
        }

        // Carrega la imatge relacionada per tornar al detall
        $comment->load('image');

        return view('images.edit_comment', compact('comment'));
    }

    /**
     * Actualitza el contingut d'un comentari existent.
     * Comprova que l'usuari és el propietari del comentari.
     */
    public function update(Request $request, Comment $comment)
    {
        // Autorització: només el propietari pot actualitzar
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'No tens permís per editar aquest comentari.');
        }

        // Validació del nou contingut
        $request->validate([
            'content' => 'required|string|max:500|min:1',
        ]);

        // Actualitza el contingut
        $comment->update(['content' => $request->content]);

        return redirect()->route('images.show', $comment->image_id)
                         ->with('success', 'Comentari actualitzat correctament!');
    }

    /**
     * Elimina un comentari de la BD.
     * Comprova que l'usuari és el propietari del comentari.
     */
    public function destroy(Comment $comment)
    {
        // Guardem l'ID de la imatge abans d'eliminar (per la redirecció)
        $imageId = $comment->image_id;

        // Autorització: només el propietari pot eliminar
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'No tens permís per eliminar aquest comentari.');
        }

        $comment->delete();

        return redirect()->route('images.show', $imageId)
                         ->with('success', 'Comentari eliminat correctament!');
    }
}
