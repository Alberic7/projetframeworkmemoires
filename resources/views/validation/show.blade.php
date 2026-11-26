@extends('layouts.dashboard')

@section('title', 'Examen du mémoire')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <div class="flex items-center gap-2">
        <a href="{{ route('validation.index') }}" class="text-sm text-gray-500 hover:text-brand-600 dark:text-gray-400">← Retour à la liste</a>
    </div>

    @if (session('success'))
        <div class="rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">✅ {{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">❌ {{ session('error') }}</div>
    @endif

    {{-- Info mémoire --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-xs p-6 space-y-4">
        <div class="flex flex-wrap items-center gap-2">
            <span class="rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">{{ $memoire->filiere?->libelle }}</span>
            <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs text-gray-600">{{ $memoire->anneeAcademique?->libelle }}</span>
            <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-800">🔍 En vérification</span>
        </div>
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ $memoire->titre }}</h1>
        <div class="grid grid-cols-2 gap-4 text-sm text-gray-600 dark:text-gray-400">
            <div>Étudiant : <strong class="text-gray-900 dark:text-white">{{ $memoire->etudiant?->prenom }} {{ $memoire->etudiant?->nom }}</strong></div>
            <div>Encadreur : <strong class="text-gray-900 dark:text-white">{{ $memoire->encadreur }}</strong></div>
            <div>Soumis le : <strong class="text-gray-900 dark:text-white">{{ $memoire->created_at->format('d/m/Y à H:i') }}</strong></div>
            @if ($memoire->score_similarite !== null)
                <div>Score de similarité : <strong class="{{ $memoire->score_similarite > 30 ? 'text-red-600' : 'text-green-600' }}">{{ $memoire->score_similarite }}%</strong></div>
            @endif
        </div>
        <div>
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Résumé</h3>
            <p class="text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 rounded-xl p-4 leading-relaxed">{{ $memoire->resume }}</p>
        </div>
        @if ($memoire->fichier_pdf)
            <div class="pt-2">
                <a href="{{ route('memoires.pdf', $memoire) }}" target="_blank"
                   class="inline-flex items-center gap-2 rounded-lg border border-gray-300 dark:border-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Ouvrir le PDF
                </a>
            </div>
        @endif
    </div>

    {{-- Actions : Valider ou Rejeter --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        {{-- Valider --}}
        <div class="rounded-2xl border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/20 p-5 space-y-4">
            <h2 class="font-semibold text-green-900 dark:text-green-300 flex items-center gap-2">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Valider le mémoire
            </h2>
            <form method="POST" action="{{ route('validation.valider', $memoire) }}">
                @csrf
                <div class="flex flex-col gap-3">
                    <label class="flex items-center gap-2 text-sm text-green-800 dark:text-green-300">
                        <input type="checkbox" name="telechargement_autorise" value="1" checked class="rounded border-gray-300">
                        Autoriser le téléchargement du PDF
                    </label>
                    <label class="flex items-center gap-2 text-sm text-green-800 dark:text-green-300">
                        <input type="checkbox" name="acces_restreint" value="1" class="rounded border-gray-300">
                        Restreindre l'accès (filière + auteur uniquement)
                    </label>
                    <div>
                        <label class="block text-xs font-medium text-green-800 dark:text-green-300 mb-1">Date d'embargo (optionnel)</label>
                        <input type="date" name="date_embargo"
                               class="w-full rounded-lg border border-green-300 dark:border-green-700 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-1 focus:ring-green-500 outline-none" />
                        <p class="mt-1 text-xs text-green-700 dark:text-green-400">Si renseigné, le mémoire sera visible uniquement après cette date.</p>
                    </div>
                    <button type="submit"
                            class="w-full rounded-lg bg-green-600 py-2.5 text-sm font-semibold text-white hover:bg-green-700 transition">
                        ✅ Confirmer la validation
                    </button>
                </div>
            </form>
        </div>

        {{-- Rejeter --}}
        <div class="rounded-2xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20 p-5 space-y-4">
            <h2 class="font-semibold text-red-900 dark:text-red-300 flex items-center gap-2">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Rejeter le mémoire
            </h2>
            <form method="POST" action="{{ route('validation.rejeter', $memoire) }}">
                @csrf
                <div class="flex flex-col gap-3">
                    <div>
                        <label for="motif" class="block text-xs font-medium text-red-800 dark:text-red-300 mb-1">
                            Motif du rejet <span class="text-red-500">*</span>
                        </label>
                        <textarea id="motif" name="motif" rows="5" required
                                  placeholder="Expliquez clairement à l'étudiant pourquoi son mémoire est rejeté et ce qu'il doit corriger..."
                                  class="w-full rounded-lg border border-red-300 dark:border-red-700 px-3 py-2 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-1 focus:ring-red-500 outline-none resize-y">{{ old('motif') }}</textarea>
                        @error('motif')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                            onclick="return confirm('Confirmer le rejet ? L\'étudiant sera notifié.')"
                            class="w-full rounded-lg bg-red-600 py-2.5 text-sm font-semibold text-white hover:bg-red-700 transition">
                        ❌ Confirmer le rejet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
