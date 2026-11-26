@extends('layouts.dashboard')

@section('title', 'Historique de lecture')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Historique de lecture</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Les mémoires que vous avez consultés.</p>
    </div>

    @if ($consultations->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 p-16 text-center">
            <p class="text-base font-medium text-gray-500 dark:text-gray-400 mb-2">Aucune consultation enregistrée.</p>
            <a href="{{ route('catalogue.index') }}" class="text-sm text-brand-600 hover:underline dark:text-brand-400">Parcourir le catalogue →</a>
        </div>
    @else
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-xs overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800 text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Mémoire</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Filière</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Consulté le</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach ($consultations as $consultation)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                            <td class="px-5 py-4 font-medium text-gray-900 dark:text-white max-w-sm">
                                <p class="line-clamp-2">{{ $consultation->memoire?->titre ?? '(mémoire supprimé)' }}</p>
                            </td>
                            <td class="px-5 py-4 text-gray-500 dark:text-gray-400">{{ $consultation->memoire?->filiere?->libelle ?? '—' }}</td>
                            <td class="px-5 py-4 text-gray-500 dark:text-gray-400">{{ $consultation->created_at->format('d/m/Y à H:i') }}</td>
                            <td class="px-5 py-4">
                                @if ($consultation->memoire)
                                    <a href="{{ route('catalogue.show', $consultation->memoire) }}"
                                       class="text-xs font-medium text-brand-600 hover:underline dark:text-brand-400">Voir →</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
