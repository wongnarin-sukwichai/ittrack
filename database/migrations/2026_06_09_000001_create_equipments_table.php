<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipments', function (Blueprint $table) {
            $table->string('id')->primary();           // IT-001, AV-001
            $table->string('name');
            $table->enum('category', ['it', 'av']);
            $table->string('serial')->default('N/A');
            $table->text('description')->nullable();
            $table->enum('status', ['Available', 'Borrowed', 'Pending', 'Maintenance'])->default('Available');
            $table->string('icon')->default('fa-laptop');
            $table->string('current_location')->nullable();
            $table->enum('lifecycle_state', ['Active', 'Under Repair', 'Relocated', 'Decommissioned'])->default('Active');
            $table->string('image')->nullable();       // URL or storage path
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};
