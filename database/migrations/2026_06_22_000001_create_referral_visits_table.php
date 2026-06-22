<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('referral_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('referral_categories')->onDelete('cascade');
            $table->foreignId('referrer_id')->constrained('users')->onDelete('cascade');
            $table->string('ip')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('referral_visits');
    }
};
