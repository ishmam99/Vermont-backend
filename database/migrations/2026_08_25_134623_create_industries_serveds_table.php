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
        Schema::create('industries_serveds', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('department', ['Aerospace & Defense', 'Automotive', 'Medical Imaging'])->default('Aerospace & Defense');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->string('company_name')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('industries_serveds');
    }
};
