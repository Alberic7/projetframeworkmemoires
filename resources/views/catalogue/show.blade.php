<x-layouts.app :title="$memoire->titre">
<div class="mx-auto max-w-4xl space-y-6">
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
        <a href="{{ route('catalogue.index') }}" class="hover:text-indigo-600">&larr; Retour au catalogue</a>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm space-y-6">
        <div>
            <div class="flex flex-wrap items-center gap-2 text-xs font-semibold text-indigo-600 mb-3">
                <span class="rounded bg-indigo-50 px-2.5 py-1 text-indigo-700">{{ $memoire->filiere?->libelle ?? 'Filière' }}</span>
                <span class="rounded bg-slate-100 px-2.5 py-1 text-slate-700">Promotion {{ $memoire->anneeAcademique?->libelle ?? '' }}</span>
            </div>

            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-snug">
                {{ $memoire->titre }}
            </h1>

            <div class="mt-4 flex flex-wrap items-center gap-6 text-sm text-slate-600 border-y border-slate-100 py-3">
                <div>
                    Auteur : <strong class="text-slate-900">{{ $memoire->etudiant?->prenom }} {{ $memoire->etudiant?->nom }}</strong>
                </div>
                <div>
                    Encadreur : <strong class="text-slate-900">{{ $memoire->encadreur ?? 'Non renseigné' }}</strong>
                </div>
                <div>
                    Date de dépôt : <strong class="text-slate-900">{{ $memoire->created_at ? $memoire->created_at->format('d/m/Y') : 'Non renseignée' }}</strong>
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-base font-bold text-slate-900 mb-2">Résumé</h2>
            <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line bg-slate-50 p-4 rounded-xl border border-slate-100">
                {{ $memoire->resume ?? 'Aucun résumé renseigné pour ce mémoire.' }}
            </p>
        </div>

        @if ($memoire->mots_cles)
            <div>
                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Mots-clés</h3>
                <div class="flex flex-wrap gap-1.5">
                    @foreach (explode(',', $memoire->mots_cles) as $mot)
                        <span class="rounded-md bg-slate-100 px-2.5 py-1 text-xs text-slate-700">
                            #{{ trim($mot) }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="flex flex-wrap items-center gap-4 pt-4 border-t border-slate-100">
            @if ($memoire->fichier_pdf)
                <a href="{{ route('memoires.pdf', $memoire) }}" target="_blank"
                   class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Télécharger le PDF
                </a>
            @endif

            @auth
                <form method="POST" action="{{ route('favoris.toggle', $memoire) }}">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                        <span>{{ $estFavori ? '★ Retirer des favoris' : '☆ Ajouter aux favoris' }}</span>
                    </button>
                </form>
            @endauth
        </div>
    </div>
</div>
</x-layouts.app>
