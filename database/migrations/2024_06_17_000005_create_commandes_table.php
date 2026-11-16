<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id('id_cmd');
            $table->foreignId('id_client')->constrained('users')->onDelete('cascade');
            $table->timestamp('date_cmd')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'declined', 'completed'])->default('pending');
            $table->decimal('total_price', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};