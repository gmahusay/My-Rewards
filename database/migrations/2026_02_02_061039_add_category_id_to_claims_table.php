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
        Schema::table('claims', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('business_id')->constrained('claim_categories')->onDelete('set null');
            
            // Drop old unique constraint if it exists (it was added in a previous migration)
            $table->dropUnique(['invoice_number']);
            
            // Add new composite unique index
            $table->unique(['category_id', 'invoice_number'], 'claims_category_invoice_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->dropUnique('claims_category_invoice_unique');
            $table->unique('invoice_number');
            $table->dropConstrainedForeignId('category_id');
        });
    }
};
