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
        Schema::create('ticket_departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');                // Sales, Technical, Billing...
            $table->string('email')->nullable();   // email nhận thông báo
            $table->timestamps();
        });

        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // client mở ticket
            $table->foreignId('department_id')->constrained('ticket_departments')->cascadeOnDelete();
            $table->string('subject');
            $table->string('status')->default('open');     // open, answered, customer-reply, closed
            $table->string('priority')->default('medium'); // low, medium, high, urgent
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete(); // có thể = user_id
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete(); // staff phụ trách
            $table->timestamps();
        });

        Schema::create('ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // client hoặc staff
            $table->boolean('is_staff')->default(false);
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_replies');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('ticket_departments');
    }
};
