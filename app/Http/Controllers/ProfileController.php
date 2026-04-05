<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

/**
 * ProfileController — gestiona el perfil de l'usuari autenticat.
 *
 * Operacions:
 *  - edit:   mostra el formulari d'edició del perfil
 *  - update: actualitza les dades del perfil (inclòs l'avatar)
 */
class ProfileController extends Controller
{
    /**
     * Mostra el formulari d'edició del perfil.
     */
    public function edit()
    {
        // Obtenim l'usuari fresc de la BD (no la versió cacheada d'Auth)
        $user = User::find(Auth::id());
        return view('profile.edit', compact('user'));
    }

    /**
     * Actualitza el perfil de l'usuari autenticat.
     */
    public function update(Request $request)
    {
        // Obtenim l'usuari fresc de la BD per poder desar-lo correctament
        $user = User::find(Auth::id());

        // Validació de les dades del formulari
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'surname'      => 'nullable|string|max:255',
            'nick'         => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'email'        => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone_number' => 'nullable|string|max:20',
            'avatar'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'password'     => 'nullable|string|min:8|confirmed',
        ], [
            'nick.unique'  => 'Aquest nick ja està en ús.',
            'email.unique' => 'Aquest email ja està en ús.',
            'avatar.image' => 'L\'avatar ha de ser una imatge.',
            'avatar.max'   => 'L\'avatar no pot superar els 2MB.',
        ]);

        // ── Actualitza camps bàsics ───────────────────────────────────────
        $user->name         = $request->name;
        $user->surname      = $request->surname;
        $user->nick         = $request->nick ?: null;
        $user->phone_number = $request->phone_number;

        // Si el email ha canviat, restableix la verificació
        if ($user->email !== $request->email) {
            $user->email             = $request->email;
            $user->email_verified_at = null;
        }

        // ── Actualitza l'avatar ───────────────────────────────────────────
        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            // Elimina l'avatar antic si n'hi havia un
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            // Assegurem que existeix el directori avatars
            Storage::disk('public')->makeDirectory('avatars');

            // Desa el nou avatar a storage/app/public/avatars
            $path        = $request->file('avatar')->store('avatars', 'public');
            $user->image = $path;
        }

        // ── Actualitza la contrasenya (opcional) ─────────────────────────
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Desa tots els canvis a la BD
        $user->save();

        return redirect()->route('profile.edit')
                         ->with('success', 'Perfil actualitzat correctament!');
    }
}