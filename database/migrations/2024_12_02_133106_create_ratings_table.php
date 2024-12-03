<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id'); // Misalnya untuk memberi rating pada produk/artikel
            $table->tinyInteger('stars')->unsigned(); // Menyimpan nilai rating (1-5)
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade'); // 'items' adalah tabel target
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
