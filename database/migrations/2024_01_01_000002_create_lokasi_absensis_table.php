<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lokasi_absensis', function (Blueprint $table) {
            $table->id();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->integer('radius_meter')->default(3000);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lokasi_absensis');
    }
};
