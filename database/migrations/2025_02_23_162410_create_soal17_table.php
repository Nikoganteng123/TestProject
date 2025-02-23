<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('soal17', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('media_cetak_nasional')->nullable(); // 5 poin
            $table->string('media_cetak_internasional')->nullable(); // 10 poin
            $table->string('buku_merangkai_bunga')->nullable(); // 20 poin
            $table->string('kontributor_buku1')->nullable(); // 10 poin each, maximum 20
            $table->string('kontributor_buku2')->nullable(); // 10 poin each, maximum 20
            $table->string('kontributor_tv1')->nullable(); // 5 poin each, maximum 10
            $table->string('kontributor_tv2')->nullable(); // 5 poin each, maximum 10
            
            $table->integer('nilai')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('soal17');
    }
};