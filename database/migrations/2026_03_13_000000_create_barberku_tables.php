<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. users table role update (add to existing users table instead of creating new if it exists)
        // Since Laravel creates users table, we just add the role column
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->enum('role', ['customer', 'admin'])->default('customer');
        });

        // 2. services
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // Haircut, Beard, Coloring, Treatment
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('duration_minutes');
            $table->string('image_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. staff
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('bio')->nullable();
            $table->string('photo_url')->nullable();
            $table->string('specialization')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. staff_schedules
        Schema::create('staff_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->integer('day_of_week'); // 0 Sunday - 6 Saturday
            $table->time('open_time');
            $table->time('close_time');
            $table->boolean('is_off')->default(false);
            $table->timestamps();
        });

        // 5. bookings
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            // In case it's a guest or logged-in user
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            
            $table->foreignId('staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            
            $table->date('booking_date');
            $table->time('booking_time');
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'done', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });

        // 6. payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->string('invoice_id')->nullable(); // External ID from Pak Kasir
            $table->decimal('amount', 10, 2);
            $table->string('proof_url')->nullable();
            $table->enum('status', ['unpaid', 'paid', 'verified'])->default('unpaid');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        // 7. reviews
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->string('customer_name')->nullable();
            $table->integer('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('staff_schedules');
        Schema::dropIfExists('staff');
        Schema::dropIfExists('services');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'role']);
        });
    }
};
