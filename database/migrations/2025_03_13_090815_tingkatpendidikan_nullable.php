<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TingkatPendidikanNullable extends Migration
{
    public function up()
    {
        Schema::table('soal1', function (Blueprint $table) {
            $table->enum('tingkat_pendidikan', ['SMP-D3', 'S1', 'S2_atau_lebih'])->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('soal1', function (Blueprint $table) {
            $table->enum('tingkat_pendidikan', ['SMP-D3', 'S1', 'S2_atau_lebih'])->nullable(false)->change();
        });
    }
}