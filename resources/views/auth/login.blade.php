<x-layouts.app title="Connexion">
<div class="mx-auto max-w-md my-8 sm:my-12">
    <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
        <div class="mb-6 text-center">
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Connexion</h2>
            <p class="mt-1 text-sm text-slate-500">Accédez à votre espace de gestion des mémoires</p>
        </div>

        <x-alert />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Adresse Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    placeholder="ex: etudiant@eneam.bj"
                    class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none" />
                @error('email')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="password" class="block text-sm font-medium text-slate-700">Mot de passe</label>
                </div>
                <input id="password" type="password" name="password" required
                    placeholder="••••••••"
                    class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none" />
                @error('password')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="text-slate-600">Se souvenir de moi</span>
                </label>
            </div>

            <button type="submit"
                class="w-full rounded-lg bg-indigo-600 py-3 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 transition">
                Se connecter
            </button>
        </form>

        <div class="mt-6 rounded-lg bg-slate-50 p-4 border border-slate-100 text-xs text-slate-600 space-y-1">
            <p class="font-semibold text-slate-700">Comptes de test (Mot de passe : <span class="text-indigo-600">password</span>) :</p>
            <p>• Étudiant : <code class="text-indigo-600">etudiant@eneam.bj</code></p>
            <p>• Responsable bibliothèque : <code class="text-indigo-600">responsable@eneam.bj</code></p>
            <p>• Administrateur : <code class="text-indigo-600">admin@eneam.bj</code></p>
        </div>
    </div>
</div>
</x-layouts.app>
