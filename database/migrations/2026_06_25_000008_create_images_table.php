<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id('id_image');
            $table->string('path');                          // chemin du fichier stocké
            $table->string('alt')->nullable();               // texte alternatif (SEO / accessibilité)
            $table->boolean('is_primary')->default(false);   // image principale du produit
            $table->unsignedBigInteger('id_product');
            $table->timestamps();

            $table->foreign('id_product')
                ->references('id_product')
                ->on('produits')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};