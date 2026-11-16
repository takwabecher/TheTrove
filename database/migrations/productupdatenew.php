<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->decimal('prix_boutique', 10, 2)->default(0)->after('prix');
            $table->decimal('site_fee', 10, 2)->default(0)->after('prix_boutique');
        });
    }

    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn(['prix_boutique', 'site_fee']);
        });
    }
};