

<?php $__env->startSection('title', 'Dashboard Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">

    <!-- Title -->
    <div>
        <h1 class="text-3xl font-bold text-black">Dashboard Admin</h1>
        <p class="mt-2 text-gray-600 text-lg">
            Suivez les statistiques et les signalements récents de la plateforme.
        </p>
    </div>

    <!-- Stats cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
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
            <h2 class="mt-3 text-4xl font-bold text-black"><?php echo e($statis['total_categories']); ?>

            </h2>
        </div>

        <div class="bg-white rounded-[28px] shadow-sm p-6 border border-gray-100">
            <p class="text-gray-500 text-sm">Signalements 3 derniers jours</p>
            <h2 class="mt-3 text-4xl font-bold text-black"><?php echo e($statis['last_3_days_reports']); ?></h2>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- charjs -->
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
        <!-- Top voted reports -->
        <div class="bg-white rounded-[28px] shadow-sm p-6 border border-gray-100">
            <h2 class="text-2xl font-bold text-black mb-6">Les plus votés</h2>

            <div class="space-y-4">
                <?php $__currentLoopData = $top; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="border border-gray-100 rounded-2xl p-4">
                    <h3 class="text-base font-bold text-black"><?php echo e($report->title); ?></h3>
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
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Apache24\htdocs\Urbanoo\resources\views/adminDashboard.blade.php ENDPATH**/ ?>