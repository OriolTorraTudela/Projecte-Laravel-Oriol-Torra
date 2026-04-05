<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

/**
 * RegisteredUserController — gestiona el registre de nous usuaris.
 *
 * Estén el controlador per defecte de Laravel Breeze per incloure
 * els camps addicionals: surname, nick, phone_number.
 */
class RegisteredUserController extends Controller
{
    /**
     * Mostra el formulari de registre.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Processa el formulari de registre i crea el nou usuari.
     *
     * Validacions addicionals respecte al Breeze per defecte:
     *  - surname:      opcional
     *  - nick:         opcional però únic
     *  - phone_number: opcional, format numèric
     */
    public function store(Request $request): RedirectResponse
    {
        // Validació de totes les dades del formulari de registre
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'surname'      => ['nullable', 'string', 'max:255'],
            'nick'         => ['nullable', 'string', 'max:50', 'unique:users,nick'],
            'email'        => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone_number' => ['nullable', 'string', 'regex:/^[0-9+\-\s()]*$/', 'max:20'],
            'password'     => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'nick.unique'  => 'Aquest nick ja està en ús.',
            'email.unique' => 'Aquest email ja està registrat.',
        ]);

        // Crea el nou usuari amb tots els camps
        $user = User::create([
            'name'         => $request->name,
            'surname'      => $request->surname,
            'nick'         => $request->nick,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
            'password'     => Hash::make($request->password),
            'role'         => 'user', // Rol per defecte
        ]);

        // Dispara l'event de registre (per a notificacions, etc.)
        event(new Registered($user));

        // Inicia sessió automàticament després del registre
        Auth::login($user);

        return redirect(route('home'));
    }
}
