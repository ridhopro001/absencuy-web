<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->string('lokasi_sumber', 20)->nullable()->after('longitude');
        });
    }

    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropColumn('lokasi_sumber');
        });
    }
};
