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
        Schema::create('product_pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('key'); // cpu, ram, storage...
            $table->string('billing_cycle')->default('monthly'); // monthly/annually...
            $table->decimal('price_per_unit', 12, 2)->default(0);
            $table->unsignedInteger('min')->nullable();
            $table->unsignedInteger('max')->nullable();
            $table->unsignedInteger('step')->default(1);
            $table->timestamps();

            $table->index(['product_id', 'key', 'billing_cycle']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_pricing_rules');
    }
};
