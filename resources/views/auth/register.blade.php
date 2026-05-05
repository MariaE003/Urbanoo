<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Urbanoo</title>
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
                    <p class="text-sm font-medium uppercase tracking-[0.2em] text-blue-600">Inscription</p>
                    <h1 class="mt-4 text-4xl font-bold text-black md:text-5xl">Créez votre espace sur Urbanoo</h1>
                    <p class="mt-4 text-lg leading-8 text-gray-600">
                        Rejoignez la plateforme pour signaler les incidents, suivre leur état et participer à une ville plus réactive.
                    </p>
                </div>

            </div>

            <div class="w-full rounded-[32px] bg-white px-8 py-10 shadow-sm md:px-12">
                <a
                    href="{{ url('/auth/google') }}"
                    class="mb-5 inline-flex h-14 w-full items-center justify-center gap-3 rounded-[18px] border border-gray-300 bg-white text-base font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="h-5 w-5">
                        <path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3C33.6 32.7 29.2 36 24 36c-6.6 0-12-5.4-12-12S17.4 12 24 12c3 0 5.7 1.1 7.8 3l5.7-5.7C34 6.1 29.3 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.2-.1-2.3-.4-3.5z"/>
                        <path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.7 15 18.9 12 24 12c3 0 5.7 1.1 7.8 3l5.7-5.7C34 6.1 29.3 4 24 4c-7.7 0-14.4 4.3-17.7 10.7z"/>
                        <path fill="#4CAF50" d="M24 44c5.2 0 9.9-2 13.4-5.2l-6.2-5.2C29.2 35.1 26.7 36 24 36c-5.2 0-9.6-3.3-11.3-8l-6.5 5C9.5 39.6 16.2 44 24 44z"/>
                        <path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.3-2.2 4.2-4.1 5.6l.1-.1 6.2 5.2C37.1 38.4 44 33 44 24c0-1.2-.1-2.3-.4-3.5z"/>
                    </svg>
                    Continuer avec Google
                </a>

                <div class="mb-5 flex items-center gap-3">
                    <div class="h-px flex-1 bg-gray-200"></div>
                    <span class="text-sm text-gray-400">ou</span>
                    <div class="h-px flex-1 bg-gray-200"></div>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="mb-2 block text-xl font-medium text-black">
                            Nom complet
                        </label>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="Entrez votre nom"
                            class="h-14 w-full rounded-[18px] border border-gray-300 px-4 text-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="mb-2 block text-xl font-medium text-black">
                            Type de compte
                        </label>
                        <select
                            id="role"
                            name="role"
                            required
                            class="h-14 w-full rounded-[18px] border border-gray-300 px-4 text-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">Choisir un rôle</option>
                            <option value="citizen" {{ old('role') == 'citizen' ? 'selected' : '' }}>Citoyen</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="mb-2 block text-xl font-medium text-black">
                            Adresse email
                        </label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="username"
                            placeholder="vous@exemple.com"
                            class="h-14 w-full rounded-[18px] border border-gray-300 px-4 text-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-xl font-medium text-black">
                            Mot de passe
                        </label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            placeholder="********"
                            class="h-14 w-full rounded-[18px] border border-gray-300 px-4 text-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 block text-xl font-medium text-black">
                            Confirmer le mot de passe
                        </label>
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="********"
                            class="h-14 w-full rounded-[18px] border border-gray-300 px-4 text-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="h-14 w-full rounded-[18px] bg-blue-600 text-xl font-semibold text-white transition hover:bg-blue-700"
                    >
                        S'inscrire
                    </button>

                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-blue-600">
                            Vous avez déjà un compte ?
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
