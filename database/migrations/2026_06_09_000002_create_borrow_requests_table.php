<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrow_requests', function (Blueprint $table) {
            $table->string('id')->primary();           // REQ-2026-001
            $table->string('borrower_name');
            $table->string('email');
            $table->string('department')->nullable();
            $table->json('items');                     // array of equipment IDs
            $table->text('purpose');
            $table->date('borrow_date');
            $table->date('return_date');
            $table->enum('status', ['Pending', 'Approved', 'Returned', 'Overdue'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrow_requests');
    }
};
