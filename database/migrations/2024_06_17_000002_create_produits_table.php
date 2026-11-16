<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id('id_product');
            $table->string('nom');
            $table->decimal('prix', 10, 2);
            $table->integer('stock')->default(0);
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('id_cat');
            $table->unsignedBigInteger('id_boutique');
            $table->timestamps();

            $table->foreign('id_cat')
                ->references('id_cat')
                ->on('categories')
                ->onDelete('cascade');

            $table->foreign('id_boutique')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};