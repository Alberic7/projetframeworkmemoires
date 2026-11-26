@extends('layouts.dashboard')

@section('title', 'Mes mémoires')

@section('content')
<div class="space-y-6">
    {{-- En-tête --}}
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Mes mémoires</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Suivez le statut de vos travaux soumis à la bibliothèque.</p>
        </div>
        <a href="{{ route('memoires.create') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-brand-600 transition">
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Déposer un mémoire
        </a>
    </div>

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800 dark:bg-green-900/20 dark:border-green-800 dark:text-green-300">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Table ou état vide --}}
    @if ($memoires->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 p-16 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">Aucun mémoire soumis</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Vous n'avez encore déposé aucun mémoire. Commencez maintenant !</p>
            <a href="{{ route('memoires.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-600 transition">
                Déposer mon premier mémoire
            </a>
        </div>
    @else
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800 text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Titre</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Filière</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Année</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Statut</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Déposé le</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($memoires as $memoire)
                            @php
                                $badgeClass = match($memoire->statut) {
                                    'valide'         => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                    'rejete'         => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                    'en_verification'=> 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                    default          => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400',
                                };
                                $badgeLabel = match($memoire->statut) {
                                    'valide'         => '✅ Validé',
                                    'rejete'         => '❌ Rejeté',
                                    'en_verification'=> '🔍 En vérification',
                                    default          => '⏳ Soumis',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                <td class="px-5 py-4">
                                    <p class="font-medium text-gray-900 dark:text-white line-clamp-2 max-w-xs">{{ $memoire->titre }}</p>
                                    @if ($memoire->statut === 'rejete' && $memoire->motif_rejet)
                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">Motif : {{ Str::limit($memoire->motif_rejet, 80) }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-gray-600 dark:text-gray-400">{{ $memoire->filiere?->libelle ?? '—' }}</td>
                                <td class="px-5 py-4 text-gray-600 dark:text-gray-400">{{ $memoire->anneeAcademique?->libelle ?? '—' }}</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $badgeClass }}">
                                        {{ $badgeLabel }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-gray-500 dark:text-gray-400">
                                    {{ $memoire->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-5 py-4">
                                    @if ($memoire->statut === 'valide')
                                        <a href="{{ route('catalogue.show', $memoire) }}"
                                           class="text-xs font-medium text-brand-600 hover:underline dark:text-brand-400">
                                            Voir dans le catalogue →
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400">En attente</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
