<!DOCTYPE html>
@php
    $estConnecte = auth()->check();
    $estCitoyen = $estConnecte && auth()->user()->role === 'citizen';
    $estAdmin = $estConnecte && auth()->user()->role === 'admin';
@endphp
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Urbanoo</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-[#f5f5f5] text-gray-900">

    <header class="border-b border-gray-200 bg-white/95 backdrop-blur">
        <div class="mx-auto flex max-w-[1400px] items-center gap-4 px-4 py-4 sm:px-6">
            <a href="{{ route('home') }}" class="shrink-0">
                <img src="{{ asset('imgs/logo.png') }}"
                    alt="Logo Urbanoo"
                    class="h-14 w-14 rounded-2xl object-cover sm:h-16 sm:w-16">
            </a>

            <nav class="hidden items-center gap-3 lg:flex">
                <a href="{{ route('home') }}" class="rounded-full px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 hover:text-blue-600">
                    Accueil
                </a>

                @if ($estCitoyen)
                    <a href="{{ route('citizen.dashboard') }}" class="rounded-full px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 hover:text-blue-600">
                        Mes signalements
                    </a>
                @endif

                @if ($estAdmin)
                    <a href="{{ route('admin.dashboard') }}" class="rounded-full px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 hover:text-blue-600">
                        Dashboard
                    </a>

                    <a href="{{ route('admin.categories.index') }}" class="rounded-full px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 hover:text-blue-600">
                        Catégories
                    </a>
                @endif
            </nav>

            <div class="ml-auto hidden items-center gap-3 lg:flex">
                @if ($estCitoyen)
                    <div class="relative">
                        <button id="boutonNotifications"
                            type="button"
                            class="relative flex h-11 w-11 items-center justify-center rounded-2xl border border-gray-200 bg-white text-gray-700 transition hover:bg-gray-50" >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0018 9.75V9a6 6 0 10-12 0v.75a8.967 8.967 0 00-2.311 6.022c1.733.64 3.56 1.082 5.454 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                            <span  id="compteurNotifications"
                                class="hidden absolute -right-2 -top-2 min-w-[22px] rounded-full bg-red-500 px-1 text-center text-xs font-bold text-white" >
                                0
                            </span>
                        </button>

                        <div id="menuNotifications" class="absolute right-0 z-[9999] mt-3 hidden w-[360px] overflow-hidden rounded-[24px] border border-gray-100 bg-white shadow-lg" >
                            <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3">
                                <h3 class="text-base font-bold text-black">Notifications</h3>
                                <button id="boutonToutLire" type="button" class="text-sm text-blue-600 transition hover:text-blue-700">
                                    Tout lire
                                </button>
                            </div>
                            <div id="listeNotifications" class="max-h-[420px] overflow-y-auto">
                                <div class="p-4 text-sm text-gray-500">Chargement...</div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($estConnecte)
                    <a href="{{ route('profile.edit') }}" class="rounded-2xl border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 hover:text-blue-600">
                        Profil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-2xl bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                            Déconnexion
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="rounded-2xl border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 hover:text-blue-600">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}" class="rounded-2xl bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                        Créer un compte
                    </a>
                @endif
            </div>

            <button id="boutonMenuMobile" type="button"class="ml-auto inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-gray-200 text-gray-700 transition hover:bg-gray-50 lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <div id="menuMobile" class="hidden border-t border-gray-100 bg-white lg:hidden">
            <div class="mx-auto flex max-w-[1400px] flex-col gap-2 px-4 py-4 sm:px-6">
                <a href="{{ route('home') }}" class="rounded-2xl px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 hover:text-blue-600">
                    Accueil
                </a>

                @if ($estCitoyen)
                    <a href="{{ route('citizen.dashboard') }}" class="rounded-2xl px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 hover:text-blue-600">
                        Mes signalements
                    </a>
                @endif

                @if ($estAdmin)
                    <a href="{{ route('admin.dashboard') }}" class="rounded-2xl px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 hover:text-blue-600">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="rounded-2xl px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 hover:text-blue-600">
                        Catégories
                    </a>
                @endif

                <div class="mt-2 flex flex-col gap-2 border-t border-gray-100 pt-4">
                    @if ($estConnecte)
                        <a href="{{ route('profile.edit') }}" class="rounded-2xl border border-gray-200 px-4 py-3 text-center text-sm font-medium text-gray-700 transition hover:bg-gray-50 hover:text-blue-600">
                            Profil
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full rounded-2xl bg-blue-600 px-4 py-3 text-sm font-medium text-white transition hover:bg-blue-700">
                                Déconnexion
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="rounded-2xl border border-gray-200 px-4 py-3 text-center text-sm font-medium text-gray-700 transition hover:bg-gray-50 hover:text-blue-600">
                            Connexion
                        </a>

                        <a href="{{ route('register') }}" class="rounded-2xl bg-blue-600 px-4 py-3 text-center text-sm font-medium text-white transition hover:bg-blue-700">
                            Créer un compte
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <main class="mx-auto px-4 py-6 sm:px-6">
        @yield('content')
    </main>

    <div id="zoneNotification" class="pointer-events-none fixed right-4 top-4 z-[10000] flex w-full max-w-sm flex-col gap-3"></div>

    <div id="fondDialogue" class="fixed inset-0 z-[10001] hidden bg-black/40 p-4">
        <div class="flex min-h-full items-center justify-center">
            <div class="w-full max-w-md rounded-[28px] bg-white p-6 shadow-2xl">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p id="titreDialogue" class="text-xl font-bold text-black">Message</p>
                        <p id="texteDialogue" class="mt-3 text-sm leading-7 text-gray-600"></p>
                    </div>
                </div>

                <div id="actionsDialogue" class="mt-6 flex justify-end gap-3">
                    <button id="boutonAnnulerDialogue" type="button" class="hidden rounded-2xl border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                        Annuler
                    </button>
                    <button id="boutonValiderDialogue" type="button" class="rounded-2xl bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let actionValidationDialogue = null;
        //alert perso
        function notif(message, typeNotification = 'info') {
            let zoneNotification = document.getElementById('zoneNotification');

            if (!zoneNotification) {
                return;
            }

            let couleurs = {
                succes: 'border-green-100 bg-green-50 text-green-700',
                erreur: 'border-red-100 bg-red-50 text-red-700',
                info: 'border-blue-100 bg-blue-50 text-blue-700'
            };

            let bloc = document.createElement('div');
            bloc.className = `pointer-events-auto rounded-[24px] border px-4 py-4 shadow-lg ${couleurs[typeNotification] || couleurs.info}`;
            bloc.innerHTML = `
                <div class="flex items-start justify-between gap-4">
                    <p class="text-sm font-medium leading-6">${message}</p>
                    <button type="button" class="bouton-fermer-notification rounded-xl px-2 py-1 text-xs font-semibold">
                        Fermer
                    </button>
                </div>
            `;

            zoneNotification.appendChild(bloc);

            let boutonFermer = bloc.querySelector('.bouton-fermer-notification');
            if (boutonFermer) {
                boutonFermer.addEventListener('click', function () {
                    bloc.remove();
                });
            }

            setTimeout(function () {
                bloc.remove();
            }, 3500);
        }
        //close  une boite
        function fermerDlg() {
            let fondDialogue = document.getElementById('fondDialogue');
            let boutonAnnulerDialogue = document.getElementById('boutonAnnulerDialogue');
            if (!fondDialogue) {
                return;
            }
            fondDialogue.classList.add('hidden');
            actionValidationDialogue = null;

            if (boutonAnnulerDialogue) {
                boutonAnnulerDialogue.classList.add('hidden');
            }
        }
        //affiche la biote personalise
        function msg(titre, texte, typeBouton = 'OK') {
            let fondDialogue = document.getElementById('fondDialogue');
            let titreDialogue = document.getElementById('titreDialogue');
            let texteDialogue = document.getElementById('texteDialogue');
            let boutonValiderDialogue = document.getElementById('boutonValiderDialogue');
            let boutonAnnulerDialogue = document.getElementById('boutonAnnulerDialogue');

            if (!fondDialogue || !titreDialogue || !texteDialogue || !boutonValiderDialogue || !boutonAnnulerDialogue) {
                return;
            }

            titreDialogue.textContent = titre;
            texteDialogue.textContent = texte;
            boutonValiderDialogue.textContent = typeBouton;
            boutonAnnulerDialogue.classList.add('hidden');
            fondDialogue.classList.remove('hidden');
        }
        //afficher la boite (confirme())
        function confirmDlg(titre, texte, actionValidation) {
            let fondDialogue = document.getElementById('fondDialogue');
            let titreDialogue = document.getElementById('titreDialogue');
            let texteDialogue = document.getElementById('texteDialogue');
            let boutonValiderDialogue = document.getElementById('boutonValiderDialogue');
            let boutonAnnulerDialogue = document.getElementById('boutonAnnulerDialogue');

            if (!fondDialogue || !titreDialogue || !texteDialogue || !boutonValiderDialogue || !boutonAnnulerDialogue) {
                return;
            }

            actionValidationDialogue = actionValidation;
            titreDialogue.textContent = titre;
            texteDialogue.textContent = texte;
            boutonValiderDialogue.textContent = 'Confirmer';
            boutonAnnulerDialogue.classList.remove('hidden');
            fondDialogue.classList.remove('hidden');
        }
        // active le  burgerMenu
        function initMenu() {
            let boutonMenuMobile = document.getElementById('boutonMenuMobile');
            let menuMobile = document.getElementById('menuMobile');

            if (!boutonMenuMobile || !menuMobile) {
                return;
            }
            boutonMenuMobile.addEventListener('click', function () {
                menuMobile.classList.toggle('hidden');
            });
        }

        async function chargerNotifs() {
            let compteurNotifications = document.getElementById('compteurNotifications');
            let listeNotifications = document.getElementById('listeNotifications');
            if (!compteurNotifications || !listeNotifications) {
                return;
            }
            try {
                let reponseToutes = await fetch('/notifications/all', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                let notifications = await reponseToutes.json();

                let reponseNonLues = await fetch('/notifications/unread', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                let notificationsNonLues = await reponseNonLues.json();

                if (notificationsNonLues.length > 0) {
                    compteurNotifications.textContent = notificationsNonLues.length;
                    compteurNotifications.classList.remove('hidden');
                } else {
                    compteurNotifications.classList.add('hidden');
                }
                if (notifications.length === 0) {
                    listeNotifications.innerHTML = '<div class="p-4 text-sm text-gray-500">Aucune notification.</div>';
                    return;
                }
                listeNotifications.innerHTML = notifications.map(function (notification) {
                    let titre = notification.data.report_title || notification.data.title || 'Signalement';
                    let reportId = notification.data.report_id || '';
                    let estNonLue = notification.read_at === null;

                    return `
                        <button type="button" id="notification-${notification.id}" class="carte-notification w-full border-b border-gray-100 px-4 py-4 text-left transition hover:bg-gray-50 ${estNonLue ? 'bg-blue-50/40' : 'bg-white'}">
                            <span id="report-notification-${notification.id}" class="hidden">${reportId}</span>
                            <h4 class="text-sm font-semibold text-black">${titre}</h4>
                            <p class="mt-1 text-sm text-gray-600">${notification.data.message || ''}</p>
                        </button>
                    `;
                }).join('');
            } catch (error) {
                console.error(error);
            }
        }

        async function lireNotif(notificationId) {
            try {
                let reponse = await fetch(`/notifications/${notificationId}/read`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                if (!reponse.ok) {
                    return;
                }

                chargerNotifs();
            } catch (error) {
                console.error(error);
            }
        }

        async function lireToutesNotifs() {
            try {
                let reponse = await fetch('/notifications/read-all', {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                if (!reponse.ok) {
                    return;
                }

                chargerNotifs();
            } catch (error) {
                console.error(error);
            }
        }

        function initNotifs(){
            let boutonNotifications = document.getElementById('boutonNotifications');
            let menuNotifications = document.getElementById('menuNotifications');
            let boutonToutLire = document.getElementById('boutonToutLire');

            if (!boutonNotifications || !menuNotifications) {
                return;
            }
            boutonNotifications.addEventListener('click', function () {
                menuNotifications.classList.toggle('hidden');
                chargerNotifs();
            });
            document.addEventListener('click', function (e) {
                //pour ferme la partie notif
                if (!e.target.closest('#boutonNotifications') && !e.target.closest('#menuNotifications')) {
                    menuNotifications.classList.add('hidden');
                }
                let carteNotification = e.target.closest('.carte-notification');
                if (!carteNotification) {
                    return;
                }

                let notificationId = carteNotification.id.replace('notification-', '');//pour recupere just id sans notif-
                let blocReportId = document.getElementById(`report-notification-${notificationId}`);
                let reportId = blocReportId ? blocReportId.textContent : '';

                if (notificationId) {
                    lireNotif(notificationId);
                }
                if (reportId) {
                    window.location.href = `/?report=${reportId}`;
                }
            });
            if (boutonToutLire) {
                boutonToutLire.addEventListener('click', function () {
                    lireToutesNotifs();
                });
            }
        }

        function initDlg() {
            let fondDialogue = document.getElementById('fondDialogue');
            let boutonAnnulerDialogue = document.getElementById('boutonAnnulerDialogue');
            let boutonValiderDialogue = document.getElementById('boutonValiderDialogue');

            if (!fondDialogue || !boutonAnnulerDialogue || !boutonValiderDialogue) {
                return;
            }
            boutonAnnulerDialogue.addEventListener('click', fermerDlg);

            fondDialogue.addEventListener('click', function (e) {
                if (e.target.id === 'fondDialogue') {
                    fermerDlg();
                }
            });

            boutonValiderDialogue.addEventListener('click', function () {
                let actionAExecuter = actionValidationDialogue;//contient l'action a faire si user confirme
                fermerDlg();

                if (typeof actionAExecuter === 'function') {
                    actionAExecuter();
                }
            });
        }
        // rendent les methodes en globale
        window.ouvrirNotification = notif;
        window.fermerDialogue = fermerDlg;
        window.ouvrirMessage = msg;
        window.ouvrirConfirmation = confirmDlg;
        window.activerMenuMobile = initMenu;
        window.chargerNotifications = chargerNotifs;
        window.marquerNotificationCommeLue = lireNotif;
        window.marquerToutesNotificationsCommeLues = lireToutesNotifs;
        window.activerNotifications = initNotifs;
        window.activerDialogueGlobal = initDlg;
        //apres chargament complete du html
        document.addEventListener('DOMContentLoaded', function () {
            initMenu();
            initNotifs();
            initDlg();
            chargerNotifs();
        });
    </script>
    @stack('scripts')
</body>
</html>