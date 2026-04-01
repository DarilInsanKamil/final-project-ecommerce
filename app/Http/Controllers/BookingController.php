<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Staff;
use App\Models\StaffSchedule;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function createWizard(Request $request)
    {
        $services = Service::where('is_active', true)->get();
        $staff = Staff::where('is_active', true)->get();
        
        return view('bookings.wizard', compact('services', 'staff'));
    }

    public function getAvailableSlots(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'date' => 'required|date|after_or_equal:today',
        ]);

        $date = Carbon::parse($validated['date']);
        $dayOfWeek = $date->dayOfWeek; // 0 (Sunday) - 6 (Saturday)

        $schedule = StaffSchedule::where('staff_id', $validated['staff_id'])
            ->where('day_of_week', $dayOfWeek)
            ->where('is_off', false)
            ->first();

        if (!$schedule) {
            return response()->json(['slots' => []]);
        }

        // Get existing bookings for that staff member on that date
        $existingBookings = Booking::where('staff_id', $validated['staff_id'])
            ->whereDate('booking_date', $date->toDateString())
            ->whereIn('status', ['confirmed', 'done'])
            ->pluck('booking_time')
            ->toArray();

        // Calculate slots (every 30 mins)
        $slots = [];
        $start = Carbon::parse($schedule->open_time);
        $end = Carbon::parse($schedule->close_time);

        while ($start <= $end->copy()->subMinutes(30)) {
            $timeString = $start->format('H:i:s');
            $timeDisplay = $start->format('H:i');
            
            // Basic availability check (doesn't account for overlapping service durations)
            if (!in_array($timeString, $existingBookings)) {
                $slots[] = ['time' => $timeString, 'display' => $timeDisplay];
            }
            $start->addMinutes(30);
        }

        return response()->json(['slots' => $slots]);
    }

    public function store(Request $request, FonnteService $fonnte)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'staff_id' => 'required|exists:staff,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|date_format:H:i:s',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        $service = Service::findOrFail($validated['service_id']);

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'staff_id' => $validated['staff_id'],
            'service_id' => $validated['service_id'],
            'booking_date' => $validated['booking_date'],
            'booking_time' => $validated['booking_time'],
            'total_price' => $service->price,
            'status' => 'pending',
        ]);

        // Integrate PakKasir payment creation later in PaymentController or redirect
        
        // Notify via Fonnte
        $message = "Halo {$booking->customer_name},\nBooking {$service->name} Anda untuk tanggal {$booking->booking_date} pukul {$booking->booking_time} sudah kami terima. Silahkan lakukan pembayaran untuk konfirmasi.";
        $fonnte->sendMessage($booking->customer_phone, $message);

        return redirect()->route('payment.process', ['booking' => $booking->id])
            ->with('success', 'Booking berhasil dibuat. Silahkan lakukan pembayaran.');
    }
}
