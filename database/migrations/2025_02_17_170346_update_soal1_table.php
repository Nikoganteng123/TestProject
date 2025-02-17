<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('soal1', function (Blueprint $table) {
            $table->enum('tingkat_pendidikan', ['SMP', 'D3', 'S1', 'S2_or_above'])->after('user_id');
            $table->integer('nilai')->after('tingkat_pendidikan');
        });
    }
    
    public function down()
    {
        Schema::table('soal1', function (Blueprint $table) {
            $table->dropColumn('tingkat_pendidikan');
            $table->dropColumn('nilai');
        });
    }
    
};
