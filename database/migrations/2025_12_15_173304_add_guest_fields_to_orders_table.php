<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // nếu orders.user_id đang NOT NULL -> chuyển nullable để guest order dùng được
            $table->foreignId('user_id')->nullable()->change();
            $table->boolean('is_guest')->default(false)->after('user_id');
            $table->string('guest_name')->nullable()->after('is_guest');
            $table->string('guest_email')->nullable()->after('guest_name');
            $table->string('guest_phone')->nullable()->after('guest_email');
            $table->string('guest_company')->nullable()->after('guest_phone');
            $table->string('public_token')->nullable()->unique()->after('guest_company');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'is_guest', 'guest_name', 'guest_email', 'guest_phone', 'guest_company', 'public_token'
            ]);

            // rollback: bạn có thể giữ nullable nếu muốn, hoặc change lại not null (tuỳ DB hiện trạng)
            // $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
