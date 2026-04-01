@extends('layouts.admin')

@section('title', 'Manage Staff')

@section('content')
<div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-white">Barbers & Staff</h2>
        <button onclick="openModal('addModal')" class="bg-amber-500 hover:bg-amber-600 text-zinc-900 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
            <i class='bx bx-plus'></i> Add Staff
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

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($staffMembers as $member)
        <div class="border border-zinc-800 bg-zinc-950 rounded-xl p-5 flex items-start gap-4 hover:border-amber-500/50 transition-colors relative">
            <div class="w-16 h-16 rounded-full bg-zinc-800 overflow-hidden flex-shrink-0">
                @if($member->photo_url)
                    <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-zinc-500"><i class='bx bxs-user text-2xl'></i></div>
                @endif
            </div>
            <div class="flex-1 w-full">
                <div class="flex justify-between items-start">
                    <div class="w-full truncate pr-6">
                        <h3 class="text-white font-semibold flex items-center gap-2">
                            {{ $member->name }}
                            @if(!$member->is_active)
                                <span class="text-[10px] uppercase bg-red-500/10 text-red-500 px-2 py-0.5 rounded leading-none mt-0.5">Inactive</span>
                            @endif
                        </h3>
                        <p class="text-amber-500 text-xs">{{ $member->specialization ?? 'Barber' }}</p>
                    </div>
                    
                    <div class="absolute top-4 right-4">
                        <form action="{{ route('admin.staff.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this staff member?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-zinc-500 hover:text-red-500 transition-colors"><i class='bx bx-trash'></i></button>
                        </form>
                    </div>
                </div>
                <p class="text-zinc-500 text-sm mt-2 line-clamp-2" title="{{ $member->bio }}">{{ $member->bio ?? 'No bio provided.' }}</p>
                <div class="mt-4 flex gap-2">
                    <button onclick='openEditModal(@json($member))' class="text-xs bg-zinc-800 hover:bg-zinc-700 text-white px-3 py-1.5 rounded transition-colors flex-1 text-center">Edit</button>
                    <button onclick='openScheduleModal(@json($member))' class="text-xs bg-zinc-800 hover:bg-zinc-700 text-white px-3 py-1.5 rounded transition-colors flex-1 text-center">Schedule</button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center text-zinc-500 border border-dashed border-zinc-800 rounded-xl">
            <i class='bx bx-user-x text-4xl mb-2'></i>
            <p>No staff records found.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Add Staff Modal -->
<div id="addModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm">
    <div class="bg-zinc-900 border border-zinc-700 rounded-xl w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-white">Add Staff Member</h3>
            <button onclick="closeModal('addModal')" class="text-zinc-400 hover:text-white"><i class='bx bx-x text-2xl'></i></button>
        </div>
        <form action="{{ route('admin.staff.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-zinc-400 mb-1">Full Name</label>
                    <input type="text" name="name" required class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-2 text-white outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-sm text-zinc-400 mb-1">Specialization</label>
                    <input type="text" name="specialization" placeholder="e.g. Master Barber, Hair Stylist" required class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-2 text-white outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-sm text-zinc-400 mb-1">Bio</label>
                    <textarea name="bio" rows="3" class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-2 text-white outline-none focus:border-amber-500"></textarea>
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

<!-- Edit Staff Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm">
    <div class="bg-zinc-900 border border-zinc-700 rounded-xl w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-white">Edit Staff Member</h3>
            <button onclick="closeModal('editModal')" class="text-zinc-400 hover:text-white"><i class='bx bx-x text-2xl'></i></button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-zinc-400 mb-1">Full Name</label>
                    <input type="text" name="name" id="edit_name" required class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-2 text-white outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-sm text-zinc-400 mb-1">Specialization</label>
                    <input type="text" name="specialization" id="edit_specialization" required class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-2 text-white outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-sm text-zinc-400 mb-1">Bio</label>
                    <textarea name="bio" id="edit_bio" rows="3" class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-2 text-white outline-none focus:border-amber-500"></textarea>
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

