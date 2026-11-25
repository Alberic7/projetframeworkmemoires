<x-layouts.app title="Inscription Étudiant">
<div class="mx-auto max-w-lg my-8 sm:my-12">
    <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
        <div class="mb-6 text-center">
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Inscription Étudiant</h2>
            <p class="mt-1 text-sm text-slate-500">Créez votre compte pour soumettre votre mémoire</p>
        </div>

        <x-alert />

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="nom" class="block text-sm font-medium text-slate-700 mb-1">Nom</label>
                    <input id="nom" type="text" name="nom" value="{{ old('nom') }}" required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none" />
                </div>
                <div>
                    <label for="prenom" class="block text-sm font-medium text-slate-700 mb-1">Prénom</label>
                    <input id="prenom" type="text" name="prenom" value="{{ old('prenom') }}" required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none" />
                </div>
            </div>

            <div>
                <label for="matricule" class="block text-sm font-medium text-slate-700 mb-1">Matricule Étudiant</label>
                <input id="matricule" type="text" name="matricule" value="{{ old('matricule') }}" placeholder="ex: 12345678"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none" />
            </div>

            <div>
                <label for="filiere_id" class="block text-sm font-medium text-slate-700 mb-1">Filière</label>
                <select id="filiere_id" name="filiere_id"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                    <option value="">Sélectionnez votre filière</option>
                    @foreach ($filieres as $filiere)
                        <option value="{{ $filiere->id }}" {{ old('filiere_id') == $filiere->id ? 'selected' : '' }}>
                            {{ $filiere->libelle }} ({{ $filiere->code ?? '' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email universitaire</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none" />
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Mot de passe</label>
                    <input id="password" type="password" name="password" required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none" />
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirmation</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none" />
                </div>
            </div>

            <button type="submit"
                class="w-full rounded-lg bg-indigo-600 py-3 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 transition mt-2">
                Créer mon compte
            </button>
        </form>
    </div>
</div>
</x-layouts.app>
