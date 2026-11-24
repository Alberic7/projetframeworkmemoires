@if (session('success'))
    <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 flex items-start gap-3 shadow-xs mb-4">
        <svg class="h-5 w-5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <div class="text-sm font-medium">{{ session('success') }}</div>
    </div>
@endif

@if (session('error'))
    <div class="rounded-lg border border-rose-200 bg-rose-50 p-4 text-rose-800 flex items-start gap-3 shadow-xs mb-4">
        <svg class="h-5 w-5 text-rose-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <div class="text-sm font-medium">{{ session('error') }}</div>
    </div>
@endif

@if ($errors->any())
    <div class="rounded-lg border border-rose-200 bg-rose-50 p-4 text-rose-800 shadow-xs mb-4">
        <div class="flex items-start gap-3 mb-2">
            <svg class="h-5 w-5 text-rose-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
            </svg>
            <div class="text-sm font-semibold">Veuillez corriger les erreurs suivantes :</div>
        </div>
        <ul class="list-disc list-inside text-sm space-y-1 ml-8">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
