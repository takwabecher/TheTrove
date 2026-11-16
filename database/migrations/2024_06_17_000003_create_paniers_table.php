<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paniers', function (Blueprint $table) {
            $table->id('id_panier');
            $table->foreignId('id_client')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_product')->constrained('produits', 'id_product')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->timestamp('dateCreation')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paniers');
    }
};