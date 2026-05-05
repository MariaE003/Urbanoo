<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - Urbanoo</title>
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
                    <p class="text-sm font-medium uppercase tracking-[0.2em] text-blue-600">Récupération</p>
                    <h1 class="mt-4 text-4xl font-bold text-black md:text-5xl">Mot de passe oublié</h1>
                    <p class="mt-4 text-lg leading-8 text-gray-600">
                        Entrez votre adresse email et nous vous enverrons un lien pour choisir un nouveau mot de passe.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-[28px] border border-gray-100 bg-white p-5 shadow-sm">
                        <p class="text-base font-semibold text-black">Étape 1</p>
                        <p class="mt-2 text-sm leading-6 text-gray-500">
                            Saisissez l’email associé à votre compte.
                        </p>
                    </div>

                    <div class="rounded-[28px] border border-blue-100 bg-blue-50 p-5 shadow-sm">
                        <p class="text-base font-semibold text-black">Étape 2</p>
                        <p class="mt-2 text-sm leading-6 text-gray-500">
                            Ouvrez le lien reçu par email pour réinitialiser votre mot de passe.
                        </p>
                    </div>
                </div>
            </div>

            <div class="w-full rounded-[32px] bg-white px-8 py-10 shadow-sm md:px-12">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-black">Recevoir un lien</h2>
                    <p class="mt-2 text-sm leading-6 text-gray-500">
                        Le lien de réinitialisation sera envoyé à votre adresse email.
                    </p>
                </div>

                @if (session('status'))
                    <div class="mb-5 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-gray-700">
                            Adresse email
                        </label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            placeholder="vous@exemple.com"
                            class="h-12 w-full rounded-2xl border border-gray-300 px-4 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button
                            type="submit"
                            class="inline-flex h-12 items-center justify-center rounded-2xl bg-blue-600 px-6 text-sm font-medium text-white transition hover:bg-blue-700"
                        >
                            Envoyer le lien
                        </button>

                        <a
                            href="{{ route('login') }}"
                            class="inline-flex h-12 items-center justify-center rounded-2xl border border-gray-300 px-6 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                        >
                            Retour connexion
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
