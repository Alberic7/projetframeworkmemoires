@extends('layouts.dashboard')

@section('title', 'Validation des mémoires')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Validation des mémoires</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Mémoires en attente de vérification ou soumis récemment.</p>
    </div>

    @if (session('success'))
        <div class="rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">✅ {{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">❌ {{ session('error') }}</div>
    @endif

    @if ($memoires->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 p-16 text-center">
            <p class="text-base font-medium text-gray-500 dark:text-gray-400">🎉 Aucun mémoire en attente de validation !</p>
        </div>
    @else
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-xs overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800 text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Titre</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Étudiant</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Filière</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Statut</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Soumis le</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach ($memoires as $memoire)
                        @php
                            $badgeClass = $memoire->statut === 'en_verification'
                                ? 'bg-blue-100 text-blue-800'
                                : 'bg-orange-100 text-orange-800';
                            $badgeLabel = $memoire->statut === 'en_verification'
                                ? '🔍 En vérification'
                                : '⏳ Soumis';
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                            <td class="px-5 py-4 font-medium text-gray-900 dark:text-white max-w-xs">
                                <p class="line-clamp-2">{{ $memoire->titre }}</p>
                            </td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-400">
                                {{ $memoire->etudiant?->prenom }} {{ $memoire->etudiant?->nom }}
                            </td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-400">{{ $memoire->filiere?->libelle ?? '—' }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $badgeClass }}">
                                    {{ $badgeLabel }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-gray-500 dark:text-gray-400">{{ $memoire->created_at->format('d/m/Y') }}</td>
                            <td class="px-5 py-4">
                                <a href="{{ route('validation.show', $memoire) }}"
                                   class="inline-flex items-center gap-1 rounded-lg bg-brand-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-brand-600 transition">
                                    Examiner →
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
