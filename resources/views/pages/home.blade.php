<x-app-layout title="Accueil">
    <!-- Hero Section -->
    <div class="rounded-2xl bg-gradient-to-br from-indigo-900 via-indigo-800 to-slate-900 px-6 py-16 sm:px-12 sm:py-20 text-white shadow-xl relative overflow-hidden">
        <div class="relative z-10 max-w-2xl">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-500/20 px-3 py-1 text-xs font-semibold text-indigo-200 border border-indigo-400/30 mb-4">
                Plateforme Académique
            </span>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white mb-4">
                Gestion & Consultation des Mémoires
            </h1>
            <p class="text-base sm:text-lg text-indigo-100/90 leading-relaxed mb-8">
                Consultez, déposez et gérez les mémoires de fin d'études en toute simplicité. Un espace centralisé pour étudiants, enseignants et jurys.
            </p>
            <div class="flex flex-wrap items-center gap-4">
                <a href="#" class="rounded-lg bg-indigo-500 px-5 py-3 text-sm font-semibold text-white shadow-md hover:bg-indigo-400 transition">
                    Explorer les mémoires
                </a>
                <a href="#" class="rounded-lg bg-white/10 px-5 py-3 text-sm font-semibold text-white hover:bg-white/20 transition border border-white/20">
                    Déposer mon mémoire
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques / Raccourcis rapides -->
    <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-xs">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-slate-500">Mémoires disponibles</h3>
                    <p class="text-2xl font-bold text-slate-900">0</p>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-xs">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-slate-500">Soutenances validées</h3>
                    <p class="text-2xl font-bold text-slate-900">0</p>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-xs">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-slate-500">Dépôts en attente</h3>
                    <p class="text-2xl font-bold text-slate-900">0</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
