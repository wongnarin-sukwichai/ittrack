<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_logs', function (Blueprint $table) {
            $table->id();
            $table->string('asset_id');
            $table->foreign('asset_id')->references('id')->on('equipments')->onDelete('cascade');
            $table->enum('action', ['Relocate', 'Maintenance', 'Inspect', 'Decommission']);
            $table->text('detail');
            $table->string('location');
            $table->string('operator');
            $table->date('date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_logs');
    }
};
