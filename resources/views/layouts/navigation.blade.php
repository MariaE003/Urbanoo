@php
    $isCitizen = auth()->check() && auth()->user()->role === 'citizen';
    $isAdmin = auth()->check() && auth()->user()->role === 'admin';
@endphp

<nav x-data="{ open: false }" class="border-b border-gray-200 bg-white">
    <div class="mx-auto flex max-w-[1400px] items-center gap-4 px-4 py-4 sm:px-6">
        <a href="{{ route('home') }}" class="shrink-0">
            <img
                src="{{ asset('imgs/logo.png') }}"
                alt="Logo Urbanoo"
                class="h-14 w-14 rounded-2xl object-cover sm:h-16 sm:w-16"
            >
        </a>

        <div class="hidden items-center gap-3 lg:flex">
            <a href="{{ route('home') }}" class="rounded-full px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 hover:text-blue-600">
                Accueil
            </a>

            @if ($isCitizen)
                <a href="{{ route('citizen.dashboard') }}" class="rounded-full px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 hover:text-blue-600">
                    Mes signalements
                </a>
            @endif

            @if ($isAdmin)
                <a href="{{ route('admin.dashboard') }}" class="rounded-full px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 hover:text-blue-600">
                    Dashboard
                </a>

                <a href="{{ route('admin.categories.index') }}" class="rounded-full px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 hover:text-blue-600">
                    Catégories
                </a>
            @endif
        </div>

        <div class="ml-auto hidden items-center gap-3 lg:flex">
            <div class="rounded-2xl border border-gray-200 px-4 py-2 text-sm text-gray-600">
                {{ Auth::user()->name }}
            </div>

            <a href="{{ route('profile.edit') }}" class="rounded-2xl border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 hover:text-blue-600">
                Profil
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rounded-2xl bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                    Déconnexion
                </button>
            </form>
        </div>

        <button
            @click="open = !open"
            type="button"
            class="ml-auto inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-gray-200 text-gray-700 transition hover:bg-gray-50 lg:hidden"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <div x-show="open" x-transition class="border-t border-gray-100 bg-white lg:hidden">
        <div class="mx-auto flex max-w-[1400px] flex-col gap-2 px-4 py-4 sm:px-6">
            <a href="{{ route('home') }}" class="rounded-2xl px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 hover:text-blue-600">
                Accueil
            </a>

            @if ($isCitizen)
                <a href="{{ route('citizen.dashboard') }}" class="rounded-2xl px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 hover:text-blue-600">
                    Mes signalements
                </a>
            @endif

            @if ($isAdmin)
                <a href="{{ route('admin.dashboard') }}" class="rounded-2xl px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 hover:text-blue-600">
                    Dashboard
                </a>

                <a href="{{ route('admin.categories.index') }}" class="rounded-2xl px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 hover:text-blue-600">
                    Catégories
                </a>
            @endif

            <div class="mt-2 rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-600">
                {{ Auth::user()->name }}<br>
                <span class="text-xs text-gray-500">{{ Auth::user()->email }}</span>
            </div>

            <a href="{{ route('profile.edit') }}" class="rounded-2xl border border-gray-200 px-4 py-3 text-center text-sm font-medium text-gray-700 transition hover:bg-gray-50 hover:text-blue-600">
                Profil
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full rounded-2xl bg-blue-600 px-4 py-3 text-sm font-medium text-white transition hover:bg-blue-700">
                    Déconnexion
                </button>
            </form>
        </div>
    </div>
</nav>
