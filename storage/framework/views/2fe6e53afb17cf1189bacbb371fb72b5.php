

<?php $__env->startSection('title', 'Dashboard Citoyen'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">

    <!-- Title -->
    <div>
        <h1 class="text-3xl font-bold text-black">Mon espace citoyen</h1>
        <p class="mt-2 text-gray-600 text-lg">
            Suivez vos signalements et leur état d’avancement.
        </p>
    </div>

    <!-- Stats cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <div class="rounded-[28px] shadow-sm p-6 border border-slate-100 bg-gradient-to-br from-slate-50 to-white">
            <p class="text-slate-600 text-sm">Mes signalements</p>
            <h2 class="mt-3 text-4xl font-bold text-slate-800"><?php echo e($stats['total_reports']); ?></h2>
        </div>

        <div class="rounded-[28px] shadow-sm p-6 border border-yellow-100 bg-gradient-to-br from-yellow-50 to-white">
            <p class="text-yellow-700 text-sm">En attente</p>
            <h2 class="mt-3 text-4xl font-bold text-yellow-500"><?php echo e($stats['pending_reports']); ?></h2>
        </div>

        <div class="rounded-[28px] shadow-sm p-6 border border-blue-100 bg-gradient-to-br from-blue-50 to-white">
            <p class="text-blue-700 text-sm">En cours</p>
            <h2 class="mt-3 text-4xl font-bold text-blue-600"><?php echo e($stats['in_progress_reports']); ?></h2>
        </div>

        <div class="rounded-[28px] shadow-sm p-6 border border-green-100 bg-gradient-to-br from-green-50 to-white">
            <p class="text-green-700 text-sm">Résolus</p>
            <h2 class="mt-3 text-4xl font-bold text-green-600"><?php echo e($stats['resolved_reports']); ?></h2>
        </div>
    </div>

    <!-- Latest reports -->
    <div class="bg-white rounded-[28px] shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-black">Mes derniers signalements</h2>
                <p class="text-sm text-gray-500 mt-1">Aperçu rapide de vos signalements récents</p>
            </div>
        </div>

        <div class="space-y-4">
            <?php $__empty_1 = true; $__currentLoopData = $latestReports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(url('/?report=' . $report->id)); ?>" class="block border border-gray-100 rounded-2xl p-4 transition hover:shadow-sm hover:border-blue-200">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl overflow-hidden bg-gray-100 flex items-center justify-center">
                                <?php if($report->images && $report->images->count() > 0): ?>
                                    <img src="<?php echo e(asset('storage/' . $report->images->first()->image_path)); ?>" alt="image" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <span class="text-gray-400 text-xs">No img</span>
                                <?php endif; ?>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-black"><?php echo e($report->title); ?></h3>
                                <p class="text-sm text-gray-500">
                                    <?php echo e($report->category?->name ?? 'Sans catégorie'); ?> • <?php echo e($report->created_at->format('d/m/Y')); ?>

                                </p>
                            </div>
                        </div>

                        <div>
                            <?php if($report->status === 'pending'): ?>
                                <span class="bg-yellow-100 text-yellow-700 text-sm px-4 py-2 rounded-full">En attente</span>
                            <?php elseif($report->status === 'in_progress'): ?>
                                <span class="bg-blue-100 text-blue-700 text-sm px-4 py-2 rounded-full">En cours</span>
                            <?php elseif($report->status === 'resolved'): ?>
                                <span class="bg-green-100 text-green-700 text-sm px-4 py-2 rounded-full">Résolu</span>
                            <?php else: ?>
                                <span class="bg-gray-100 text-gray-700 text-sm px-4 py-2 rounded-full"><?php echo e($report->status); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <p class="mt-3 text-sm text-gray-600 leading-6">
                        <?php echo e($report->description); ?>

                    </p>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center text-gray-500 py-10">
                    Vous n’avez encore aucun signalement.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- All my reports -->
    <div class="bg-white rounded-[28px] shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-black">Tous mes signalements</h2>
                <p class="text-sm text-gray-500 mt-1">Liste complète de vos signalements</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-100 text-sm text-gray-500">
                        <th class="py-3">Titre</th>
                        <th class="py-3">Catégorie</th>
                        <th class="py-3">Date</th>
                        <th class="py-3">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $myReports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b border-gray-50 transition hover:bg-blue-50/50">
                            <td class="py-4 font-medium text-black">
                                <a href="<?php echo e(url('/?report=' . $report->id)); ?>" class="block hover:text-blue-600"><?php echo e($report->title); ?></a>
                            </td>
                            <td class="py-4 text-gray-600">
                                <a href="<?php echo e(url('/?report=' . $report->id)); ?>" class="block"><?php echo e($report->category?->name ?? 'Sans catégorie'); ?></a>
                            </td>
                            <td class="py-4 text-gray-600">
                                <a href="<?php echo e(url('/?report=' . $report->id)); ?>" class="block"><?php echo e($report->created_at->format('d/m/Y')); ?></a>
                            </td>
                            <td class="py-4">
                                <a href="<?php echo e(url('/?report=' . $report->id)); ?>" class="inline-block">
                                    <?php if($report->status === 'pending'): ?>
                                        <span class="bg-yellow-100 text-yellow-700 text-sm px-3 py-1 rounded-full">En attente</span>
                                    <?php elseif($report->status === 'in_progress'): ?>
                                        <span class="bg-blue-100 text-blue-700 text-sm px-3 py-1 rounded-full">En cours</span>
                                    <?php elseif($report->status === 'resolved'): ?>
                                        <span class="bg-green-100 text-green-700 text-sm px-3 py-1 rounded-full">Résolu</span>
                                    <?php else: ?>
                                        <span class="bg-gray-100 text-gray-700 text-sm px-3 py-1 rounded-full"><?php echo e($report->status); ?></span>
                                    <?php endif; ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-500">
                                Aucun signalement trouvé.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Apache24\htdocs\Urbanoo\resources\views/citizen/dashboard.blade.php ENDPATH**/ ?>