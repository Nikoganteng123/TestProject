<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('soal2', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('tp3')->nullable(); // Sertifikat TP3
            $table->string('lpmp_diknas')->nullable(); // Sertifikat LPMP/Diknas
            $table->string('guru_lain_ipbi_1')->nullable(); // Maksimum 4 file
            $table->string('guru_lain_ipbi_2')->nullable();
            $table->string('guru_lain_ipbi_3')->nullable();
            $table->string('guru_lain_ipbi_4')->nullable();
            $table->string('training_trainer')->nullable(); 
            $table->integer('nilai')->nullable();// Sertifikat Training to Trainer
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('soal2');
    }
};
