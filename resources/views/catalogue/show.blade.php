<x-layouts.app :title="$memoire->titre">
<div class="mx-auto max-w-4xl space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-gray-400 mb-2">
        <a href="{{ route('catalogue.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">&larr; Retour au catalogue</a>
    </div>

    <div class="rounded-2xl border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6 sm:p-8 shadow-sm space-y-6">

        {{-- Badges --}}
        <div class="flex flex-wrap items-center gap-2 text-xs font-semibold">
            <span class="rounded bg-indigo-50 dark:bg-indigo-900/30 px-2.5 py-1 text-indigo-700 dark:text-indigo-300">
                {{ $memoire->filiere?->libelle ?? 'Filière' }}
            </span>
            <span class="rounded bg-slate-100 dark:bg-gray-800 px-2.5 py-1 text-slate-700 dark:text-gray-300">
                Promotion {{ $memoire->anneeAcademique?->libelle ?? '' }}
            </span>
        </div>

        {{-- Titre --}}
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white leading-snug">
            {{ $memoire->titre }}
        </h1>

        {{-- Méta --}}
        <div class="flex flex-wrap items-center gap-6 text-sm text-slate-600 dark:text-gray-400 border-y border-slate-100 dark:border-gray-800 py-3">
            <div>
                Auteur :
                <strong class="text-slate-900 dark:text-white">
                    {{ $memoire->etudiant?->prenom }} {{ $memoire->etudiant?->nom }}
                </strong>
            </div>
            <div>
                Encadreur :
                <strong class="text-slate-900 dark:text-white">{{ $memoire->encadreur ?? 'Non renseigné' }}</strong>
            </div>
            <div>
                Date de dépôt :
                <strong class="text-slate-900 dark:text-white">
                    {{ $memoire->created_at ? $memoire->created_at->format('d/m/Y') : 'Non renseignée' }}
                </strong>
            </div>
        </div>

        {{-- Résumé (visible par tous) --}}
        <div>
            <h2 class="text-base font-bold text-slate-900 dark:text-white mb-2">Résumé</h2>
            <p class="text-sm text-slate-700 dark:text-gray-300 leading-relaxed whitespace-pre-line bg-slate-50 dark:bg-gray-800 p-4 rounded-xl border border-slate-100 dark:border-gray-700">
                {{ $memoire->resume ?? 'Aucun résumé renseigné pour ce mémoire.' }}
            </p>
        </div>

        {{-- Mots-clés (visible par tous) --}}
        @if ($memoire->mots_cles)
            <div>
                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-gray-400 mb-2">Mots-clés</h3>
                <div class="flex flex-wrap gap-1.5">
                    @foreach (explode(',', $memoire->mots_cles) as $mot)
                        <span class="rounded-md bg-slate-100 dark:bg-gray-800 px-2.5 py-1 text-xs text-slate-700 dark:text-gray-300">
                            #{{ trim($mot) }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ═══ ACTIONS (selon statut connexion) ══════════════════════════ --}}
        <div class="flex flex-wrap items-center gap-4 pt-4 border-t border-slate-100 dark:border-gray-800">
            @auth
                {{-- Utilisateur connecté : accès PDF si autorisé --}}
                @can('lire', $memoire)
                    <a href="{{ route('memoires.lire', $memoire) }}"
                       class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Lire en ligne
                    </a>
                @endcan

                @can('telecharger', $memoire)
                    <a href="{{ route('memoires.telecharger', $memoire) }}"
                       class="inline-flex items-center gap-2 rounded-lg border border-slate-300 dark:border-gray-700 px-5 py-2.5 text-sm font-medium text-slate-700 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-gray-800 transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Télécharger PDF
                    </a>
                @endcan

                {{-- Favori --}}
                <form method="POST" action="{{ route('favoris.toggle', $memoire) }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg border px-4 py-2.5 text-sm font-medium transition
                                {{ $estFavori
                                   ? 'border-red-300 bg-red-50 text-red-700 hover:bg-red-100 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400'
                                   : 'border-slate-300 dark:border-gray-700 text-slate-700 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-gray-800' }}">
                        {{ $estFavori ? '♥ Retirer des favoris' : '♡ Ajouter aux favoris' }}
                    </button>
                </form>

            @else
                {{-- Visiteur non connecté : bannière invitation --}}
                <div class="w-full rounded-xl border border-indigo-200 bg-indigo-50 dark:border-indigo-800 dark:bg-indigo-900/20 px-5 py-4">
                    <p class="text-sm text-indigo-800 dark:text-indigo-300 font-medium">
                        🔒 Connectez-vous pour lire ou télécharger ce mémoire et le sauvegarder en favoris.
                    </p>
                    <div class="mt-3 flex gap-3">
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 transition">
                            Se connecter
                        </a>
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center rounded-lg border border-indigo-300 px-4 py-2 text-sm font-medium text-indigo-700 hover:bg-indigo-100 dark:border-indigo-700 dark:text-indigo-300 transition">
                            Créer un compte
                        </a>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</div>
</x-layouts.app>