<!-- Schedule Staff Modal -->
<div id="scheduleModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center backdrop-blur-sm p-4">
    <div class="bg-zinc-900 border border-zinc-700 rounded-xl w-full max-w-2xl flex flex-col max-h-[90vh]">
        <div class="flex justify-between items-center p-5 border-b border-zinc-800 shrink-0">
            <h3 class="text-lg font-bold text-white">Manage Schedule for <span id="schedule_staff_name" class="text-amber-500"></span></h3>
            <button type="button" onclick="closeModal('scheduleModal')" class="text-zinc-400 hover:text-white"><i class='bx bx-x text-2xl'></i></button>
        </div>
        
        <form id="scheduleForm" method="POST" class="flex flex-col overflow-hidden h-full">
            @csrf
            @method('PUT')
            
            <div id="schedule_loading" class="text-center py-12 text-zinc-500 shrink-0">
                <i class='bx bx-loader-alt animate-spin text-3xl mb-2'></i>
                <p>Loading schedule...</p>
            </div>

            <div id="schedule_content" class="hidden flex flex-col flex-1 min-h-0">
                <!-- Scrollable Body -->
                <div class="p-5 overflow-y-auto flex-1 min-h-0">
                    <div class="grid grid-cols-12 gap-2 text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-3 px-2">
                        <div class="col-span-3">Day</div>
                        <div class="col-span-4">Open Time</div>
                        <div class="col-span-4">Close Time</div>
                        <div class="col-span-1 text-center">Off?</div>
                    </div>
                    
                    <!-- JS will inject the 7 rows here -->
                    <div id="schedule_rows" class="space-y-3"></div>
                </div>

                <!-- Fixed Footer -->
                <div class="p-4 flex justify-end gap-3 border-t border-zinc-800 shrink-0 bg-zinc-900 rounded-b-xl mt-auto">
                    <button type="button" onclick="closeModal('scheduleModal')" class="px-4 py-2 text-zinc-400 hover:text-white">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-amber-500 text-zinc-900 font-medium rounded-lg hover:bg-amber-600">Save Schedule</button>
                </div>
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
    function openEditModal(staff) {
        document.getElementById('editForm').action = '/admin/staff/' + staff.id;
        document.getElementById('edit_name').value = staff.name;
        document.getElementById('edit_specialization').value = staff.specialization || '';
        document.getElementById('edit_bio').value = staff.bio || '';
        document.getElementById('edit_is_active').checked = staff.is_active ? true : false;
        openModal('editModal');
    }

    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    async function openScheduleModal(staff) {
        document.getElementById('schedule_staff_name').innerText = staff.name;
        document.getElementById('scheduleForm').action = '/admin/staff/' + staff.id + '/schedule';
        
        document.getElementById('schedule_loading').classList.remove('hidden');
        document.getElementById('schedule_content').classList.add('hidden');
        openModal('scheduleModal');

        try {
            const res = await fetch(`/admin/staff/${staff.id}/schedule`);
            const data = await res.json();
            
            let html = '';
            data.forEach((sched, i) => {
                html += `
                <div class="grid grid-cols-12 gap-2 items-center bg-zinc-950 p-2 rounded-lg border border-zinc-800">
                    <div class="col-span-3 font-medium text-white text-sm pl-2">
                        <input type="hidden" name="schedules[${i}][day_of_week]" value="${sched.day_of_week}">
                        ${days[sched.day_of_week]}
                    </div>
                    <div class="col-span-4">
                        <input type="time" name="schedules[${i}][open_time]" value="${sched.open_time}" required class="w-full bg-zinc-800 border border-zinc-700 rounded-md px-2 py-1.5 text-white outline-none focus:border-amber-500 text-sm">
                    </div>
                    <div class="col-span-4">
                        <input type="time" name="schedules[${i}][close_time]" value="${sched.close_time}" required class="w-full bg-zinc-800 border border-zinc-700 rounded-md px-2 py-1.5 text-white outline-none focus:border-amber-500 text-sm">
                    </div>
                    <div class="col-span-1 flex justify-center">
                        <input type="checkbox" name="schedules[${i}][is_off]" value="1" ${sched.is_off ? 'checked' : ''} class="w-4 h-4 text-amber-500 rounded bg-zinc-800 border-zinc-700">
                    </div>
                </div>
                `;
            });
            
            document.getElementById('schedule_rows').innerHTML = html;
            document.getElementById('schedule_loading').classList.add('hidden');
            document.getElementById('schedule_content').classList.remove('hidden');
        } catch (e) {
            document.getElementById('schedule_loading').innerHTML = '<p class="text-red-400">Failed to load schedule. Try again.</p>';
        }
    }
</script>
@endsection
