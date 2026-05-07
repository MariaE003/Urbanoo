<?php $__env->startSection('title', 'Ajouter une catégorie'); ?>

<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-6xl space-y-8">
    <div>
        <div class="rounded-[28px] border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
            <form action="<?php echo e(route('admin.categories.store')); ?>" method="POST" class="space-y-6">
                <?php echo csrf_field(); ?>
                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-gray-700">Nom</label>
                    <input type="text" id="name" name="name" value="<?php echo e(old('name')); ?>" class="h-12 w-full rounded-2xl border border-gray-300 px-4 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ex: Voirie">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-500"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="description" class="mb-2 block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description" rows="6" class="w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Décrivez le rôle de cette catégorie...">
                        <?php echo e(old('description')); ?>

                    </textarea>
                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-500"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <button type="submit"
                            class="inline-flex h-12 items-center justify-center rounded-2xl bg-blue-600 px-6 text-sm font-medium text-white transition hover:bg-blue-700">
                        Enregistrer
                    </button>

                    <a href="<?php echo e(route('admin.categories.index')); ?>" class="inline-flex h-12 items-center justify-center rounded-2xl border border-gray-300 px-6 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                        Retour
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Apache24\htdocs\Urbanoo\resources\views/admin/categories/create.blade.php ENDPATH**/ ?>