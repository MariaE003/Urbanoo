<?php $__env->startSection('title', 'Dashboard Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">

    <?php if(session('status')): ?>
        <div class="rounded-2xl border border-green-100 bg-green-50 px-4 py-3 text-sm text-green-700">
            <?php echo e(session('status')); ?>

        </div>
    <?php endif; ?>

    <div>
        <h1 class="text-3xl font-bold text-black">Dashboard Admin</h1>
        <p class="mt-2 text-gray-600 text-lg">
            Suivez les statistiques et les signalements récents de la plateforme.
        </p>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
        <div class="bg-white rounded-[28px] shadow-sm p-6 border border-gray-100">
            <p class="text-gray-500 text-sm">Total signalements</p>
            <h2 class="mt-3 text-4xl font-bold text-black"><?php echo e($statis['total_reports']); ?></h2>
        </div>
        <div class="bg-white rounded-[28px] shadow-sm p-6 border border-gray-100">
            <p class="text-gray-500 text-sm">Citoyens</p>
            <h2 class="mt-3 text-4xl font-bold text-black"><?php echo e($statis['total_users']); ?></h2>
        </div>

        <div class="bg-white rounded-[28px] shadow-sm p-6 border border-gray-100">
            <p class="text-gray-500 text-sm">Catégories</p>
            <h2 class="mt-3 text-4xl font-bold text-black"><?php echo e($statis['total_categories']); ?></h2>
        </div>

        <div class="bg-white rounded-[28px] shadow-sm p-6 border border-gray-100">
            <p class="text-gray-500 text-sm">Signalements 3 derniers jours</p>
            <h2 class="mt-3 text-4xl font-bold text-black"><?php echo e($statis['last_3_days_reports']); ?></h2>
        </div>
    </div>

    <div class="bg-white rounded-[28px] shadow-sm p-6 border border-gray-100">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-black">Activer / Désactiver les citoyens</h2>
            <p class="mt-1 text-sm text-gray-500">Gestion simple des comptes citoyens.</p>
        </div>

        <div class="mb-5">
            <input
                id="champRechercheUsers"
                type="text"
                placeholder="Rechercher un user par nom ou email..."
                class="h-12 w-full rounded-2xl border border-gray-300 px-4 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
        </div>

        <div id="listeUsersAdmin" class="space-y-4">
            <?php $__empty_1 = true; $__currentLoopData = $citizens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $citizen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="carte-user-admin flex flex-col gap-4 rounded-2xl border border-gray-100 p-4 md:flex-row md:items-center md:justify-between" data-name="<?php echo e(strtolower($citizen->name)); ?>" data-email="<?php echo e(strtolower($citizen->email)); ?>">
                    <div>
                        <h3 class="text-base font-bold text-black"><?php echo e($citizen->name); ?></h3>
                        <p class="mt-1 text-sm text-gray-500"><?php echo e($citizen->email); ?></p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <?php if($citizen->is_active): ?>
                            <span class="rounded-full bg-green-100 px-4 py-2 text-center text-sm font-medium text-green-700">
                                Actif
                            </span>
                        <?php else: ?>
                            <span class="rounded-full bg-red-100 px-4 py-2 text-center text-sm font-medium text-red-700">
                                Désactivé
                            </span>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo e(route('admin.users.toggleStatus', $citizen->id)); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>

                            <?php if($citizen->is_active): ?>
                                <button type="submit" class="rounded-2xl bg-red-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-red-600">
                                    Désactiver
                                </button>
                            <?php else: ?>
                                <button type="submit" class="rounded-2xl bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                                    Activer
                                </button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-sm text-gray-500">Aucun citoyen trouvé.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="bg-white rounded-[28px] shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-black">Répartition des signalements</h2>
                    <p class="text-sm text-gray-500 mt-1">Nombre de reports par statut</p>
                </div>
            </div>
            <div class="w-full h-[350px]">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-[28px] shadow-sm p-6 border border-gray-100">
            <h2 class="text-2xl font-bold text-black mb-6">Signalements des 3 derniers jours</h2>

            <div id="lastReportsContainer" class="max-h-[420px] space-y-4 overflow-y-auto pr-2">
                <p class="text-sm text-gray-500">Chargement...</p>
            </div>
        </div>

        <div class="bg-white rounded-[28px] shadow-sm p-6 border border-gray-100">
            <h2 class="text-2xl font-bold text-black mb-6">Les plus votés</h2>

            <div class="space-y-4">
                <?php $__currentLoopData = $top; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="border border-gray-100 rounded-2xl p-4">
                    <a href="<?php echo e(route('home', ['report' => $report->id])); ?>" class="text-base font-bold text-black transition hover:text-blue-600">
                        <?php echo e($report->title); ?>

                    </a>
                    <div class="mt-2 flex items-center justify-between text-sm text-gray-500">
                        <span><?php echo e($report->category->name); ?></span>
                        <span class="font-semibold text-blue-600"><?php echo e($report->votes_count); ?></span>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chart=document.getElementById('statusChart');
    new Chart(chart,{
        type:'doughnut',
        data :{
            labels:['en attente','en cours','resoulus'],
            datasets:[{
                data:[
                    <?php echo e($statis['pending_reports']); ?>,
                    <?php echo e($statis['in_progress_reports']); ?>,
                    <?php echo e($statis['resolved_reports']); ?>

                ],
                backgroundColor:['#fcd34d','#60a5fa','#4ade80'],
                borderWidth:0
            }]
        },
        options:{
            responsive:true,
            maintainAspectRatio:false,
            plugins:{
                legend:{
                    position:'bottom'
                }
            }
        }
    })

    async function loadLastReports() {
        try {
            let res = await fetch('/reports/last', {
                headers: {
                    'Accept': 'application/json'
                }
            });

            let reports = await res.json();
            let container = document.getElementById('lastReportsContainer');

            if (!container) return;

            if (!res.ok) {
                container.innerHTML = `<p class="text-red-500">Erreur lors du chargement.</p>`;
                return;
            }

            if (reports.length === 0) {
                container.innerHTML = `<p class="text-sm text-gray-500">Aucun signalement trouvé.</p>`;
                return;
            }

            container.innerHTML = '';

            reports.forEach(report => {
                let imagePath = report.images && report.images.length > 0
                    ? `/storage/${report.images[0].image_path}`
                    : 'imgs/Rectangle 18.png';

                let categoryName = report.category ? report.category.name : 'Sans catégorie';
                let reportDate = report.created_at
                    ? new Date(report.created_at).toLocaleDateString('fr-FR')
                    : '';

                let statusBadge = '';

                if (report.status === 'pending') {
                    statusBadge = `<span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-medium">En attente</span>`;
                } else if (report.status === 'in_progress') {
                    statusBadge = `<span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-medium">En cours</span>`;
                } else if (report.status === 'resolved') {
                    statusBadge = `<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">Résolu</span>`;
                } else {
                    statusBadge = `<span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-medium">${report.status}</span>`;
                }

                let card = document.createElement('div');
                card.className = 'border border-gray-100 rounded-2xl p-4 flex items-center gap-4';

                card.innerHTML = `
                    <div class="w-20 h-20 rounded-2xl overflow-hidden bg-gray-100 shrink-0">
                        <img src="${imagePath}" alt="report" class="w-full h-full object-cover">
                    </div>

                    <div class="flex-1 min-w-0">
                        <a href="/?report=${report.id}" class="block text-base font-bold text-black truncate transition hover:text-blue-600">
                            ${report.title}
                        </a>

                        <div class="mt-2 flex items-center justify-between text-sm text-gray-500">
                            <span>${categoryName}</span>
                            <span>${reportDate}</span>
                        </div>

                        <div class="mt-3">
                            ${statusBadge}
                        </div>
                    </div>
                `;

                container.appendChild(card);
            });

        } catch (error) {
            console.error(error);

            let container = document.getElementById('lastReportsContainer');
            if (container) {
                container.innerHTML = `<p class="text-red-500">Erreur serveur.</p>`;
            }
        }
    }

    function initRechercheUsers() {
        let champRechercheUsers = document.getElementById('champRechercheUsers');
        let cartes = document.querySelectorAll('.carte-user-admin');

        if (!champRechercheUsers || cartes.length === 0) {
            return;
        }

        champRechercheUsers.addEventListener('input', function () {
            let mot = champRechercheUsers.value.toLowerCase().trim();

            cartes.forEach(function (carte) {
                let nom = carte.dataset.name || '';
                let email = carte.dataset.email || '';
                let visible = nom.includes(mot) || email.includes(mot);
                carte.style.display = visible ? '' : 'none';
            });
        });
    }

    loadLastReports();
    initRechercheUsers();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Apache24\htdocs\Urbanoo\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>