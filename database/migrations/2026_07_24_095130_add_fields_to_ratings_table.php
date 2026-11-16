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
       Schema::table('ratings', function (Blueprint $table) {
            $table->unsignedBigInteger('id_product');
            $table->unsignedBigInteger('id_client');
            $table->tinyInteger('rating')->unsigned()->default(0);
            
            // Optional: add foreign keys
            $table->foreign('id_product')->references('id_product')->on('produits')->onDelete('cascade');
            $table->foreign('id_client')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('ratings', function (Blueprint $table) {
            $table->dropForeign(['id_product']);
            $table->dropForeign(['id_client']);
            $table->dropColumn(['id_product', 'id_client', 'rating']);
        });
    }
};
