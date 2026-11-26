@extends('layouts.dashboard')

@section('title', 'Lire le mémoire')

@section('content')
<div class="space-y-4">
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
        <a href="{{ route('catalogue.index') }}" class="hover:text-brand-600 dark:hover:text-brand-400">Catalogue</a>
        <span>›</span>
        <a href="{{ route('catalogue.show', $memoire) }}" class="hover:text-brand-600 dark:hover:text-brand-400 line-clamp-1">{{ Str::limit($memoire->titre, 50) }}</a>
        <span>›</span>
        <span>Lecture</span>
    </div>

    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-xs overflow-hidden">
        {{-- Header --}}
        <div class="border-b border-gray-200 dark:border-gray-800 p-5 flex items-center justify-between gap-4">
            <div class="flex-1 min-w-0">
                <h1 class="text-lg font-bold text-gray-900 dark:text-white line-clamp-1">{{ $memoire->titre }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $memoire->etudiant?->prenom }} {{ $memoire->etudiant?->nom }} —
                    {{ $memoire->filiere?->libelle }}
                </p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                @if ($telechargementAutorise)
                    <a href="{{ route('memoires.telecharger', $memoire) }}"
                       class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 dark:border-gray-700 px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Télécharger
                    </a>
                @endif
            </div>
        </div>

        {{-- Visionneuse PDF --}}
        <div class="bg-gray-100 dark:bg-gray-950" style="height: 80vh;">
            <iframe src="{{ $urlPdf }}" class="w-full h-full border-0" title="{{ $memoire->titre }}"></iframe>
        </div>
    </div>
</div>
@endsection
