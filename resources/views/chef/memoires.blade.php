@extends('layouts.dashboard')

@section('title', 'Mémoires de ma filière')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
            Mémoires — {{ $filiere->libelle }}
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Tous les mémoires validés de votre filière.
        </p>
    </div>

    @if ($memoires->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 p-16 text-center">
            <p class="text-base font-medium text-gray-500 dark:text-gray-400">Aucun mémoire validé dans cette filière pour le moment.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($memoires as $memoire)
                <div class="flex flex-col rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5 shadow-xs hover:shadow-md transition">
                    <div class="flex items-center justify-between gap-2 text-xs font-semibold text-indigo-600 dark:text-indigo-400 mb-2">
                        <span>{{ $memoire->anneeAcademique?->libelle ?? '—' }}</span>
                        @if ($memoire->acces_restreint)
                            <span class="rounded bg-orange-100 px-2 py-0.5 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">Accès restreint</span>
                        @endif
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white line-clamp-2 mb-2">{{ $memoire->titre }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-3 mb-4 leading-relaxed">{{ $memoire->resume }}</p>
                    <div class="mt-auto border-t border-gray-100 dark:border-gray-800 pt-3 flex items-center justify-between text-xs text-gray-600 dark:text-gray-400">
                        <span>{{ $memoire->etudiant?->prenom }} {{ $memoire->etudiant?->nom }}</span>
                        <a href="{{ route('catalogue.show', $memoire) }}" class="font-semibold text-brand-600 hover:underline dark:text-brand-400">Détails →</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
