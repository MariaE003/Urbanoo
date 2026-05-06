@extends('partials.header')

@section('content')
<style>
    .leaflet-popup-content {
        margin: 0 !important;
        width: 340px !important;
    }

    .leaflet-popup-content-wrapper {
        border-radius: 24px !important;
        overflow: hidden;
    }

    .leaflet-popup-tip {
        box-shadow: none !important;
    }

    .map-pin {
        position: relative;
        width: 22px;
        height: 22px;
        border-radius: 9999px 9999px 9999px 0;
        transform: rotate(-45deg);
        border: 2px solid #ffffff;
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.22);
    }

    .map-pin::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 8px;
        height: 8px;
        border-radius: 9999px;
        background: #ffffff;
        transform: translate(-50%, -50%);
    }

    .map-pin-red {
        background: #ef4444;
    }

    .map-pin-yellow {
        background: #f59e0b;
    }

    .map-pin-green {
        background: #22c55e;
    }

    .map-pin-blue {
        background: #2563eb;
    }

    .my-location-marker {
        position: relative;
        width: 22px;
        height: 22px;
        border-radius: 9999px;
        background: #2563eb;
        border: 3px solid #ffffff;
        box-shadow: 0 8px 18px rgba(37, 99, 235, 0.28);
    }

    .my-location-marker::before {
        content: '';
        position: absolute;
        inset: -8px;
        border-radius: 9999px;
        background: rgba(37, 99, 235, 0.18);
    }

    .my-location-marker::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 6px;
        height: 6px;
        border-radius: 9999px;
        background: #ffffff;
        transform: translate(-50%, -50%);
    }

    .btn-map-location {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 52px;
        height: 52px;
        border: 1px solid rgba(148, 163, 184, 0.28);
        border-radius: 18px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        color: #2563eb;
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.12);
        transition: transform 0.2s ease, box-shadow 0.2s ease, color 0.2s ease;
    }

    .btn-map-location:hover {
        transform: translateY(-1px);
        color: #1d4ed8;
        box-shadow: 0 16px 32px rgba(37, 99, 235, 0.18);
    }

    .btn-map-location:focus-visible {
        outline: none;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.18), 0 16px 32px rgba(37, 99, 235, 0.18);
    }

    .btn-map-location__inner {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 9999px;
        background: rgba(37, 99, 235, 0.08);
    }

    .btn-map-location__inner::after {
        content: '';
        position: absolute;
        width: 10px;
        height: 10px;
        border-radius: 9999px;
        background: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.18);
    }

    .popup-ma-position {
        padding: 16px;
        min-width: 180px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
    }

    .popup-ma-position__badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 10px;
        border-radius: 9999px;
        background: rgba(37, 99, 235, 0.1);
        color: #1d4ed8;
        font-size: 12px;
        font-weight: 600;
    }

    .popup-ma-position__dot {
        width: 8px;
        height: 8px;
        border-radius: 9999px;
        background: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.14);
    }

    .popup-ma-position__title {
        margin-top: 12px;
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
    }

    .popup-ma-position__text {
        margin-top: 4px;
        font-size: 13px;
        line-height: 1.5;
        color: #64748b;
    }
</style>

@php
    $pageServices = request('page') === 'services' && auth()->check() && auth()->user()->role === 'admin';
@endphp
<div class="mx-auto max-w-[1400px] space-y-6">

    <div class="grid gap-7 xl:grid-cols-[360px_minmax(0,1fr)] xl:items-start">
        <section class="space-y-4 xl:sticky xl:top-6 xl:flex xl:h-[calc(100vh-5.5rem)] xl:flex-col xl:overflow-hidden">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-black">Signalements récents</h2>
                    <p class="mt-1 text-sm text-gray-500">Les derniers signalements publiés sur la carte</p>
                </div>
                <div class="flex items-center gap-3">
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('admin.services.index') }}" class="inline-flex shrink-0 items-center gap-2 rounded-full border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                            Services
                        </a>
                    @endif
                    <button id="boutonFiltres" type="button" class="flex shrink-0 items-center gap-2 rounded-full border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                        <img src="imgs/filter.svg" alt="Filtre" class="h-4 w-4">
                        Filtres
                    </button>
                </div>
            </div>
            <div id="zoneFiltres" class="hidden rounded-[28px] border border-gray-100 bg-white p-5 shadow-sm">
                <div class="flex flex-col gap-4">
                    <div>
                        <label for="champRecherche" class="mb-2 block text-sm font-medium text-gray-700">Recherche</label>
                        <input id="champRecherche" type="text"placeholder="Rechercher par titre ou description..."class="h-12 w-full rounded-2xl border border-gray-300 px-4 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="champStatut" class="mb-2 block text-sm font-medium text-gray-700">Statut</label>
                        <select id="champStatut" class="h-12 w-full rounded-2xl border border-gray-300 px-4 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Tous les statuts</option>
                            <option value="pending">En attente</option>
                            <option value="in_progress">En cours</option>
                            <option value="resolved">Résolu</option>
                        </select>
                    </div>

                    <div>
                        <label for="champCategorie" class="mb-2 block text-sm font-medium text-gray-700">Catégorie</label>
                        <select id="champCategorie" class="h-12 w-full rounded-2xl border border-gray-300 px-4 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Toutes les catégories</option>
                        </select>
                    </div>

                    <div class="flex gap-3">
                        <button id="boutonRechercher" type="button" class="h-12 rounded-2xl bg-blue-600 px-6 font-medium text-white transition hover:bg-blue-700">
                            Rechercher
                        </button>

                        <button id="boutonReinitialiser" type="button" class="h-12 rounded-2xl border border-gray-300 px-6 font-medium text-gray-700 transition hover:bg-gray-50">
                            Réinitialiser
                        </button>
                    </div>
                </div>
            </div>

            <div id="resumeFiltres" class="hidden rounded-2xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-700"></div>

            <div id="listeReports" class="flex flex-col gap-4 xl:min-h-0 xl:flex-1 xl:overflow-y-auto xl:pr-1"></div>
        </section>

        <section class="min-w-0 space-y-3 xl:sticky xl:top-6 xl:flex xl:h-[calc(100vh-5.5rem)] xl:flex-col">
            <div class="flex justify-end">
                <button  id="myLocationBtn"type="button"  title="Ma position"  aria-label="Ma position"  class="btn-map-location">
                    <span class="btn-map-location__inner" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="6"></circle>
                            <path d="M12 3v3"></path>
                            <path d="M12 18v3"></path>
                            <path d="M3 12h3"></path>
                            <path d="M18 12h3"></path>
                        </svg>
                    </span>
                </button>
            </div>
            <div id="map" class="h-[460px] w-full rounded-2xl md:h-[620px] xl:min-h-0 xl:flex-1"></div>
        </section>
    </div>
</div>
@endsection
