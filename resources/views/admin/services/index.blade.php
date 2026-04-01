@extends('layouts.admin')

@section('title', 'Manage Services')

@section('content')
<div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-white">Services Catalog</h2>
        <button onclick="openModal('addModal')" class="bg-amber-500 hover:bg-amber-600 text-zinc-900 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
            <i class='bx bx-plus'></i> Add Service
        </button>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-500/10 border border-green-500/20 text-green-500 p-3 rounded">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-4 bg-red-500/10 border border-red-500/20 text-red-500 p-3 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-zinc-400 text-sm border-b border-zinc-800">
                    <th class="pb-3 font-medium">Name</th>
                    <th class="pb-3 font-medium">Category</th>
                    <th class="pb-3 font-medium">Price</th>
                    <th class="pb-3 font-medium">Duration</th>
                    <th class="pb-3 font-medium">Status</th>
                    <th class="pb-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800 text-sm">
                @forelse($services as $service)
                <tr class="hover:bg-zinc-800/30 transition-colors">
                    <td class="py-4 font-medium text-white">{{ $service->name }}</td>
                    <td class="py-4 text-zinc-300">
                        <span class="px-2 py-1 bg-zinc-800 rounded text-xs">{{ $service->category }}</span>
                    </td>
                    <td class="py-4 text-zinc-300">Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                    <td class="py-4 text-zinc-300">{{ $service->duration_minutes }}m</td>
                    <td class="py-4">
                        @if($service->is_active)
                            <span class="text-green-500 bg-green-500/10 px-2 py-1 rounded text-xs">Active</span>
                        @else
                            <span class="text-red-500 bg-red-500/10 px-2 py-1 rounded text-xs">Inactive</span>
                        @endif
                    </td>
                    <td class="py-4 text-right flex justify-end gap-2">
                        <button onclick='openEditModal(@json($service))' class="text-zinc-400 hover:text-amber-500 transition-colors mr-2"><i class='bx bx-edit text-lg'></i></button>
                        <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this service?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-zinc-400 hover:text-red-500 transition-colors"><i class='bx bx-trash text-lg'></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-zinc-500">No services found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm">
    <div class="bg-zinc-900 border border-zinc-700 rounded-xl w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-white">Add Service</h3>
            <button onclick="closeModal('addModal')" class="text-zinc-400 hover:text-white"><i class='bx bx-x text-2xl'></i></button>
        </div>
        <form action="{{ route('admin.services.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-zinc-400 mb-1">Name</label>
                    <input type="text" name="name" required class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-2 text-white outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-sm text-zinc-400 mb-1">Category</label>
                    <input type="text" name="category" required class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-2 text-white outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-sm text-zinc-400 mb-1">Description</label>
                    <textarea name="description" class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-2 text-white outline-none focus:border-amber-500"></textarea>
                </div>
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="block text-sm text-zinc-400 mb-1">Price (Rp)</label>
                        <input type="number" name="price" required class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-2 text-white outline-none focus:border-amber-500">
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm text-zinc-400 mb-1">Duration (Mins)</label>
                        <input type="number" name="duration_minutes" required class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-2 text-white outline-none focus:border-amber-500">
                    </div>
                </div>
                <div>
                    <label class="flex items-center gap-2 text-zinc-400">
                        <input type="checkbox" name="is_active" value="1" checked class="form-checkbox text-amber-500 rounded bg-zinc-800 border-zinc-700">
                        Is Active?
                    </label>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeModal('addModal')" class="px-4 py-2 text-zinc-400 hover:text-white">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-amber-500 text-zinc-900 font-medium rounded-lg hover:bg-amber-600">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm">
    <div class="bg-zinc-900 border border-zinc-700 rounded-xl w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-white">Edit Service</h3>
            <button onclick="closeModal('editModal')" class="text-zinc-400 hover:text-white"><i class='bx bx-x text-2xl'></i></button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-zinc-400 mb-1">Name</label>
                    <input type="text" name="name" id="edit_name" required class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-2 text-white outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-sm text-zinc-400 mb-1">Category</label>
                    <input type="text" name="category" id="edit_category" required class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-2 text-white outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-sm text-zinc-400 mb-1">Description</label>
                    <textarea name="description" id="edit_description" class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-2 text-white outline-none focus:border-amber-500"></textarea>
                </div>
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="block text-sm text-zinc-400 mb-1">Price (Rp)</label>
                        <input type="number" name="price" id="edit_price" required class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-2 text-white outline-none focus:border-amber-500">
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm text-zinc-400 mb-1">Duration (Mins)</label>
                        <input type="number" name="duration_minutes" id="edit_duration_minutes" required class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-2 text-white outline-none focus:border-amber-500">
                    </div>
                </div>
                <div>
                    <label class="flex items-center gap-2 text-zinc-400">
                        <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="form-checkbox text-amber-500 rounded bg-zinc-800 border-zinc-700">
                        Is Active?
                    </label>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 text-zinc-400 hover:text-white">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-amber-500 text-zinc-900 font-medium rounded-lg hover:bg-amber-600">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
    function openEditModal(service) {
        document.getElementById('editForm').action = '/admin/services/' + service.id;
        document.getElementById('edit_name').value = service.name;
        document.getElementById('edit_category').value = service.category;
        document.getElementById('edit_description').value = service.description || '';
        document.getElementById('edit_price').value = parseInt(service.price);
        document.getElementById('edit_duration_minutes').value = service.duration_minutes;
        document.getElementById('edit_is_active').checked = service.is_active ? true : false;
        openModal('editModal');
    }
</script>
@endsection
