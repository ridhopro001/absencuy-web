<?php

use App\Models\Mahasiswa;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Mahasiswa::whereNull('semester')->update(['semester' => 'Semester I']);

        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->string('semester')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->string('semester')->nullable()->change();
        });
    }
};
