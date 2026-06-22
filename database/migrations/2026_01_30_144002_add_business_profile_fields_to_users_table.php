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
        Schema::table('users', function (Blueprint $table) {
            $table->string('website_logo_path')->nullable()->after('points');
            $table->string('website_name')->nullable()->after('website_logo_path');
            $table->string('company_name')->nullable()->after('website_name');
            $table->text('company_address')->nullable()->after('company_name');
            $table->string('company_contact_number')->nullable()->after('company_address');
            $table->string('company_contact_person')->nullable()->after('company_contact_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'website_logo_path',
                'website_name',
                'company_name',
                'company_address',
                'company_contact_number',
                'company_contact_person',
            ]);
        });
    }
};
