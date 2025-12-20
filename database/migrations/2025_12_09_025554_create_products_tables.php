<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_group_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('type')->default('other');
            $table->boolean('is_active')->default(true);
            $table->string('provisioning_module')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->timestamps();
        });

        Schema::create('product_pricing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('billing_cycle');
            $table->decimal('price', 12, 2);
            $table->decimal('setup_fee', 12, 2)->default(0);
            $table->string('currency', 3)->default('VND');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_pricing');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_groups');
    }

};
