<footer class="border-t border-slate-200 bg-white py-8 text-sm text-slate-500 mt-auto">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
            </svg>
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Gestion des Mémoires') }} — Projet de Groupe.</p>
        </div>
        <div class="flex items-center gap-6">
            <a href="#" class="hover:text-indigo-600 transition">Documentation</a>
            <a href="#" class="hover:text-indigo-600 transition">Mentions légales</a>
            <a href="#" class="hover:text-indigo-600 transition">Support</a>
        </div>
    </div>
</footer>
