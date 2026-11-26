@php
    use App\Enums\Role;
    use App\Models\Memoire;
    use App\Enums\StatutMemoire;

    $user = auth()->user();
    $role = $user->role instanceof \BackedEnum ? $user->role->value : (string) $user->role;
@endphp

@extends('layouts.dashboard')

@section('title', 'Tableau de bord')

@section('content')
<div class="space-y-6">

    {{-- ═══ EN-TÊTE PERSONNALISÉ ════════════════════════════════════════════ --}}
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                Bonjour, {{ $user->prenom ?? $user->name ?? 'Utilisateur' }} 👋
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                @switch($role)
                    @case('etudiant')       Espace étudiant — Gérez vos mémoires et consultez le catalogue. @break
                    @case('responsable')    Espace bibliothèque — Validez les mémoires soumis. @break
                    @case('chef_departement') Espace chef de département — Suivez les mémoires de votre filière. @break
                    @case('admin')          Tableau de bord administrateur — Vue globale du système. @break
                    @default                Bienvenue sur la plateforme de gestion des mémoires.
                @endswitch
            </p>
        </div>

        {{-- Bouton d'action principal selon le rôle --}}
        @if ($role === 'etudiant')
            <a href="{{ route('memoires.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-brand-600 transition">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Déposer un mémoire
            </a>
        @elseif ($role === 'responsable')
            <a href="{{ route('validation.index') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-brand-600 transition">
                Voir les mémoires à valider →
            </a>
        @elseif ($role === 'chef_departement')
            <a href="{{ route('filiere.memoires') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-brand-600 transition">
                Mémoires de ma filière →
            </a>
        @elseif ($role === 'admin')
            <a href="{{ route('admin.statistiques') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-brand-600 transition">
                Voir les statistiques →
            </a>
        @endif
    </div>

    {{-- ═══ CARTES STATISTIQUES (selon rôle) ══════════════════════════════ --}}
    @if ($role === 'etudiant')
        @php
            $mesMemoires      = Memoire::where('etudiant_id', $user->id)->get();
            $mesValides       = $mesMemoires->where('statut', 'valide')->count();
            $mesEnAttente     = $mesMemoires->whereIn('statut', ['soumis','en_verification'])->count();
            $mesRejetes       = $mesMemoires->where('statut', 'rejete')->count();
            $totalCatalogue   = Memoire::visibles()->count();
        @endphp
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            @include('partials.stat-card', ['label'=>'Mes mémoires', 'value'=>$mesMemoires->count(), 'color'=>'brand', 'icon'=>'📄'])
            @include('partials.stat-card', ['label'=>'Validés', 'value'=>$mesValides, 'color'=>'green', 'icon'=>'✅'])
            @include('partials.stat-card', ['label'=>'En attente', 'value'=>$mesEnAttente, 'color'=>'orange', 'icon'=>'⏳'])
            @include('partials.stat-card', ['label'=>'Dans le catalogue', 'value'=>$totalCatalogue, 'color'=>'indigo', 'icon'=>'📚'])
        </div>

    @elseif ($role === 'responsable')
        @php
            $aValider       = Memoire::whereIn('statut', ['soumis','en_verification'])->count();
            $totalValides   = Memoire::where('statut', 'valide')->count();
            $totalRejetes   = Memoire::where('statut', 'rejete')->count();
            $totalDeposes   = Memoire::count();
        @endphp
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            @include('partials.stat-card', ['label'=>'En attente', 'value'=>$aValider, 'color'=>'orange', 'icon'=>'⏳'])
            @include('partials.stat-card', ['label'=>'Validés', 'value'=>$totalValides, 'color'=>'green', 'icon'=>'✅'])
            @include('partials.stat-card', ['label'=>'Rejetés', 'value'=>$totalRejetes, 'color'=>'red', 'icon'=>'❌'])
            @include('partials.stat-card', ['label'=>'Total déposés', 'value'=>$totalDeposes, 'color'=>'brand', 'icon'=>'📄'])
        </div>

    @elseif ($role === 'chef_departement')
        @php
            $filiere        = \App\Models\Filiere::find($user->filiere_id);
            $memoiresFiliere = $filiere ? Memoire::where('filiere_id', $filiere->id)->get() : collect();
            $validesFiliere = $memoiresFiliere->where('statut', 'valide')->count();
            $enAttenteFiliere = $memoiresFiliere->whereIn('statut', ['soumis','en_verification'])->count();
        @endphp
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            @include('partials.stat-card', ['label'=>'Total filière', 'value'=>$memoiresFiliere->count(), 'color'=>'brand', 'icon'=>'📄'])
            @include('partials.stat-card', ['label'=>'Validés', 'value'=>$validesFiliere, 'color'=>'green', 'icon'=>'✅'])
            @include('partials.stat-card', ['label'=>'En attente', 'value'=>$enAttenteFiliere, 'color'=>'orange', 'icon'=>'⏳'])
            @include('partials.stat-card', ['label'=>'Ma filière', 'value'=>$filiere?->libelle ?? '—', 'color'=>'indigo', 'icon'=>'🎓', 'isText'=>true])
        </div>

    @elseif ($role === 'admin')
        @php
            $totalMemoires  = Memoire::count();
            $aValider       = Memoire::whereIn('statut', ['soumis','en_verification'])->count();
            $totalValides   = Memoire::where('statut', 'valide')->count();
            $totalUsers     = \App\Models\User::count();
        @endphp
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            @include('partials.stat-card', ['label'=>'Total mémoires', 'value'=>$totalMemoires, 'color'=>'brand', 'icon'=>'📄'])
            @include('partials.stat-card', ['label'=>'Validés', 'value'=>$totalValides, 'color'=>'green', 'icon'=>'✅'])
            @include('partials.stat-card', ['label'=>'En attente', 'value'=>$aValider, 'color'=>'orange', 'icon'=>'⏳'])
            @include('partials.stat-card', ['label'=>'Utilisateurs', 'value'=>$totalUsers, 'color'=>'indigo', 'icon'=>'👥'])
        </div>
    @endif

    {{-- ═══ CONTENU PRINCIPAL SELON LE RÔLE ═══════════════════════════════ --}}

    @if ($role === 'etudiant')
        {{-- Mes derniers mémoires --}}
        @php $derniersMemo = Memoire::where('etudiant_id', $user->id)->with(['filiere'])->latest()->take(5)->get(); @endphp
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-xs overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-800">
                <h2 class="font-semibold text-gray-900 dark:text-white">Mes dernières soumissions</h2>
                <a href="{{ route('mes-memoires') }}" class="text-xs text-brand-600 hover:underline dark:text-brand-400">Voir tout →</a>
            </div>
            @if ($derniersMemo->isEmpty())
                <div class="p-10 text-center text-sm text-gray-400 dark:text-gray-500">
                    Vous n'avez encore déposé aucun mémoire.
                    <a href="{{ route('memoires.create') }}" class="text-brand-600 hover:underline dark:text-brand-400">Déposer maintenant</a>
                </div>
            @else
                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach ($derniersMemo as $m)
                        @php
                            $badge = match($m->statut) {
                                'valide'          => ['✅ Validé', 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'],
                                'rejete'          => ['❌ Rejeté', 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'],
                                'en_verification' => ['🔍 En vérification', 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400'],
                                default           => ['⏳ Soumis', 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400'],
                            };
                        @endphp
                        <div class="flex items-center justify-between gap-4 px-5 py-3.5">
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $m->titre }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $m->filiere?->libelle }} — {{ $m->created_at->format('d/m/Y') }}</p>
                            </div>
                            <span class="inline-flex shrink-0 items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $badge[1] }}">{{ $badge[0] }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    @elseif ($role === 'responsable')
        {{-- Mémoires à valider en urgence --}}
        @php $urgents = Memoire::whereIn('statut', ['soumis','en_verification'])->with(['etudiant','filiere'])->oldest()->take(8)->get(); @endphp
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-xs overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-800">
                <h2 class="font-semibold text-gray-900 dark:text-white">Mémoires à traiter (les plus anciens)</h2>
                <a href="{{ route('validation.index') }}" class="text-xs text-brand-600 hover:underline dark:text-brand-400">Voir tout →</a>
            </div>
            @if ($urgents->isEmpty())
                <div class="p-10 text-center text-sm text-green-600 dark:text-green-400">🎉 Aucun mémoire en attente — tout est à jour !</div>
            @else
                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach ($urgents as $m)
                        <div class="flex items-center justify-between gap-4 px-5 py-3.5">
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $m->titre }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $m->etudiant?->prenom }} {{ $m->etudiant?->nom }} — {{ $m->filiere?->libelle }} — soumis le {{ $m->created_at->format('d/m/Y') }}
                                </p>
                            </div>
                            <a href="{{ route('validation.show', $m) }}"
                               class="shrink-0 inline-flex items-center gap-1 rounded-lg bg-brand-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-brand-600 transition">
                                Examiner →
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    @elseif ($role === 'chef_departement')
        {{-- Derniers mémoires de sa filière --}}
        @php $derniersFiliere = $memoiresFiliere->sortByDesc('created_at')->take(6); @endphp
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-xs overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-800">
                <h2 class="font-semibold text-gray-900 dark:text-white">Derniers mémoires — {{ $filiere?->libelle }}</h2>
                <a href="{{ route('filiere.memoires') }}" class="text-xs text-brand-600 hover:underline dark:text-brand-400">Voir tout →</a>
            </div>
            @if ($derniersFiliere->isEmpty())
                <div class="p-10 text-center text-sm text-gray-400 dark:text-gray-500">Aucun mémoire pour votre filière.</div>
            @else
                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach ($derniersFiliere as $m)
                        <div class="flex items-center justify-between gap-4 px-5 py-3.5">
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $m->titre }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $m->etudiant?->prenom }} {{ $m->etudiant?->nom }} — {{ $m->created_at->format('d/m/Y') }}</p>
                            </div>
                            <a href="{{ route('catalogue.show', $m) }}" class="text-xs text-brand-600 hover:underline dark:text-brand-400 shrink-0">Voir →</a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    @elseif ($role === 'admin')
        {{-- Vue globale admin --}}
        @php $recents = Memoire::with(['etudiant','filiere'])->latest()->take(8)->get(); @endphp
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-xs overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-800">
                <h2 class="font-semibold text-gray-900 dark:text-white">Derniers mémoires déposés</h2>
                <a href="{{ route('validation.index') }}" class="text-xs text-brand-600 hover:underline dark:text-brand-400">Voir la file de validation →</a>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach ($recents as $m)
                    @php
                        $badge = match($m->statut) {
                            'valide'          => ['✅ Validé', 'bg-green-100 text-green-800'],
                            'rejete'          => ['❌ Rejeté', 'bg-red-100 text-red-800'],
                            'en_verification' => ['🔍 En vérif.', 'bg-blue-100 text-blue-800'],
                            default           => ['⏳ Soumis', 'bg-orange-100 text-orange-800'],
                        };
                    @endphp
                    <div class="flex items-center justify-between gap-4 px-5 py-3.5">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $m->titre }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $m->etudiant?->prenom }} {{ $m->etudiant?->nom }} — {{ $m->filiere?->libelle }}</p>
                        </div>
                        <span class="shrink-0 inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $badge[1] }}">{{ $badge[0] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ═══ ACCÈS RAPIDES ══════════════════════════════════════════════════ --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5 shadow-xs">
        <h2 class="font-semibold text-gray-900 dark:text-white mb-4">Accès rapides</h2>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
            <a href="{{ route('catalogue.index') }}"
               class="flex flex-col items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:border-brand-300 transition">
                📚<span>Catalogue</span>
            </a>
            @if ($role === 'etudiant')
                <a href="{{ route('memoires.create') }}"
                   class="flex flex-col items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:border-brand-300 transition">
                    📤<span>Déposer</span>
                </a>
                <a href="{{ route('favoris.index') }}"
                   class="flex flex-col items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:border-brand-300 transition">
                    ❤️<span>Favoris</span>
                </a>
                <a href="{{ route('historique.index') }}"
                   class="flex flex-col items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 hover:border-brand-300 transition">
                    📖<span>Historique</span>
                </a>
            @elseif ($role === 'responsable')
                <a href="{{ route('validation.index') }}"
                   class="flex flex-col items-center gap-2 rounded-xl border border-brand-200 bg-brand-50 dark:border-brand-800 dark:bg-brand-900/20 p-4 text-center text-sm font-semibold text-brand-700 dark:text-brand-400 hover:bg-brand-100 transition">
                    ✔️<span>Validation</span>
                </a>
                <a href="{{ route('favoris.index') }}"
                   class="flex flex-col items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    ❤️<span>Favoris</span>
                </a>
                <a href="{{ route('historique.index') }}"
                   class="flex flex-col items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    📖<span>Historique</span>
                </a>
            @elseif ($role === 'chef_departement')
                <a href="{{ route('filiere.memoires') }}"
                   class="flex flex-col items-center gap-2 rounded-xl border border-brand-200 bg-brand-50 dark:border-brand-800 dark:bg-brand-900/20 p-4 text-center text-sm font-semibold text-brand-700 dark:text-brand-400 hover:bg-brand-100 transition">
                    🎓<span>Ma filière</span>
                </a>
                <a href="{{ route('favoris.index') }}"
                   class="flex flex-col items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    ❤️<span>Favoris</span>
                </a>
                <a href="{{ route('historique.index') }}"
                   class="flex flex-col items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    📖<span>Historique</span>
                </a>
            @elseif ($role === 'admin')
                <a href="{{ route('admin.users.index') }}"
                   class="flex flex-col items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    👥<span>Utilisateurs</span>
                </a>
                <a href="{{ route('admin.filieres.index') }}"
                   class="flex flex-col items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    🎓<span>Filières</span>
                </a>
                <a href="{{ route('admin.statistiques') }}"
                   class="flex flex-col items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    📊<span>Statistiques</span>
                </a>
            @endif
        </div>
    </div>

</div>
@endsection
