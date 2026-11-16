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
         Schema::table('commandes', function (Blueprint $table) {
            if (!Schema::hasColumn('commandes', 'boutique_earning')) {
                $table->decimal('boutique_earning', 10, 2)->default(0)->after('total_price');
            }
            if (!Schema::hasColumn('commandes', 'site_fee')) {
                $table->decimal('site_fee', 10, 2)->default(0)->after('boutique_earning');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn(['boutique_earning', 'site_fee']);
        });
    }
};
