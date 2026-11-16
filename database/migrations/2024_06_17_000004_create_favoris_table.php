<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favoris', function (Blueprint $table) {
            $table->id('id_fav');
            $table->foreignId('id_product')->constrained('produits', 'id_product')->onDelete('cascade');
            $table->foreignId('id_client')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['id_product', 'id_client']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favoris');
    }
};