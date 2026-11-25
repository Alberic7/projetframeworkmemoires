<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('filiere')->orderBy('nom')->get();
        $filieres = Filiere::orderBy('libelle')->get();

        return view('admin.users', compact('users', 'filieres'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->regles());

        User::forceCreate([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'matricule' => $data['matricule'] ?? null,
            'role' => $data['role'],
            'filiere_id' => $data['filiere_id'] ?? null,
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé.');
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate($this->regles($user));

        if ($user->is($request->user()) && $data['role'] !== Role::Admin->value) {
            return back()->with('error', 'Vous ne pouvez pas retirer votre propre rôle administrateur.');
        }

        $user->forceFill([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'matricule' => $data['matricule'] ?? null,
            'role' => $data['role'],
            'filiere_id' => $data['filiere_id'] ?? null,
        ]);

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur modifié.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        try {
            $user->delete();
        } catch (QueryException $e) {
            return back()->with('error', "Suppression impossible : cet utilisateur est lié à des mémoires ou des validations.");
        }

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé.');
    }

    private function regles(?User $user = null): array
    {
        $etudiant = Role::Etudiant->value;
        $chef = Role::ChefDepartement->value;

        return [
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'matricule' => ['nullable', "required_if:role,{$etudiant}", 'string', 'max:50',
                Rule::unique('users', 'matricule')->ignore($user?->id)],
            'role' => ['required', Rule::enum(Role::class)],
            'filiere_id' => ['nullable', "required_if:role,{$etudiant},{$chef}", 'exists:filieres,id'],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8'],
        ];
    }
}