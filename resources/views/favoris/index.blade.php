@extends('layouts.dashboard')

@section('title', 'Mes Favoris')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Mes Favoris</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Les mémoires que vous avez mis en favoris.</p>
    </div>

    @if (session('success'))
        <div class="rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800 dark:bg-green-900/20 dark:border-green-800 dark:text-green-300">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if ($memoires->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 p-16 text-center">
            <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <p class="text-base font-medium text-gray-500 dark:text-gray-400 mb-2">Aucun favori pour le moment.</p>
            <a href="{{ route('catalogue.index') }}" class="text-sm text-brand-600 hover:underline dark:text-brand-400">Parcourir le catalogue →</a>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($memoires as $memoire)
                <div class="flex flex-col rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5 shadow-xs hover:shadow-md transition">
                    <div class="flex items-center justify-between gap-2 text-xs font-semibold text-indigo-600 dark:text-indigo-400 mb-2">
                        <span>{{ $memoire->filiere?->libelle ?? '—' }}</span>
                        <span class="rounded bg-gray-100 dark:bg-gray-800 px-2 py-0.5 text-gray-600 dark:text-gray-400">{{ $memoire->anneeAcademique?->libelle ?? '' }}</span>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white line-clamp-2 mb-2">{{ $memoire->titre }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-3 mb-4 leading-relaxed">{{ $memoire->resume }}</p>
                    <div class="mt-auto border-t border-gray-100 dark:border-gray-800 pt-3 flex items-center justify-between gap-2">
                        <form method="POST" action="{{ route('favoris.toggle', $memoire) }}">
                            @csrf
                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 hover:underline transition">
                                ♥ Retirer des favoris
                            </button>
                        </form>
                        <a href="{{ route('catalogue.show', $memoire) }}" class="text-xs font-semibold text-brand-600 hover:underline dark:text-brand-400">Détails →</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
