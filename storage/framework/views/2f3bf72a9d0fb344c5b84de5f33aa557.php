<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-[1280px] space-y-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-black">Services</h1>
            <p class="mt-1 text-sm text-gray-500">Gérez les services et voyez les reports par service.</p>
        </div>
        <a href="<?php echo e(route('home')); ?>" class="inline-flex h-11 items-center justify-center rounded-2xl border border-gray-300 px-5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
            Retour à la carte
        </a>
    </div>
    <div class="grid gap-6 xl:grid-cols-[380px_minmax(0,1fr)] xl:items-start">
        <section class="rounded-[28px] border border-gray-100 bg-white p-5 shadow-sm xl:sticky xl:top-6">
            <div class="space-y-4">
                <div>
                    <h3 class="text-lg font-semibold text-black">Gestion des services</h3>
                    <p class="mt-1 text-sm text-gray-500">Ajouter, modifier ou supprimer un service.</p>
                </div>

                <div class="flex gap-3">
                    <input id="champNouveauService" type="text" placeholder="Ex: Service voirie" class="h-11 flex-1 rounded-2xl border border-gray-300 px-4 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button id="boutonAjouterService" type="button" class="h-11 rounded-2xl bg-slate-900 px-5 text-sm font-medium text-white transition hover:bg-black" >
                        Ajouter
                    </button>
                </div>
                <div id="listeServicesAdmin" class="max-h-[calc(100vh-18rem)] space-y-3 overflow-y-auto pr-1"></div>
            </div>
        </section>

        <section class="rounded-[28px] border border-gray-100 bg-white p-5 shadow-sm xl:min-h-[calc(100vh-6rem)] xl:max-h-[calc(100vh-6rem)] xl:overflow-hidden">
            <div class="space-y-4">
                <div>
                    <h3 class="text-lg font-semibold text-black">Reports par service</h3>
                    <p class="mt-1 text-sm text-gray-500">Les reports assignes et non assignes.</p>
                </div>

                <div id="listeReportsParService" class="max-h-[calc(100vh-13rem)] space-y-4 overflow-y-auto pr-1"></div>
            </div>
        </section>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    let servicesPageServices = [];
    let servicesPageReports = [];

    function spDate(dateTexte) {
        return new Date(dateTexte).toLocaleDateString('fr-FR');
    }

    function spLieu(report) {
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

    function spBadgeStatut(statut) {
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

    async function spChargerServices() {
        let reponse = await fetch('/services', {
            headers: {
                'Accept': 'application/json'
            }
        });

        servicesPageServices = await reponse.json();
        spAfficherServices();
    }

    async function spChargerReports() {
        let reponse = await fetch('/reports', {
            headers: {
                'Accept': 'application/json'
            }
        });

        servicesPageReports = await reponse.json();
        spAfficherReportsParService();
    }

    function spAfficherServices() {
        let liste = document.getElementById('listeServicesAdmin');

        if (!liste) {
            return;
        }

        if (servicesPageServices.length === 0) {
            liste.innerHTML = `
                <div class="rounded-2xl bg-slate-50 px-4 py-3 text-sm text-gray-500">
                    Aucun service pour le moment.
                </div>
            `;
            return;
        }

        liste.innerHTML = servicesPageServices.map(function (service) {
            return `
                <div class="rounded-2xl border border-gray-200 p-3">
                    <div class="flex flex-col gap-3">
                        <input
                            id="champ-service-admin-${service.id}"
                            type="text"
                            value="${service.name}"
                            class="h-11 w-full rounded-2xl border border-gray-300 px-4 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        <div class="flex gap-2">
                            <button
                                type="button"
                                id="bouton-modifier-service-${service.id}"
                                class="h-10 rounded-2xl bg-blue-600 px-4 text-sm font-medium text-white transition hover:bg-blue-700"
                            >
                                Modifier
                            </button>
                            <button
                                type="button"
                                id="bouton-supprimer-service-${service.id}"
                                class="h-10 rounded-2xl bg-red-500 px-4 text-sm font-medium text-white transition hover:bg-red-600"
                            >
                                Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    function spBlocReport(report) {
        return `
            <div class="rounded-2xl border border-gray-200 bg-slate-50 p-4">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="font-semibold text-gray-900">${report.title}</p>
                        <p class="mt-1 text-sm text-gray-600">${report.description}</p>
                    </div>
                    ${spBadgeStatut(report.status)}
                </div>
                <div class="mt-3 text-xs text-gray-500">
                    <p>Date : ${spDate(report.created_at)}</p>
                    <p>Lieu : ${spLieu(report)}</p>
                </div>
            </div>
        `;
    }

    function spBlocService(titre, reports) {
        let contenu = reports.length === 0
            ? '<p class="text-sm text-gray-500">Aucun report.</p>'
            : reports.map(spBlocReport).join('');

        return `
            <div class="rounded-[24px] border border-gray-100 bg-white p-5">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <h4 class="text-lg font-semibold text-black">${titre}</h4>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">${reports.length} report(s)</span>
                </div>
                <div class="space-y-3">
                    ${contenu}
                </div>
            </div>
        `;
    }

    function spAfficherReportsParService() {
        let liste = document.getElementById('listeReportsParService');

        if (!liste) {
            return;
        }

        let blocs = servicesPageServices.map(function (service) {
            let reports = servicesPageReports.filter(function (report) {
                return Number(report.service_id) === Number(service.id);
            });

            return spBlocService(service.name, reports);
        });

        let sansService = servicesPageReports.filter(function (report) {
            return !report.service_id;
        });

        blocs.push(spBlocService('Sans service', sansService));
        liste.innerHTML = blocs.join('');
    }

    async function spAjouterService() {
        let champ = document.getElementById('champNouveauService');

        if (!champ) {
            return;
        }

        let nom = champ.value.trim();

        if (!nom) {
            msg('Service', 'veuillez ecrire un nom de service.');
            return;
        }

        let reponse = await fetch('/admin/services', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                name: nom
            })
        });

        let data = await reponse.json();

        if (!reponse.ok) {
            msg('Erreur', data.message || 'Erreur pendant la création du service');
            return;
        }

        champ.value = '';
        notif('Service ajouté.', 'succes');
        await spChargerServices();
        await spChargerReports();
    }

    async function spModifierService(serviceId) {
        let champ = document.getElementById(`champ-service-admin-${serviceId}`);

        if (!champ) {
            return;
        }

        let nom = champ.value.trim();

        if (!nom) {
            msg('Service', 'Veuillez écrire un nom de service.');
            return;
        }

        let reponse = await fetch(`/admin/services/${serviceId}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                name: nom
            })
        });

        let data = await reponse.json();

        if (!reponse.ok) {
            msg('Erreur', data.message || 'Erreur pendant la modification du service');
            return;
        }

        notif('Service modifié.', 'succes');
        await spChargerServices();
        await spChargerReports();
    }

    async function spSupprimerService(serviceId) {
        let reponse = await fetch(`/admin/services/${serviceId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        let data = await reponse.json();

        if (!reponse.ok) {
            msg('Erreur', data.message || 'Erreur pendant la suppression du service');
            return;
        }

        notif('Service supprimé.', 'succes');
        await spChargerServices();
        await spChargerReports();
    }

    function spActions() {
        document.addEventListener('click', function (e) {
            let boutonAjouter = e.target.closest('#boutonAjouterService');
            if (boutonAjouter) {
                spAjouterService();
                return;
            }

            let boutonModifier = e.target.closest('[id^="bouton-modifier-service-"]');
            if (boutonModifier) {
                let serviceId = boutonModifier.id.replace('bouton-modifier-service-', '');
                spModifierService(serviceId);
                return;
            }

            let boutonSupprimer = e.target.closest('[id^="bouton-supprimer-service-"]');
            if (boutonSupprimer) {
                let serviceId = boutonSupprimer.id.replace('bouton-supprimer-service-', '');
                spSupprimerService(serviceId);
            }
        });
    }

    async function spInit() {
        spActions();
        await spChargerServices();
        await spChargerReports();
    }

    spInit();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Apache24\htdocs\Urbanoo\resources\views/admin/services/index.blade.php ENDPATH**/ ?>