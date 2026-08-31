<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestion des catégories
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="font-semibold text-gray-800 mb-3">Ajouter une catégorie</h3>
                <form method="POST" action="{{ route('admin.categories.store') }}" class="flex gap-2">
                    @csrf
                    <input type="text" name="name" placeholder="Nom de la catégorie" class="flex-1 rounded-md border-gray-300 shadow-sm" required>
                    <select name="parent_id" class="rounded-md border-gray-300 shadow-sm">
                        <option value="">Catégorie principale</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">Sous-catégorie de : {{ $category->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Ajouter
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Catégories existantes</h3>
                @foreach ($categories as $category)
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm font-medium text-gray-800">{{ $category->name }}</span>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Supprimer cette catégorie ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 hover:underline">Supprimer</button>
                        </form>
                    </div>
                    @foreach ($category->children as $child)
                        <div class="flex justify-between items-center py-2 pl-6 border-b">
                            <span class="text-sm text-gray-600">↳ {{ $child->name }}</span>
                            <form action="{{ route('admin.categories.destroy', $child) }}" method="POST" onsubmit="return confirm('Supprimer cette catégorie ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-600 hover:underline">Supprimer</button>
                            </form>
                        </div>
                    @endforeach
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>