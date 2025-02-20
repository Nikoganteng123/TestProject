<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('soal3', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('bahasa_inggris', ['Dasar', 'Fasih'])->nullable();
            $table->string('bahasa_lain1')->nullable();
            $table->string('bahasa_lain2')->nullable();
            $table->string('bahasa_lain3')->nullable();
            $table->string('bahasa_lain4')->nullable();
            $table->integer('nilai')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('soal3');
    }
};
