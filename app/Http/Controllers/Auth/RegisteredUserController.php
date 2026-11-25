<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        $filieres = Filiere::orderBy('libelle')->get();

        return view('auth.register', compact('filieres'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'matricule' => ['required', 'string', 'max:50', 'unique:users,matricule'],
            'filiere_id' => ['required', 'exists:filieres,id'],
            'password' => ['required', Password::defaults()],
        ]);

        // L'inscription publique crée toujours un étudiant : le rôle ne vient jamais du formulaire
        $user = User::forceCreate([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'matricule' => $data['matricule'],
            'filiere_id' => $data['filiere_id'],
            'email' => null,
            'role' => Role::Etudiant->value,
            'password' => Hash::make($data['password']),
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}