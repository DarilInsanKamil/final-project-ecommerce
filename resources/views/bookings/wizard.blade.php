@extends('layouts.app')

@section('title', 'Book Appointment - BarberKu')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-white mb-2">Book Your Appointment</h1>
        <p class="text-zinc-400">Complete the steps below to secure your slot.</p>
    </div>

    <!-- Wizard Container -->
    <div class="card p-8 relative overflow-hidden">
        
        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl border border-red-500/30 bg-red-500/10 text-red-500">
                <div class="font-bold mb-2 flex items-center gap-2"><i class='bx bx-error-circle'></i> Please fix the following errors:</div>
                <ul class="list-disc list-inside ml-5 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Interactive JS Wizard Form -->
        <form action="{{ route('booking.store') }}" method="POST" id="bookingForm" class="space-y-8">
            @csrf
            
            <!-- Step 1: Select Service -->
            <div id="step-1" class="wizard-step">
                <h2 class="text-xl font-semibold text-white border-b border-zinc-800 pb-4 mb-6"><span class="text-amber-500 mr-2">1.</span> Choose Service</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($services as $service)
                    <label class="relative cursor-pointer group">
                        <input type="radio" name="service_id" value="{{ $service->id }}" class="peer sr-only" required {{ request('service') == $service->id ? 'checked' : '' }}>
                        <div class="p-4 rounded-xl border-2 border-zinc-700 bg-zinc-900/50 hover:bg-zinc-800 peer-checked:border-amber-500 peer-checked:bg-amber-500/10 transition-all flex flex-col h-full">
                            <div class="font-semibold text-white mb-1">{{ $service->name }}</div>
                            <div class="text-sm text-zinc-400 mb-2">{{ $service->duration_minutes }} mins</div>
                            <div class="mt-auto font-bold text-amber-500">Rp {{ number_format($service->price, 0, ',', '.') }}</div>
                            <div class="absolute top-4 right-4 opacity-0 peer-checked:opacity-100 text-amber-500">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>
                
                <div class="flex justify-end mt-8">
                    <button type="button" onclick="nextStep(2)" class="btn-primary flex items-center gap-2">
                        Next Step <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Step 2: Select Date & Time & Barber -->
            <div id="step-2" class="wizard-step hidden">
                <h2 class="text-xl font-semibold text-white border-b border-zinc-800 pb-4 mb-6"><span class="text-amber-500 mr-2">2.</span> Date & Barber</h2>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-2">Select Barber (Staff)</label>
                        <select name="staff_id" id="staff_id" class="input-field" required onchange="fetchSlots()">
                            <option value="">-- Choose a Barber --</option>
                            @foreach($staff as $member)
                                <option value="{{ $member->id }}">{{ $member->name }} - {{ $member->specialization }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-2">Select Date</label>
                        <input type="date" name="booking_date" id="booking_date" class="input-field" min="{{ date('Y-m-d') }}" required onchange="fetchSlots()">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-2">Available Time Slots</label>
                        <input type="hidden" name="booking_time" id="booking_time" required>
                        <div id="slots_container" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3">
                            <p class="text-zinc-500 text-sm col-span-full py-4 text-center border border-dashed border-zinc-700 rounded-lg">Please select a barber and date first.</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between mt-8">
                    <button type="button" onclick="nextStep(1)" class="btn-secondary">Back</button>
                    <button type="button" onclick="nextStep(3)" class="btn-primary">Next Step</button>
                </div>
            </div>

            <!-- Step 3: Customer Details -->
            <div id="step-3" class="wizard-step hidden">
                <h2 class="text-xl font-semibold text-white border-b border-zinc-800 pb-4 mb-6"><span class="text-amber-500 mr-2">3.</span> Your Details</h2>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-2">Full Name</label>
                        <input type="text" name="customer_name" class="input-field" placeholder="John Doe" required value="{{ auth()->check() ? auth()->user()->name : '' }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-2">WhatsApp Number</label>
                        <input type="text" name="customer_phone" class="input-field" placeholder="08123456789" required value="{{ auth()->check() ? auth()->user()->phone : '' }}">
                        <p class="text-xs text-zinc-500 mt-2">We will send your booking confirmation via WhatsApp.</p>
                    </div>
                </div>

                <div class="flex justify-between mt-8 pt-6 border-t border-zinc-800">
                    <button type="button" onclick="nextStep(2)" class="btn-secondary">Back</button>
                    <button type="submit" class="btn-primary w-full md:w-auto">Confirm & Pay</button>
                </div>
            </div>

        </form>
    </div>
</div>

@push('scripts')
<script>
    function nextStep(step) {
        document.querySelectorAll('.wizard-step').forEach(el => el.classList.add('hidden'));
        document.getElementById('step-' + step).classList.remove('hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function selectTimeSlot(timeStr, btnEl) {
        document.getElementById('booking_time').value = timeStr;
        document.querySelectorAll('.time-slot-btn').forEach(el => {
            el.classList.remove('bg-amber-500', 'text-zinc-900', 'border-amber-500');
            el.classList.add('bg-zinc-900', 'text-zinc-300', 'border-zinc-700');
        });
        btnEl.classList.remove('bg-zinc-900', 'text-zinc-300', 'border-zinc-700');
        btnEl.classList.add('bg-amber-500', 'text-zinc-900', 'border-amber-500');
    }

    async function fetchSlots() {
        const staffId = document.getElementById('staff_id').value;
        const date = document.getElementById('booking_date').value;
        const container = document.getElementById('slots_container');
        const token = document.querySelector('meta[name="csrf-token"]').content;

        if (!staffId || !date) return;

        container.innerHTML = '<p class="text-zinc-400 text-sm col-span-full text-center">Loading slots...</p>';
        document.getElementById('booking_time').value = '';

        try {
            const res = await fetch('{{ route("booking.slots") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ staff_id: staffId, date: date })
            });
            const data = await res.json();
            
            if (data.slots && data.slots.length > 0) {
                container.innerHTML = data.slots.map(slot => `
                    <button type="button" onclick="selectTimeSlot('${slot.time}', this)" class="time-slot-btn py-2 px-3 rounded-lg border border-zinc-700 bg-zinc-900 text-zinc-300 hover:border-amber-500 hover:text-amber-500 transition-colors text-center font-medium">
                        ${slot.display}
                    </button>
                `).join('');
            } else {
                container.innerHTML = '<p class="text-red-400 text-sm col-span-full py-4 text-center bg-red-500/10 rounded-lg">No available slots for this date.</p>';
            }
        } catch (e) {
            container.innerHTML = '<p class="text-red-400 text-sm col-span-full">Error fetching slots.</p>';
        }
    }
</script>
@endpush
@endsection
