<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestion des vendeurs
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Boutique</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Propriétaire</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($vendors as $vendor)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-800">{{ $vendor->boutique_name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-800">{{ $vendor->user->name }} ({{ $vendor->user->email }})</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        {{ $vendor->status === 'approved' ? 'bg-green-100 text-green-700' : ($vendor->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                        {{ ucfirst($vendor->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm space-x-2">
                                    @if ($vendor->status !== 'approved')
                                        <form action="{{ route('admin.vendors.approve', $vendor) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:underline">Approuver</button>
                                        </form>
                                    @endif
                                    @if ($vendor->status !== 'rejected')
                                        <form action="{{ route('admin.vendors.reject', $vendor) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-red-600 hover:underline">Rejeter</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    Aucun vendeur pour le moment.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $vendors->links() }}</div>

        </div>
    </div>
</x-app-layout>