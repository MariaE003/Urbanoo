<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-[1400px] space-y-6">
    <div class="rounded-[30px] border border-gray-100 bg-white p-6 shadow-sm">
        <p class="text-sm font-medium uppercase tracking-[0.2em] text-blue-600">Mon compte</p>
        <h1 class="mt-2 text-3xl font-bold text-black md:text-4xl">Profil</h1>
        <p class="mt-3 max-w-3xl text-base leading-7 text-gray-600">
            Gérez vos informations personnelles, votre mot de passe et la sécurité de votre compte.
        </p>
    </div>

    <div class="grid gap-6 xl:grid-cols-[360px_minmax(0,1fr)]">
        <aside class="space-y-6">
            <div class="rounded-[28px] border border-gray-100 bg-gradient-to-br from-slate-50 to-white p-6 shadow-sm">
                <div class="space-y-5">
                    <div class="flex items-center justify-between">
                        <div class="rounded-full bg-blue-50 px-4 py-2 text-sm font-medium text-blue-600">
                            Espace sécurisé
                        </div>
                        <div class="rounded-full border border-gray-200 bg-white px-4 py-2 text-xs font-semibold text-gray-500">
                            <?php echo e($user->role === 'admin' ? 'Administrateur' : 'Citoyen'); ?>

                        </div>
                    </div>

                    <div class="flex flex-col items-center text-center">
                        <div id="avatarPreview" class="flex h-28 w-28 items-center justify-center overflow-hidden rounded-[28px] bg-blue-100 text-3xl font-bold text-blue-700 shadow-sm">
                            <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                        </div>

                        <h2 class="mt-4 text-2xl font-bold text-black"><?php echo e($user->name); ?></h2>
                        <p class="mt-2 text-sm text-gray-500"><?php echo e($user->email); ?></p>

                        <div class="mt-5 w-full rounded-[24px] border border-gray-100 bg-white p-4 text-left">
                            <p class="text-sm font-semibold text-black">Photo de profil</p>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Vous pouvez choisir une image pour l’aperçu visuel de votre profil.
                                Cette partie reste seulement côté front, sans changement backend.
                            </p>

                            <input id="champPhotoProfil" type="file" accept="image/*" class="hidden">

                            <div class="mt-4 flex gap-3">
                                <button id="boutonChoisirPhoto" type="button" class="rounded-2xl border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 hover:text-blue-600">
                                    Choisir une photo
                                </button>
                                <button id="boutonRetirerPhoto" type="button" class="rounded-2xl bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-black">
                                    Retirer
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[24px] border border-gray-100 bg-white p-4">
                        <p class="text-sm font-semibold text-black">Accès actuel</p>
                        <p class="mt-2 text-sm leading-6 text-gray-500">
                            <?php echo e($user->role === 'admin'
                                ? 'Vous gérez les catégories, le dashboard et les signalements.'
                                : 'Vous pouvez gérer votre profil et suivre vos propres signalements.'); ?>

                        </p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="space-y-6">
            <section class="rounded-[28px] border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-black">Informations personnelles</h2>
                    <p class="mt-2 text-sm leading-6 text-gray-500">
                        Mettez à jour votre nom et votre adresse email.
                    </p>
                </div>

                <?php if(session('status') === 'profile-updated'): ?>
                    <div class="mb-5 rounded-2xl border border-green-100 bg-green-50 px-4 py-3 text-sm text-green-700">
                        Vos informations ont été mises à jour.
                    </div>
                <?php endif; ?>

                <form id="formVerificationEmail" method="POST" action="<?php echo e(route('verification.send')); ?>">
                    <?php echo csrf_field(); ?>
                </form>

                <form method="POST" action="<?php echo e(route('profile.update')); ?>" class="space-y-5">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>

                    <div>
                        <label for="name" class="mb-2 block text-sm font-medium text-gray-700">Nom complet</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="<?php echo e(old('name', $user->name)); ?>"
                            required
                            autofocus
                            autocomplete="name"
                            class="h-12 w-full rounded-2xl border border-gray-300 px-4 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-gray-700">Adresse email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="<?php echo e(old('email', $user->email)); ?>"
                            required
                            autocomplete="username"
                            class="h-12 w-full rounded-2xl border border-gray-300 px-4 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        <?php if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail()): ?>
                            <div class="mt-4 rounded-2xl border border-yellow-100 bg-yellow-50 p-4">
                                <p class="text-sm leading-6 text-yellow-800">
                                    Votre adresse email n’est pas encore vérifiée.
                                </p>
                                <button form="formVerificationEmail" type="submit" class="mt-3 rounded-2xl border border-yellow-200 bg-white px-4 py-2 text-sm font-medium text-yellow-800 transition hover:bg-yellow-100">
                                    Renvoyer le lien de vérification
                                </button>

                                <?php if(session('status') === 'verification-link-sent'): ?>
                                    <p class="mt-3 text-sm text-green-700">
                                        Un nouveau lien de vérification a été envoyé.
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="rounded-2xl bg-blue-600 px-5 py-3 text-sm font-medium text-white transition hover:bg-blue-700">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </section>

            <section class="rounded-[28px] border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-black">Mot de passe</h2>
                    <p class="mt-2 text-sm leading-6 text-gray-500">
                        Choisissez un mot de passe fort pour mieux protéger votre compte.
                    </p>
                </div>

                <?php if(session('status') === 'password-updated'): ?>
                    <div class="mb-5 rounded-2xl border border-green-100 bg-green-50 px-4 py-3 text-sm text-green-700">
                        Votre mot de passe a été mis à jour.
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('password.update')); ?>" class="space-y-5">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div>
                        <label for="current_password" class="mb-2 block text-sm font-medium text-gray-700">Mot de passe actuel</label>
                        <input
                            id="current_password"
                            name="current_password"
                            type="password"
                            autocomplete="current-password"
                            class="h-12 w-full rounded-2xl border border-gray-300 px-4 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        <?php if($errors->updatePassword->has('current_password')): ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($errors->updatePassword->first('current_password')); ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="new-password"
                            class="h-12 w-full rounded-2xl border border-gray-300 px-4 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        <?php if($errors->updatePassword->has('password')): ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($errors->updatePassword->first('password')); ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            class="h-12 w-full rounded-2xl border border-gray-300 px-4 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        <?php if($errors->updatePassword->has('password_confirmation')): ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($errors->updatePassword->first('password_confirmation')); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="rounded-2xl bg-blue-600 px-5 py-3 text-sm font-medium text-white transition hover:bg-blue-700">
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </section>

            <section class="rounded-[28px] border border-red-100 bg-white p-6 shadow-sm sm:p-8">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-black">Supprimer le compte</h2>
                    <p class="mt-2 text-sm leading-6 text-gray-500">
                        Cette action est définitive. Veuillez confirmer avec votre mot de passe.
                    </p>
                </div>

                <form id="formSuppressionCompte" method="POST" action="<?php echo e(route('profile.destroy')); ?>" class="space-y-5">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>

                    <div>
                        <label for="mot_de_passe_suppression" class="mb-2 block text-sm font-medium text-gray-700">Mot de passe</label>
                        <input
                            id="mot_de_passe_suppression"
                            name="password"
                            type="password"
                            placeholder="Entrez votre mot de passe"
                            class="h-12 w-full rounded-2xl border border-gray-300 px-4 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-400"
                        >
                        <?php if($errors->userDeletion->has('password')): ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($errors->userDeletion->first('password')); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="flex justify-end">
                        <button id="boutonSuppressionCompte" type="button" class="rounded-2xl bg-red-500 px-5 py-3 text-sm font-medium text-white transition hover:bg-red-600">
                            Supprimer mon compte
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function activerPhotoProfilLocale() {
        let boutonChoisirPhoto = document.getElementById('boutonChoisirPhoto');
        let boutonRetirerPhoto = document.getElementById('boutonRetirerPhoto');
        let champPhotoProfil = document.getElementById('champPhotoProfil');
        let avatarPreview = document.getElementById('avatarPreview');
        let lettreInitiale = <?php echo json_encode(strtoupper(substr($user->name, 0, 1))) ?>;

        if (!boutonChoisirPhoto || !boutonRetirerPhoto || !champPhotoProfil || !avatarPreview) {
            return;
        }

        boutonChoisirPhoto.addEventListener('click', function () {
            champPhotoProfil.click();
        });

        boutonRetirerPhoto.addEventListener('click', function () {
            champPhotoProfil.value = '';
            avatarPreview.innerHTML = lettreInitiale;
            avatarPreview.className = 'flex h-28 w-28 items-center justify-center overflow-hidden rounded-[28px] bg-blue-100 text-3xl font-bold text-blue-700 shadow-sm';
        });

        champPhotoProfil.addEventListener('change', function () {
            let fichier = champPhotoProfil.files[0];

            if (!fichier) {
                return;
            }

            let lecteur = new FileReader();

            lecteur.onload = function (e) {
                avatarPreview.innerHTML = `<img src="${e.target.result}" alt="Photo de profil" class="h-full w-full object-cover">`;
                avatarPreview.className = 'h-28 w-28 overflow-hidden rounded-[28px] shadow-sm';
            };

            lecteur.readAsDataURL(fichier);
        });
    }

    function activerSuppressionCompte() {
        let boutonSuppressionCompte = document.getElementById('boutonSuppressionCompte');
        let formSuppressionCompte = document.getElementById('formSuppressionCompte');

        if (!boutonSuppressionCompte || !formSuppressionCompte) {
            return;
        }

        boutonSuppressionCompte.addEventListener('click', function () {
            ouvrirConfirmation(
                'Supprimer le compte',
                'Voulez-vous vraiment supprimer votre compte ? Cette action est définitive.',
                function () {
                    formSuppressionCompte.submit();
                }
            );
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        activerPhotoProfilLocale();
        activerSuppressionCompte();
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Apache24\htdocs\Urbanoo\resources\views/profile/edit.blade.php ENDPATH**/ ?>