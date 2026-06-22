<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('email');
        });

        // Populate slug for existing users
        DB::table('users')->get()->each(function ($user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['slug' => Str::slug($user->name . '-' . $user->id)]);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};