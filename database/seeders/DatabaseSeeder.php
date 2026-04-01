<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
use App\Models\Staff;
use App\Models\StaffSchedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin & Customer Users
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@barberku.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '08111111111',
        ]);

        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '08222222222',
        ]);

        // 2. Services
        $services = [
            ['name' => 'Signature Haircut', 'category' => 'Haircut', 'description' => 'Precision cut tailored to your face shape.', 'price' => 75000, 'duration_minutes' => 45],
            ['name' => 'Classic Fade', 'category' => 'Haircut', 'description' => 'Clean, sharp, and perfectly blended fade.', 'price' => 60000, 'duration_minutes' => 30],
            ['name' => 'Hot Towel Shave', 'category' => 'Beard', 'description' => 'Traditional straight razor shave with a relaxing hot towel.', 'price' => 50000, 'duration_minutes' => 30],
            ['name' => 'Beard Trim & Shape', 'category' => 'Beard', 'description' => 'Expert beard sculpting and conditioning.', 'price' => 45000, 'duration_minutes' => 20],
            ['name' => 'Hair Coloring', 'category' => 'Treatment', 'description' => 'Professional hair coloring and styling.', 'price' => 250000, 'duration_minutes' => 90],
            ['name' => 'Scalp Treatment', 'category' => 'Treatment', 'description' => 'Invigorating deep scalp massage and treatment.', 'price' => 120000, 'duration_minutes' => 45],
        ];

        foreach ($services as $svc) {
            Service::create($svc);
        }

        // 3. Staff (Barbers)
        $barbers = [
            ['name' => 'Mike Torres', 'specialization' => 'Master Barber', 'bio' => 'Over 10 years of experience in classic and modern cuts.', 'photo_url' => 'https://ui-avatars.com/api/?name=Mike+Torres&background=f59e0b&color=fff'],
            ['name' => 'David Chen', 'specialization' => 'Fade Specialist', 'bio' => 'Precision fades and intricate designs.', 'photo_url' => 'https://ui-avatars.com/api/?name=David+Chen&background=f59e0b&color=fff'],
            ['name' => 'Alex Rodriguez', 'specialization' => 'Beard Expert', 'bio' => 'The go-to guy for flawless beard trims and hot shaves.', 'photo_url' => 'https://ui-avatars.com/api/?name=Alex+Rodriguez&background=f59e0b&color=fff'],
        ];

        foreach ($barbers as $b) {
            $staff = Staff::create($b);
            
            // 4. Create Schedules for each staff member for everyday except Sunday
            for ($day = 1; $day <= 6; $day++) {
                StaffSchedule::create([
                    'staff_id' => $staff->id,
                    'day_of_week' => $day, // 1 Mon... 6 Sat
                    'open_time' => '09:00:00',
                    'close_time' => '21:00:00',
                    'is_off' => false,
                ]);
            }
            // Sunday Off
            StaffSchedule::create([
                    'staff_id' => $staff->id,
                    'day_of_week' => 0, // Sunday
                    'open_time' => '09:00:00',
                    'close_time' => '17:00:00',
                    'is_off' => true,
            ]);
        }
    }
}
