@extends('layouts.dashboard')

@section('title', 'Déposer un mémoire')

@section('content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Déposer un mémoire</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Remplissez ce formulaire pour soumettre votre mémoire à la bibliothèque.</p>
        </div>
        <a href="{{ route('mes-memoires') }}"
           class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition">
            ← Mes mémoires
        </a>
    </div>

    {{-- Alerte succès/erreur --}}
    @if (session('success'))
        <div class="rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800 dark:bg-green-900/20 dark:border-green-800 dark:text-green-300">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800 dark:bg-red-900/20 dark:border-red-800 dark:text-red-300">
            <strong>Veuillez corriger les erreurs suivantes :</strong>
            <ul class="mt-2 list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulaire --}}
    <form method="POST" action="{{ route('memoires.store') }}" enctype="multipart/form-data"
          class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xs dark:border-gray-800 dark:bg-gray-900 space-y-6">
        @csrf

        {{-- Titre --}}
        <div>
            <label for="titre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Titre du mémoire <span class="text-red-500">*</span>
            </label>
            <input type="text" id="titre" name="titre" value="{{ old('titre') }}" required
                   placeholder="Ex : Conception d'un système de gestion de bibliothèque universitaire"
                   class="w-full rounded-lg border @error('titre') border-red-400 @else border-gray-300 @enderror px-3.5 py-2.5 text-sm text-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-white placeholder-gray-400 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none transition" />
            @error('titre')
                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Résumé --}}
        <div>
            <label for="resume" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Résumé <span class="text-red-500">*</span>
            </label>
            <textarea id="resume" name="resume" rows="6" required
                      placeholder="Rédigez ici un résumé complet de votre mémoire (au moins 20 caractères)..."
                      class="w-full rounded-lg border @error('resume') border-red-400 @else border-gray-300 @enderror px-3.5 py-2.5 text-sm text-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-white placeholder-gray-400 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none transition resize-y">{{ old('resume') }}</textarea>
            @error('resume')
                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Mots-clés + Encadreur sur 2 colonnes --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="mots_cles" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Mots-clés <span class="text-red-500">*</span>
                </label>
                <input type="text" id="mots_cles" name="mots_cles" value="{{ old('mots_cles') }}" required
                       placeholder="Ex : laravel, base de données, web, PHP"
                       class="w-full rounded-lg border @error('mots_cles') border-red-400 @else border-gray-300 @enderror px-3.5 py-2.5 text-sm text-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-white placeholder-gray-400 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none transition" />
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Séparez les mots-clés par des virgules.</p>
                @error('mots_cles')
                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="encadreur" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Nom de l'encadreur <span class="text-red-500">*</span>
                </label>
                <input type="text" id="encadreur" name="encadreur" value="{{ old('encadreur') }}" required
                       placeholder="Ex : Dr. Jean Dupont"
                       class="w-full rounded-lg border @error('encadreur') border-red-400 @else border-gray-300 @enderror px-3.5 py-2.5 text-sm text-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-white placeholder-gray-400 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none transition" />
                @error('encadreur')
                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Filière + Année académique --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="filiere_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Filière <span class="text-red-500">*</span>
                </label>
                <select id="filiere_id" name="filiere_id" required
                        class="w-full rounded-lg border @error('filiere_id') border-red-400 @else border-gray-300 @enderror px-3.5 py-2.5 text-sm text-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none transition">
                    <option value="">— Sélectionnez votre filière —</option>
                    @foreach ($filieres as $filiere)
                        <option value="{{ $filiere->id }}" {{ old('filiere_id') == $filiere->id ? 'selected' : '' }}>
                            {{ $filiere->libelle }}
                        </option>
                    @endforeach
                </select>
                @error('filiere_id')
                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="annee_academique_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Année académique <span class="text-red-500">*</span>
                </label>
                <select id="annee_academique_id" name="annee_academique_id" required
                        class="w-full rounded-lg border @error('annee_academique_id') border-red-400 @else border-gray-300 @enderror px-3.5 py-2.5 text-sm text-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none transition">
                    <option value="">— Sélectionnez l'année —</option>
                    @foreach ($annees as $annee)
                        <option value="{{ $annee->id }}" {{ old('annee_academique_id') == $annee->id ? 'selected' : '' }}>
                            {{ $annee->libelle }}
                        </option>
                    @endforeach
                </select>
                @error('annee_academique_id')
                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Fichier PDF --}}
        <div>
            <label for="fichier_pdf" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Fichier PDF du mémoire <span class="text-red-500">*</span>
            </label>
            <div class="flex items-center justify-center w-full">
                <label for="fichier_pdf"
                       class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed @error('fichier_pdf') border-red-400 @else border-gray-300 dark:border-gray-600 @enderror rounded-xl cursor-pointer bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <div class="flex flex-col items-center justify-center pt-4 pb-5">
                        <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            <span class="font-semibold text-brand-600 dark:text-brand-400">Cliquez pour choisir</span> ou glissez-déposez
                        </p>
                        <p class="text-xs text-gray-400 mt-1">PDF uniquement — max. 25 Mo</p>
                        <p class="text-xs text-brand-600 dark:text-brand-400 mt-1 font-medium" id="fichier-nom">Aucun fichier sélectionné</p>
                    </div>
                    <input id="fichier_pdf" name="fichier_pdf" type="file" accept=".pdf" class="hidden"
                           onchange="document.getElementById('fichier-nom').textContent = this.files[0]?.name || 'Aucun fichier sélectionné'" />
                </label>
            </div>
            @error('fichier_pdf')
                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Notice --}}
        <div class="rounded-xl border border-blue-100 bg-blue-50 dark:bg-blue-900/20 dark:border-blue-800 p-4 text-sm text-blue-800 dark:text-blue-300">
            <strong>ℹ️ Après soumission</strong>, votre mémoire sera examiné par la bibliothèque avant d'être publié dans le catalogue. Vous serez notifié par email de la décision.
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3 border-t border-gray-200 dark:border-gray-700 pt-4">
            <a href="{{ route('mes-memoires') }}"
               class="rounded-lg border border-gray-300 dark:border-gray-700 px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                Annuler
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-brand-600 transition">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                Soumettre le mémoire
            </button>
        </div>
    </form>
</div>
@endsection
