<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe - Urbanoo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-[#f3f3f3] px-4 py-8">

    <div class="mx-auto flex min-h-[calc(100vh-4rem)] w-full max-w-6xl items-center">
        <div class="grid w-full gap-8 lg:grid-cols-[1.1fr_600px] lg:items-center">
            <div class="space-y-6">
                <div>
                    <a href="{{ route('home') }}" class="inline-flex items-center rounded-full border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:text-blue-600">
                        Retour à l'accueil
                    </a>
                </div>

                <div class="max-w-xl">
                    <p class="text-sm font-medium uppercase tracking-[0.2em] text-blue-600">Nouveau mot de passe</p>
                    <h1 class="mt-4 text-4xl font-bold text-black md:text-5xl">Réinitialiser votre accès</h1>
                    <p class="mt-4 text-lg leading-8 text-gray-600">
                        Choisissez un nouveau mot de passe pour sécuriser votre compte et vous reconnecter.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-[28px] border border-gray-100 bg-white p-5 shadow-sm">
                        <p class="text-base font-semibold text-black">Sécurité</p>
                        <p class="mt-2 text-sm leading-6 text-gray-500">
                            Utilisez un mot de passe unique et facile à retenir pour vous.
                        </p>
                    </div>

                    <div class="rounded-[28px] border border-blue-100 bg-blue-50 p-5 shadow-sm">
                        <p class="text-base font-semibold text-black">Conseil</p>
                        <p class="mt-2 text-sm leading-6 text-gray-500">
                            Ajoutez des lettres, chiffres et symboles pour un mot de passe plus fort.
                        </p>
                    </div>
                </div>
            </div>

            <div class="w-full rounded-[32px] bg-white px-8 py-10 shadow-sm md:px-12">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-black">Définir un nouveau mot de passe</h2>
                    <p class="mt-2 text-sm leading-6 text-gray-500">
                        Remplissez les champs ci-dessous pour finaliser la réinitialisation.
                    </p>
                </div>

                <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-gray-700">
                            Adresse email
                        </label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email', $request->email) }}"
                            required
                            autofocus
                            autocomplete="username"
                            class="h-12 w-full rounded-2xl border border-gray-300 px-4 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-gray-700">
                            Nouveau mot de passe
                        </label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            class="h-12 w-full rounded-2xl border border-gray-300 px-4 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-medium text-gray-700">
                            Confirmer le mot de passe
                        </label>
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            class="h-12 w-full rounded-2xl border border-gray-300 px-4 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="inline-flex h-12 w-full items-center justify-center rounded-2xl bg-blue-600 px-6 text-sm font-medium text-white transition hover:bg-blue-700"
                    >
                        Réinitialiser le mot de passe
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
