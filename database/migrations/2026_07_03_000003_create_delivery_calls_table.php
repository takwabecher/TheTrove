<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_calls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_company_id')->constrained('delivery_companies')->cascadeOnDelete();
            $table->unsignedBigInteger('commande_id');
            $table->foreign('commande_id')->references('id_cmd')->on('commandes')->cascadeOnDelete();
            $table->string('client_name');
            $table->string('client_phone');
            $table->string('call_type')->default('outgoing');
            $table->integer('duration_seconds')->default(0);
            $table->text('notes')->nullable();
            $table->timestamp('called_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_calls');
    }
};