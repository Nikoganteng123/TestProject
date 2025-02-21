<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('soal7', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('juara_nasional_dpp')->nullable();
            $table->string('juara_non_dpp')->nullable();
            $table->string('juara_instansi_lain')->nullable();
            $table->string('juara_internasional')->nullable();
            $table->string('peserta_lomba_1')->nullable();
            $table->string('peserta_lomba_2')->nullable();
            $table->string('peserta_lomba_3')->nullable();
            $table->string('peserta_lomba_4')->nullable();
            $table->string('peserta_lomba_5')->nullable();
            $table->string('juri_lomba_1')->nullable();
            $table->string('juri_lomba_2')->nullable();
            $table->integer('nilai')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('soal7');
    }
};