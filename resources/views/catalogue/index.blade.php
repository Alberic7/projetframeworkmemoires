<x-layouts.app title="Catalogue des Mémoires">
<div class="space-y-8">
    <!-- Header de recherche -->
    <div class="rounded-2xl bg-gradient-to-r from-indigo-900 to-indigo-700 px-6 py-10 sm:px-10 text-white shadow-md">
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Catalogue des Mémoires Universitaires</h1>
        <p class="mt-2 text-sm sm:text-base text-indigo-100 max-w-2xl">
            Explorez les travaux de recherche, thèses et mémoires de fin d'études validés.
        </p>

        <!-- Formulaire de recherche et filtres -->
        <form method="GET" action="{{ route('catalogue.index') }}" class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-4">
            <div class="sm:col-span-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Rechercher par titre, résumé ou mots-clés..."
                    class="w-full rounded-lg bg-white px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 shadow-xs outline-none focus:ring-2 focus:ring-indigo-300" />
            </div>

            <div>
                <select name="filiere_id" class="w-full rounded-lg bg-white px-3 py-2.5 text-sm text-slate-800 shadow-xs outline-none">
                    <option value="">Toutes les filières</option>
                    @foreach ($filieres as $filiere)
                        <option value="{{ $filiere->id }}" {{ request('filiere_id') == $filiere->id ? 'selected' : '' }}>
                            {{ $filiere->libelle }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <select name="annee_id" class="w-full rounded-lg bg-white px-3 py-2.5 text-sm text-slate-800 shadow-xs outline-none">
                    <option value="">Années</option>
                    @foreach ($annees as $annee)
                        <option value="{{ $annee->id }}" {{ request('annee_id') == $annee->id ? 'selected' : '' }}>
                            {{ $annee->libelle }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="rounded-lg bg-indigo-500 hover:bg-indigo-400 px-5 py-2.5 text-sm font-semibold text-white shadow-xs transition shrink-0">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des mémoires -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-slate-800">
                Résultats ({{ $memoires->total() }})
            </h2>
        </div>

        @if ($memoires->isEmpty())
            <div class="rounded-xl border border-slate-200 bg-white p-12 text-center text-slate-500">
                <p class="text-base font-medium">Aucun mémoire ne correspond à vos critères de recherche.</p>
                <a href="{{ route('catalogue.index') }}" class="mt-2 inline-block text-sm text-indigo-600 hover:underline">
                    Réinitialiser les filtres
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($memoires as $memoire)
                    <div class="flex flex-col rounded-xl border border-slate-200 bg-white p-5 shadow-xs hover:shadow-md transition">
                        <div class="flex items-center justify-between gap-2 text-xs text-indigo-600 font-semibold mb-2">
                            <span>{{ $memoire->filiere?->libelle ?? 'Filière générale' }}</span>
                            <span class="rounded bg-slate-100 px-2 py-0.5 text-slate-600">{{ $memoire->anneeAcademique?->libelle ?? '' }}</span>
                        </div>

                        <h3 class="text-base font-bold text-slate-900 line-clamp-2 mb-2">
                            {{ $memoire->titre }}
                        </h3>

                        <p class="text-xs text-slate-500 line-clamp-3 mb-4 leading-relaxed">
                            {{ $memoire->resume ?? 'Aucun résumé disponible.' }}
                        </p>

                        <div class="mt-auto border-t border-slate-100 pt-3 flex items-center justify-between text-xs text-slate-600">
                            <div>
                                Auteur : <strong class="text-slate-800">{{ $memoire->etudiant?->prenom }} {{ $memoire->etudiant?->nom }}</strong>
                            </div>
                            <a href="{{ route('catalogue.show', $memoire) }}" class="font-semibold text-indigo-600 hover:underline">
                                Détails &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $memoires->links() }}
            </div>
        @endif
    </div>
</div>
</x-layouts.app>
