<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->foreignId('delivery_company_id')->nullable()
                  ->constrained('delivery_companies')->nullOnDelete();
            $table->enum('delivery_status', ['pending','picked_up','in_transit','delivered','cancelled'])
                  ->default('pending')->after('status');
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('delivery_notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropForeign(['delivery_company_id']);
            $table->dropColumn(['delivery_company_id','delivery_status','picked_up_at','delivered_at','delivery_notes']);
        });
    }
};