<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Staff;
use App\Models\Payment;
use App\Models\StaffSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalBookings = Booking::count();
        $revenue = Payment::where('status', 'paid')->sum('amount');
        $activeStaff = Staff::where('is_active', true)->count();
        $recentBookings = Booking::with(['service', 'staff', 'payment'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('totalBookings', 'revenue', 'activeStaff', 'recentBookings'));
    }

    public function services()
    {
        $services = Service::all();
        return view('admin.services.index', compact('services'));
    }

    public function storeService(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'is_active' => 'boolean'
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        Service::create($validated);
        return back()->with('success', 'Service added successfully.');
    }

    public function updateService(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');
        $service->update($validated);
        return back()->with('success', 'Service updated successfully.');
    }

    public function deleteService(Service $service)
    {
        $service->delete();
        return back()->with('success', 'Service deleted successfully.');
    }

    public function staff()
    {
        $staffMembers = Staff::all();
        return view('admin.staff.index', compact('staffMembers'));
    }

    public function storeStaff(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'is_active' => 'boolean'
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        Staff::create($validated);
        return back()->with('success', 'Staff added successfully.');
    }

    public function updateStaff(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');
        $staff->update($validated);
        return back()->with('success', 'Staff updated successfully.');
    }

    public function deleteStaff(Staff $staff)
    {
        $staff->delete();
        return back()->with('success', 'Staff deleted successfully.');
    }

    public function getStaffSchedule(Staff $staff)
    {
        $schedules = StaffSchedule::where('staff_id', $staff->id)->get()->keyBy('day_of_week');
        $defaultSchedule = [];
        for ($i = 0; $i < 7; $i++) {
            $defaultSchedule[] = [
                'day_of_week' => $i,
                'open_time' => $schedules->has($i) ? Carbon::parse($schedules[$i]->open_time)->format('H:i') : '10:00',
                'close_time' => $schedules->has($i) ? Carbon::parse($schedules[$i]->close_time)->format('H:i') : '20:00',
                'is_off' => $schedules->has($i) ? $schedules[$i]->is_off : false,
            ];
        }
        return response()->json($defaultSchedule);
    }

    public function updateStaffSchedule(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'schedules' => 'required|array|size:7',
            'schedules.*.day_of_week' => 'required|integer|min:0|max:6',
            'schedules.*.open_time' => 'required|date_format:H:i',
            'schedules.*.close_time' => 'required|date_format:H:i|after:schedules.*.open_time',
            'schedules.*.is_off' => 'nullable|boolean',
        ]);

        foreach ($validated['schedules'] as $scheduleData) {
            StaffSchedule::updateOrCreate(
                [
                    'staff_id' => $staff->id,
                    'day_of_week' => $scheduleData['day_of_week']
                ],
                [
                    'open_time' => $scheduleData['open_time'],
                    'close_time' => $scheduleData['close_time'],
                    'is_off' => isset($scheduleData['is_off']) ? (bool)$scheduleData['is_off'] : false,
                ]
            );
        }

        return back()->with('success', 'Staff schedule updated successfully.');
    }

    public function bookings(Request $request)
    {
        $query = Booking::with(['service', 'staff', 'payment'])->latest();
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        }
        if ($request->filled('search')) {
            $query->where('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $request->search . '%');
        }

        $bookings = $query->paginate(15);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function updateBookingStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,done,cancelled'
        ]);

        $booking->update(['status' => $validated['status']]);

        return back()->with('success', 'Status booking berhasil diupdate.');
    }
}
