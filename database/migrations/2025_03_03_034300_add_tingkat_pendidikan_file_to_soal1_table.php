<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('soal1', function (Blueprint $table) {
            $table->string('tingkat_pendidikan_file')->nullable()->after('tingkat_pendidikan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soal1', function (Blueprint $table) {
            $table->dropColumn('tingkat_pendidikan_file');
        });
    }
};
