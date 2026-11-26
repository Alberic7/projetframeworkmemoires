@extends('layouts.dashboard')

@section('title', 'Mon Profil')

@section('content')
<div class="space-y-6 max-w-2xl mx-auto">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Mon Profil</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Gérez vos informations personnelles.</p>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800 dark:bg-green-900/20 dark:border-green-800 dark:text-green-300">
            ✅ Profil mis à jour avec succès.
        </div>
    @endif

    {{-- Infos du compte --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
            <h2 class="font-semibold text-gray-900 dark:text-white">Informations personnelles</h2>
        </div>
        <form method="POST" action="{{ route('profile.update') }}" class="p-6 space-y-4">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nom <span class="text-red-500">*</span></label>
                    <input type="text" name="nom" value="{{ old('nom', $user->nom) }}" required
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-700 px-3.5 py-2.5 text-sm text-gray-900 dark:text-white dark:bg-gray-800 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none transition" />
                    @error('nom') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prénom <span class="text-red-500">*</span></label>
                    <input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}" required
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-700 px-3.5 py-2.5 text-sm text-gray-900 dark:text-white dark:bg-gray-800 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none transition" />
                    @error('prenom') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Adresse email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-700 px-3.5 py-2.5 text-sm text-gray-900 dark:text-white dark:bg-gray-800 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none transition" />
                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Champs non modifiables --}}
            @if ($user->matricule)
            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Matricule</label>
                <input type="text" value="{{ $user->matricule }}" disabled
                       class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 px-3.5 py-2.5 text-sm text-gray-500 dark:text-gray-400 cursor-not-allowed" />
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Rôle</label>
                @php
                    $roleLabel = match($user->role instanceof \BackedEnum ? $user->role->value : $user->role) {
                        'etudiant'         => '🎓 Étudiant',
                        'responsable'      => '📚 Responsable bibliothèque',
                        'chef_departement' => '🏫 Chef de département',
                        'admin'            => '⚙️ Administrateur',
                        default            => $user->role,
                    };
                @endphp
                <input type="text" value="{{ $roleLabel }}" disabled
                       class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 px-3.5 py-2.5 text-sm text-gray-500 dark:text-gray-400 cursor-not-allowed" />
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit"
                        class="rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-semibold text-white hover:bg-brand-600 transition">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>

    {{-- Supprimer le compte --}}
    <div class="rounded-2xl border border-red-200 dark:border-red-800 bg-white dark:bg-gray-900 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-red-100 dark:border-red-800">
            <h2 class="font-semibold text-red-700 dark:text-red-400">Zone dangereuse</h2>
        </div>
        <div class="p-6">
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                La suppression de votre compte est irréversible. Toutes vos données seront définitivement effacées.
            </p>
            <form method="POST" action="{{ route('profile.destroy') }}"
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="rounded-lg border border-red-300 dark:border-red-700 px-5 py-2.5 text-sm font-medium text-red-700 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                    Supprimer mon compte
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
