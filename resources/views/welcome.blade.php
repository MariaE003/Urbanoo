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
@if(!$pageServices)
@push('scripts')
<script>
    const pageServices = @json($pageServices);//envoyer un var php/laravel => js
    const cacheLieux = {};

    function creerIconeMap(classeCouleur) {//icons marker 
        return L.divIcon({//divIcon(fonction Leaflet) => create icon frm element html
            className: 'bg-transparent border-0',//coutainer du icon
            html: `<div class="map-pin ${classeCouleur}"></div>`,//html du icon
            iconSize: [22, 22],//w + h
            iconAnchor: [11, 22],//bas-centre du marker su les coordone lat et long
            popupAnchor: [0, -18]//top du marker 18
        });
    }
    // ma position
    function creerIconeMaPosition() {
        return L.divIcon({
            className: 'bg-transparent border-0',
            html: '<div class="my-location-marker"></div>',
            iconSize: [22, 22],
            iconAnchor: [11, 11],//centre de icon sur position
            popupAnchor: [0, -12]
        });
    }

    function creerPopupMaPosition() {
        return `
            <div class="popup-ma-position">
                <span class="popup-ma-position__badge">
                    <span class="popup-ma-position__dot"></span>
                    Position actuelle
                </span>
                <p class="popup-ma-position__title">Ma position</p>
                <p class="popup-ma-position__text">Votre localisation actuelle sur la carte.</p>
            </div>
        `;
    }

    const redIcon = creerIconeMap('map-pin-red');
    const yellowIcon = creerIconeMap('map-pin-yellow');
    const greenIcon = creerIconeMap('map-pin-green');
    const blueIcon = creerIconeMap('map-pin-blue');
    const myLocationIcon = creerIconeMaPosition();

    function getIconByStatus(status) {
        if (status === 'pending') {
            return redIcon;
        } else if (status === 'in_progress') {
            return yellowIcon;
        } else if (status === 'resolved') {
            return greenIcon;
        }
        return redIcon; // par defaut
    }
    
    let tousLesReports = [];
    let marqueursReports = {};
    let categoriesMemorisees = [];
    let servicesMemorises = [];

    let userMarker = null;
    
    // la position actuelle
    let boutonMaPosition = document.getElementById('myLocationBtn');
    if (boutonMaPosition) {
        boutonMaPosition.addEventListener('click', () => {
            if (!navigator.geolocation) {//navigateur support geo ou non
                msg('Localisation', 'La geolocalisation n est pas supportee sur ce navigateur.');
                return;
            }

            navigator.geolocation.getCurrentPosition(position => {//demande la position actuelle
                let lat = position.coords.latitude;//recupere lat
                let lng = position.coords.longitude;

                if (userMarker) {
                    map.removeLayer(userMarker);
                }
                userMarker = L.marker([lat, lng])
                    .setIcon(myLocationIcon)
                    .addTo(map)
                    .bindPopup(creerPopupMaPosition())
                    .openPopup();

                map.setView([lat, lng], 14);//centrer la carte a cette position + niveau de zoom
            });
        });
    }

    const estConnecte = @json(auth()->check());
    const estAdmin = @json(auth()->check() && auth()->user()->role === 'admin');
    const idUtilisateur = @json(auth()->id());
    const urlConnexion = @json(route('login'));
    const urlInscription = @json(route('register'));

    let carte = null;
    let coucheMarqueurs = null;
    let map = null;

    if (!pageServices) {
        carte = L.map('map').setView([31.79, -7.09], 7)//creer et centralise sur lat+long;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {//add img to map(fond)
            attribution: 'Map data'
        }).addTo(carte);

        coucheMarqueurs = L.layerGroup().addTo(carte);//groupe contient les marker des report + add to map
        map = carte;
    }

    function ajusterCarte() {
        if (!carte) {
            return;
        }

        setTimeout(function () {
            carte.invalidateSize();//ajuster la carte quand un changement applique sur la taile du carte
        }, 200);

        window.addEventListener('resize', function () {//pour adapter la carte au ecran
            carte.invalidateSize();
        });
    }

    function activerBoutonFiltres() {
        let boutonFiltres = document.getElementById('boutonFiltres');
        let zoneFiltres = document.getElementById('zoneFiltres');

        if (!boutonFiltres || !zoneFiltres) {
            return;
        }

        boutonFiltres.addEventListener('click', function () {
            zoneFiltres.classList.toggle('hidden');

            setTimeout(function () {
                carte.invalidateSize();//Ajuster la carte apres open/close du filter
            }, 150);
        });
    }
    function afficherResumeFiltres(message, afficher = true) {
        let resumeFiltres = document.getElementById('resumeFiltres');
        if (!resumeFiltres) {
            return;
        }
        if (!afficher) {
            resumeFiltres.classList.add('hidden');
            resumeFiltres.textContent = '';
            return;
        }
        resumeFiltres.textContent = message;
        resumeFiltres.classList.remove('hidden');
    }

    function formaterDate(dateTexte) {
        return new Date(dateTexte).toLocaleDateString('fr-FR');
    }

    function obtenirNomStatut(statut) {
        if (statut === 'pending') {
            return 'En attente';
        }

        if (statut === 'in_progress') {
            return 'En cours';
        }

        if (statut === 'resolved') {
            return 'Résolu';
        }

        return statut;
    }

    function obtenirBadgeStatut(statut) {
        if (statut === 'pending') {
            return '<span class="inline-block rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700">En attente</span>';
        }

        if (statut === 'in_progress') {
            return '<span class="inline-block rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">En cours</span>';
        }

        if (statut === 'resolved') {
            return '<span class="inline-block rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">Résolu</span>';
        }

        return `<span class="inline-block rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">${statut}</span>`;
    }

    function obtenirNomCategorie(report) {
        return report.category ? report.category.name : 'Sans catégorie';
    }

    function obtenirTexteLieu(report) {
        if (report.location_name) {
            return report.location_name;
        }
        if (report.location) {
            return report.location;
        }
        if (report.latitude && report.longitude) {
            return `${Number(report.latitude).toFixed(4)}, ${Number(report.longitude).toFixed(4)}`;
        }
        return 'Localisation non disponible';
    }

    function construireNomLieu(data) {//data est from nominatim
        if (!data) {
            return '';
        }
        if (data.address) {
            let address = data.address;
            let morceaux = [
                address.road,
                address.suburb,
                address.neighbourhood,
                address.village,
                address.town,
                address.city,
                address.state
            ].filter(Boolean);//supprimer les valeur null/vide (true)

            if (morceaux.length > 0) {
                return morceaux.slice(0, 3).join(', ');
            }
        }

        return data.display_name || '';//display_name =>contient ladresse complet returne pas nominatim 
    }

    async function chercherLieu(lat, lng) {
        let cle = `${Number(lat).toFixed(5)},${Number(lng).toFixed(5)}`;
        if (cacheLieux[cle]) {
            return cacheLieux[cle];
        }
        try {
            let reponse = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}&accept-language=fr`);

            if (!reponse.ok) {
                cacheLieux[cle] = `${Number(lat).toFixed(4)}, ${Number(lng).toFixed(4)}`;
                return cacheLieux[cle];
            }

            let data = await reponse.json();
            let lieu = construireNomLieu(data);

            cacheLieux[cle] = lieu || `${Number(lat).toFixed(4)}, ${Number(lng).toFixed(4)}`;
            return cacheLieux[cle];
        } catch (error) {
            cacheLieux[cle] = `${Number(lat).toFixed(4)}, ${Number(lng).toFixed(4)}`;
            return cacheLieux[cle];
        }
    }

    async function remplirLieuxReports(reports) {
        await Promise.all(reports.map(async function (report) {//attent que toutes les search de lieux soient termine
            if (!report.latitude || !report.longitude) {
                return;
            }
            //en stock temporairemetn dans 
            report.location_name = await chercherLieu(report.latitude, report.longitude);
        }));
    }

    function obtenirImageReport(report) {
        if (report.images && report.images.length > 0) {
            return `/storage/${report.images[0].image_path}`;
        }

        return 'imgs/Rectangle 18.png';
    }

    function utilisateurPeutGererReport(report) {
        return estAdmin || Number(report.user_id) === Number(idUtilisateur);
    }

    function utilisateurPeutSupprimerReport(report) {
        if (!utilisateurPeutGererReport(report)) {
            return false;
        }
        if (estAdmin) {
            return true;
        }
        return report.status === 'pending';
    }

    function obtenirNomService(report) {
        return report.service_name || '';      
    }

    function obtenirNomAuteur(report) {
        return report.user ? report.user.name : 'Utilisateur';
    }

    function creerBlocInvitation(actionTexte) {
        return `
            <div class="rounded-2xl bg-slate-50 p-4 text-sm text-gray-600">
                <p class="font-medium text-black">Connexion requise</p>
                <p class="mt-2 leading-6">Connectez-vous pour ${actionTexte}.</p>
                <div class="mt-3 flex gap-2">
                    <a href="${urlConnexion}" class="rounded-xl border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-white hover:text-blue-600">
                        Connexion
                    </a>
                    <a href="${urlInscription}" class="rounded-xl bg-blue-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-blue-700" style="color: white;">
                        Inscription
                    </a>
                </div>
            </div>
        `;
    }

    function creerBlocStatutPopup(report) {
        if (!estAdmin) {
            return `
                <div class="border-t border-gray-200 pt-3">
                    <p class="text-sm text-gray-600">
                        <span class="font-semibold text-gray-700">Statut :</span> ${obtenirNomStatut(report.status)}
                    </p>
                </div>
            `;
        }

        return `
            <div class="space-y-3 border-t border-gray-200 pt-3">
                <p class="text-sm font-semibold text-gray-700">Modifier le statut</p>
                <div class="flex flex-col gap-2 sm:flex-row">
                    <select id="champ-statut-${report.id}" class="h-11 flex-1 rounded-xl border border-gray-300 px-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="pending" ${report.status === 'pending' ? 'selected' : ''}>En attente</option>
                        <option value="in_progress" ${report.status === 'in_progress' ? 'selected' : ''}>En cours</option>
                        <option value="resolved" ${report.status === 'resolved' ? 'selected' : ''}>Résolu</option>
                    </select>
                    <button type="button" id="bouton-statut-${report.id}" class="inline-flex h-11 items-center justify-center rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-black">
                        Mettre à jour
                    </button>
                </div>
                <p class="text-xs text-gray-500">
                    Statut actuel : <span id="texte-statut-${report.id}">${obtenirNomStatut(report.status)}</span>
                </p>
            </div>
        `;
    }
    function creerBlocServicePopup(report) {
        let nomService = obtenirNomService(report);

        if (!estAdmin && !nomService) {
            return '';
        }

        if (!estAdmin) {
            return `
                <div class="border-t border-gray-200 pt-3">
                    <p class="text-sm text-gray-600">
                        <span class="font-semibold text-gray-700">Service :</span> ${nomService}
                    </p>
                </div>
            `;
        }

        let options = '<option value="">Aucun service</option>';

        servicesMemorises.forEach(function (service) {
            let selected = Number(report.service_id) === Number(service.id) ? 'selected' : '';
            options += `<option value="${service.id}" ${selected}>${service.name}</option>`;
        });

        return `
            <div class="space-y-3 border-t border-gray-200 pt-3">
                <p class="text-sm font-semibold text-gray-700">Service assigné</p>
                <div class="flex flex-col gap-2 sm:flex-row">
                    <select id="champ-service-report-${report.id}" class="h-11 flex-1 rounded-xl border border-gray-300 px-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        ${options}
                    </select>
                    <button type="button" id="bouton-service-report-${report.id}" class="inline-flex h-11 items-center justify-center rounded-xl bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-black">
                        Enregistrer
                    </button>
                </div>
                <p class="text-xs text-gray-500">
                    Service actuel : <span id="texte-service-report-${report.id}">${nomService || 'Aucun'}</span>
                </p>
            </div>
        `;
    }

    function creerBlocVotePopup(report) {
        if (!estConnecte) {
            return `
                <div class="border-t border-gray-200 pt-3">
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-sm font-semibold text-gray-700">Votes</span>
                        <span id="compteur-vote-${report.id}" class="text-sm font-semibold text-gray-700">0</span>
                    </div>
                    <div class="mt-3">
                        ${creerBlocInvitation('voter sur un signalement')}
                    </div>
                </div>
            `;
        }

        return `
            <div class="flex items-center justify-between border-t border-gray-200 pt-3">
                <button type="button"
                    id="bouton-vote-${report.id}"
                    class="inline-flex h-11 items-center justify-center rounded-xl bg-blue-600 px-4 text-sm font-medium text-white transition hover:bg-blue-700"
                >
                    Valider
                </button>
                <span id="compteur-vote-${report.id}" class="text-sm font-semibold text-gray-700">0</span>
            </div>
        `;
    }

    function creerBlocCommentairesPopup(report) {
        let formulaireCommentaire = creerBlocInvitation('ajouter un commentaire');

        if (estConnecte) {
            formulaireCommentaire = `
                <form id="formulaire-commentaire-${report.id}" class="space-y-3">
                    <textarea
                        name="content"
                        placeholder="Ajouter un commentaire..."
                        class="min-h-[90px] w-full rounded-xl border border-gray-300 px-3 py-2 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    ></textarea>
                    <button
                        type="submit"
                        class="inline-flex h-11 w-full items-center justify-center rounded-xl bg-blue-600 text-sm font-medium text-white transition hover:bg-blue-700"
                    >
                        Envoyer
                    </button>
                </form>
            `;
        }

        return `
            <div class="border-t border-gray-200 pt-3">
                <h4 class="mb-2 text-sm font-semibold text-gray-900">Commentaires</h4>
                <div id="liste-commentaires-${report.id}" class="max-h-[170px] space-y-2 overflow-y-auto pr-1 text-sm text-gray-600">
                    Chargement des commentaires...
                </div>
                <div class="mt-3">
                    ${formulaireCommentaire}
                </div>
            </div>
        `;
    }

    function creerBlocSuppressionPopup(report){
        if (!utilisateurPeutSupprimerReport(report)) {
            return '';
        }

        return `
            <div class="border-t border-gray-200 pt-3">
                <button
                    type="button"
                    id="bouton-supprimer-report-${report.id}"
                    class="inline-flex h-11 w-full items-center justify-center rounded-xl bg-red-500 text-sm font-medium text-white transition hover:bg-red-600"
                >
                    Supprimer ce signalement
                </button>
            </div>
        `;
    }

    function construirePopupReport(report) {
        return `
            <div class="max-h-[68vh] w-[340px] overflow-y-auto p-5 text-sm text-gray-700">
                <div class="space-y-4">
                    <div class="overflow-hidden rounded-2xl">
                        <img src="${obtenirImageReport(report)}" alt="report" class="h-[190px] w-full object-cover">
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-lg font-bold text-gray-900 leading-6">${report.title}</h3>
                        <p class="text-sm leading-6 text-gray-600">${report.description}</p>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        ${obtenirBadgeStatut(report.status)}
                    </div>

                    <div class="space-y-1 text-xs text-gray-500">
                        <p><span class="font-semibold text-gray-700">Publié par :</span> ${obtenirNomAuteur(report)}</p>
                        <p><span class="font-semibold text-gray-700">Date :</span> ${formaterDate(report.created_at)}</p>
                        <p><span class="font-semibold text-gray-700">Lieu :</span> ${obtenirTexteLieu(report)}</p>
                    </div>

                    ${creerBlocServicePopup(report)}
                    ${creerBlocStatutPopup(report)}
                    ${creerBlocVotePopup(report)}
                    ${creerBlocCommentairesPopup(report)}
                    ${creerBlocSuppressionPopup(report)}
                </div>
            </div>
        `;
    }

    function construireCarteReport(report) {
        return `
            <div class="overflow-hidden rounded-[28px] border border-gray-100 bg-white shadow-sm">
                <div>
                    <img src="${obtenirImageReport(report)}" alt="report" class="h-[190px] w-full object-cover">
                </div>
                <div class="space-y-4 p-5">
                    <div class="flex items-center justify-between gap-3">
                        ${obtenirBadgeStatut(report.status)}
                        <p class="text-[14px] text-gray-500">${formaterDate(report.created_at)}</p>
                    </div>

                    <button
                        type="button"
                        id="titre-report-${report.id}"
                        class="titre-report text-left text-[18px] font-bold text-black transition hover:text-blue-600"
                    >
                        ${report.title}
                    </button>

                    <p class="text-[15px] leading-6 text-gray-600">
                        ${report.description}
                    </p>

                    <p class="text-[14px] text-gray-500">
                        Publié par : ${obtenirNomAuteur(report)}
                    </p>

                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-2 text-[14px] text-gray-500">
                            <span><img src="imgs/location.svg" alt="" class="h-4 w-4"></span>
                            <p>${obtenirTexteLieu(report)}</p>
                        </div>
                        
                    </div>
                </div>
            </div>
        `;
    }

    async function chargerCategories() {
        if (categoriesMemorisees.length > 0) {
            return categoriesMemorisees;
        }

        let reponse = await fetch('/categories');
        let data = await reponse.json();

        categoriesMemorisees = data.data || [];
        return categoriesMemorisees;
    }

    async function chargerServices() {
        let reponse = await fetch('/services', {
            headers: {
                'Accept': 'application/json'
            }
        });

        servicesMemorises = await reponse.json();
        // afficherListeServicesAdmin();
        return servicesMemorises;
    }

    async function remplirSelectsCategories() {
        let categories = await chargerCategories();
        let champCategorie = document.getElementById('champCategorie');
        let champCategoriePopup = document.getElementById('champ-categorie-popup');

        if (champCategorie && champCategorie.options.length === 1) {
            categories.forEach(function (categorie) {
                let option = document.createElement('option');
                option.value = categorie.id;
                option.textContent = categorie.name;
                champCategorie.appendChild(option);
            });
        }

        if (champCategoriePopup) {
            champCategoriePopup.innerHTML = '<option value="">Choisir une catégorie</option>';

            categories.forEach(function (categorie) {
                let option = document.createElement('option');
                option.value = categorie.id;
                option.textContent = categorie.name;
                champCategoriePopup.appendChild(option);
            });
        }
    }

    async function chargerReports() {
        let reponse = await fetch('/reports', {
            headers: {
                'Accept': 'application/json'
            }
        });

        let reports = await reponse.json();
        await remplirLieuxReports(reports);
        tousLesReports = reports;


        marqueursReports = {};
        coucheMarqueurs.clearLayers();

        reports.forEach(function (report) {
            // let marqueur = L.marker([Number(report.latitude), Number(report.longitude)])
            let marqueur = L.marker(
                [Number(report.latitude), Number(report.longitude)],
                { icon: getIconByStatus(report.status) }
            )
            .addTo(coucheMarqueurs)
            .bindPopup(construirePopupReport(report));

            marqueursReports[report.id] = marqueur;

            marqueur.on('popupopen', function () {
                chargerCommentaires(report.id);
                chargerVotes(report.id);
            });
        });

        afficherListeReports(tousLesReports);
    }

    function afficherListeReports(reports) {
        let listeReports = document.getElementById('listeReports');
        listeReports.innerHTML = '';

        if (reports.length === 0) {
            listeReports.innerHTML = `
                <div class="rounded-[28px] border border-gray-100 bg-white p-6 text-center text-sm text-gray-500">
                    Aucun signalement trouvé.
                </div>
            `;
            return;
        }

        reports.forEach(function (report) {
            let bloc = document.createElement('div');
            bloc.innerHTML = construireCarteReport(report);
            listeReports.appendChild(bloc);
        });
    }

    function appliquerFiltres() {
        let motCle = document.getElementById('champRecherche').value.toLowerCase().trim();
        let statut = document.getElementById('champStatut').value;
        let categorieId = document.getElementById('champCategorie').value;
        let zoneFiltres = document.getElementById('zoneFiltres');
        let listeReports = document.getElementById('listeReports');

        let reportsFiltres = tousLesReports.filter(function (report) {
            let titre = (report.title || '').toLowerCase();
            let description = (report.description || '').toLowerCase();
            let okMotCle = titre.includes(motCle) || description.includes(motCle);
            let okStatut = statut === '' || report.status === statut;
            let okCategorie = categorieId === '' || String(report.category_id) === String(categorieId);

            return okMotCle && okStatut && okCategorie;
        });

        afficherListeReports(reportsFiltres);

        if (listeReports) {
            listeReports.scrollTop = 0;
        }

        if (zoneFiltres) {
            zoneFiltres.classList.add('hidden');
        }

        afficherResumeFiltres(`${reportsFiltres.length} signalement(s) trouvé(s).`);

        setTimeout(function () {
            carte.invalidateSize();
        }, 100);
    }

    function reinitialiserFiltres() {
        document.getElementById('champRecherche').value = '';
        document.getElementById('champStatut').value = '';
        document.getElementById('champCategorie').value = '';
        afficherListeReports(tousLesReports);
        afficherResumeFiltres('', false);
        carte.setView([31.63, -8.00], 7);

        setTimeout(function () {
            carte.invalidateSize();
        }, 100);
    }

    function activerFiltres() {
        if (!document.getElementById('boutonRechercher') || !document.getElementById('boutonReinitialiser')) {
            return;
        }

        document.getElementById('boutonRechercher').addEventListener('click', appliquerFiltres);
        document.getElementById('boutonReinitialiser').addEventListener('click', reinitialiserFiltres);
    }

    function afficherReportSurCarte(reportId) {
        if (!carte) {
            return;
        }
        let marqueur = marqueursReports[reportId];

        if (!marqueur) {
            return;
        }

        carte.invalidateSize();
        carte.setView(marqueur.getLatLng(), 16);
        marqueur.openPopup();
    }

    function activerClicTitresReports() {
        document.addEventListener('click', function (e) {
            let boutonTitre = e.target.closest('.titre-report');

            if (!boutonTitre) {
                return;
            }
            let reportId = boutonTitre.id.replace('titre-report-', '');
            afficherReportSurCarte(reportId);
        });
    }

    function ouvrirPopupVisiteur(lat, lng) {
        if (!carte) {
            return;
        }

        let contenu = `
            <div class="w-[340px] p-5">
                <div class="space-y-4">
                    <div>
                        <h3 class="text-xl font-bold text-black">Connexion requise</h3>
                        <p class="mt-2 text-sm leading-6 text-gray-500">
                            Vous consultez actuellement la carte en mode visiteur. Connectez-vous pour créer un signalement à cet endroit.
                        </p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4 text-sm text-gray-600">
                        <p class="font-medium text-black">Position sélectionnée</p>
                        <p class="mt-2">${lat.toFixed(4)}, ${lng.toFixed(4)}</p>
                    </div>

                    <div class="flex gap-3">
                        <a href="${urlConnexion}" class="flex-1 rounded-2xl border border-gray-200 px-4 py-3 text-center text-sm font-medium text-gray-700 transition hover:bg-white hover:text-blue-600">
                            Connexion
                        </a>
                        <a href="${urlInscription}" class="flex-1 rounded-2xl bg-blue-600 px-4 py-3 text-center text-sm font-medium text-white transition hover:bg-blue-700" style="color:white;">
                            Inscription
                        </a>
                    </div>
                </div>
            </div>
        `;

        L.popup()
            .setLatLng([lat, lng])
            .setContent(contenu)
            .openOn(carte);
    }

    
</script>
@endpush
@endif