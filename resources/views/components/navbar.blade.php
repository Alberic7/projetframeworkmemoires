<nav class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur supports-[backdrop-filter]:bg-white/60">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <!-- Logo & Nom du projet -->
            <div class="flex items-center gap-8">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 font-bold text-lg text-indigo-600 transition hover:text-indigo-700">
                    <svg class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                    <span class="text-slate-900 font-semibold tracking-tight">Mémoires<span class="text-indigo-600 font-extrabold">App</span></span>
                </a>

                <!-- Liens de navigation desktop -->
                <div class="hidden md:flex items-center gap-1 text-sm font-medium">
                    <a href="{{ url('/') }}" class="rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100 hover:text-indigo-600 transition">
                        Accueil
                    </a>
                    <a href="#" class="rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100 hover:text-indigo-600 transition">
                        Catalogue des mémoires
                    </a>
                    <a href="#" class="rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100 hover:text-indigo-600 transition">
                        Déposer un mémoire
                    </a>
                    <a href="#" class="rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100 hover:text-indigo-600 transition">
                        À propos
                    </a>
                </div>
            </div>

            <!-- Boutons d'action / Profil -->
            <div class="flex items-center gap-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="rounded-lg bg-indigo-50 px-3.5 py-2 text-sm font-semibold text-indigo-600 hover:bg-indigo-100 transition">
                            Mon Tableau de bord
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition px-2 py-1">
                            Connexion
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition">
                                Inscription
                            </a>
                        @endif
                    @endauth
                @else
                    <a href="#" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition">
                        Espace Membre
                    </a>
                @endif
            </div>
        </div>
    </div>
</nav>
