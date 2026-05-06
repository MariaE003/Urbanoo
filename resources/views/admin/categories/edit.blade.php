@extends('partials.header')

@section('title', 'Modifier une catégorie')

@section('content')
<div class="mx-auto max-w-6xl space-y-8">

    <div>
        <div class="rounded-[28px] border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-gray-700">Nom</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" class="h-12 w-full rounded-2xl border border-gray-300 px-4 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nom de la catégorie">
                    @error('name')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="mb-2 block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description" rows="6" class="w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Décrivez cette catégorie...">
                        {{ old('description', $category->description) }}
                    </textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <button type="submit"
                            class="inline-flex h-12 items-center justify-center rounded-2xl bg-blue-600 px-6 text-sm font-medium text-white transition hover:bg-blue-700">
                        Mettre à jour
                    </button>

                    <a href="{{ route('admin.categories.index') }}" class="inline-flex h-12 items-center justify-center rounded-2xl border border-gray-300 px-6 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                        Retour
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
