@extends('layouts.dashboard')

@section('title', 'Statistiques')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Statistiques globales</h1>

    @php
        use App\Models\Memoire;
        use App\Models\User;
        $stats = [
            'total_memoires'  => Memoire::count(),
            'valides'         => Memoire::where('statut','valide')->count(),
            'en_attente'      => Memoire::whereIn('statut',['soumis','en_verification'])->count(),
            'rejetes'         => Memoire::where('statut','rejete')->count(),
            'total_etudiants' => User::where('role','etudiant')->count(),
        ];
    @endphp

    <div class="grid grid-cols-2 gap-4 lg:grid-cols-5">
        @foreach ([['Total mémoires', $stats['total_memoires'], '📄', 'brand'], ['Validés', $stats['valides'], '✅', 'green'], ['En attente', $stats['en_attente'], '⏳', 'orange'], ['Rejetés', $stats['rejetes'], '❌', 'red'], ['Étudiants', $stats['total_etudiants'], '👥', 'indigo']] as [$label, $value, $icon, $color])
            @include('partials.stat-card', compact('label','value','icon','color'))
        @endforeach
    </div>

    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-xs">
        <p class="text-sm text-gray-500 dark:text-gray-400">📊 Des graphiques et tableaux détaillés seront ajoutés ici prochainement.</p>
    </div>
</div>
@endsection
