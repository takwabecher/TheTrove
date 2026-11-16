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
    Schema::table('reviews', function (Blueprint $table) {
        if (!Schema::hasColumn('reviews', 'id_product')) {
            $table->unsignedBigInteger('id_product')->after('id');
        }
        if (!Schema::hasColumn('reviews', 'id_client')) {
            $table->unsignedBigInteger('id_client')->after('id_product');
        }
        if (!Schema::hasColumn('reviews', 'comment')) {
            $table->text('comment')->nullable()->after('id_client');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
