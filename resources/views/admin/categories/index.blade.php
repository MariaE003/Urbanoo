@extends('partials.header')

@section('title', 'Gestion des catégories')

@section('content')
<div class="mx-auto max-w-[1400px] space-y-8">

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-black">Liste des catégories</h2>
            <p class="mt-1 text-sm text-gray-500">
                Retrouvez ici toutes les catégories déjà disponibles.
            </p>
        </div>

        <a href="{{ route('admin.categories.create') }}"
           class="inline-flex h-12 items-center justify-center rounded-2xl bg-blue-600 px-5 text-sm font-medium text-white transition hover:bg-blue-700">
            Ajouter une catégorie
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if($categories->count() > 0)
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @foreach($categories as $category)
                <div class="flex h-full flex-col rounded-[28px] border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-[20px] bg-blue-100 text-xl font-bold text-blue-600">
                            {{ strtoupper(substr($category->name, 0, 1)) }}
                        </div>

                        <div class="rounded-full border border-gray-200 bg-gray-50 px-3 py-1 text-xs font-semibold text-gray-500">
                            {{ $category->reports_count }} signalement(s)
                        </div>
                    </div>

                    <div class="mt-5 flex-1 space-y-3">
                        <h3 class="text-xl font-bold text-black">{{ $category->name }}</h3>
                        <p class="text-sm leading-7 text-gray-600">
                            {{ $category->description ?: 'Aucune description pour cette catégorie.' }}
                        </p>
                    </div>

                    <div class="mt-6 flex items-center gap-3">
                        <a href="{{ route('admin.categories.edit', $category->id) }}"
                           class="inline-flex h-12 flex-1 items-center justify-center rounded-xl border border-gray-300 px-5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                            Modifier
                        </a>

                        <form id="form-suppression-categorie-{{ $category->id }}" action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    id="bouton-suppression-categorie-{{ $category->id }}"
                                    class="inline-flex h-12 w-full items-center justify-center rounded-xl bg-red-500 px-5 text-sm font-medium text-white transition hover:bg-red-600">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="rounded-[28px] border border-dashed border-gray-300 bg-white px-6 py-14 text-center shadow-sm">
            <div class="mx-auto max-w-md">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-2xl font-bold text-slate-500">
                    C
                </div>
                <h3 class="mt-5 text-xl font-bold text-black">Aucune catégorie trouvée</h3>
                <p class="mt-2 text-sm leading-7 text-gray-500">
                    Commencez par créer votre première catégorie pour organiser les signalements.
                </p>
                <a href="{{ route('admin.categories.create') }}"
                   class="mt-6 inline-flex h-12 items-center justify-center rounded-2xl bg-blue-600 px-5 text-sm font-medium text-white transition hover:bg-blue-700">
                    Créer une catégorie
                </a>
            </div>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let boutonsSuppression = document.querySelectorAll('[id^="bouton-suppression-categorie-"]');

        boutonsSuppression.forEach(function (bouton) {
            bouton.addEventListener('click', function () {
                let categorieId = bouton.id.replace('bouton-suppression-categorie-', '');
                let formulaire = document.getElementById(`form-suppression-categorie-${categorieId}`);

                if (!formulaire) {
                    return;
                }

                ouvrirConfirmation(
                    'Supprimer la categorie',
                    'Voulez-vous vraiment supprimer cette categorie ?',
                    function () {
                        formulaire.submit();
                    }
                );
            });
        });
    });
</script>
@endpush
