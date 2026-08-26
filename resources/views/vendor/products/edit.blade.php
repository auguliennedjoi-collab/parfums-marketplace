<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Modifier le produit
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-6">

                @if ($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="h-32 w-32 object-cover rounded mb-4">
                @endif

                <form method="POST" action="{{ route('vendor.products.update', $product) }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nom du produit</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catégorie</label>
                        <select name="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $product->description) }}</textarea>
                        @error('description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Prix (FCFA)</label>
                            <input type="number" name="price" value="{{ old('price', $product->price) }}" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @error('price') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Stock</label>
                            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @error('stock') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Statut</label>
                        <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Actif</option>
                            <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inactif</option>
                        </select>
                        @error('status') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Changer l'image (optionnel)</label>
                        <input type="file" name="image" accept="image/*" class="mt-1 block w-full">
                        @error('image') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-2 pt-4">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Mettre à jour
                        </button>
                        <a href="{{ route('vendor.products.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                            Annuler
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>