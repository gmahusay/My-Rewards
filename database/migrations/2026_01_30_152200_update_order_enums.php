<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Enums are hard to change in standard Laravel Blueprint for some DBs, 
        // so using raw SQL for better reliability across different MySQL versions.
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method ENUM('cash', 'points', 'stripe', 'paypal') NOT NULL");
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'completed', 'cancelled', 'failed') NOT NULL DEFAULT 'completed'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method ENUM('cash', 'points') NOT NULL");
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'completed', 'cancelled') NOT NULL DEFAULT 'completed'");
        }
    }
};
