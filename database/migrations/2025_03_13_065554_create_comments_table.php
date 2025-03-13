<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID user yang jawabannya dihapus
            $table->string('soal_number'); // Nomor soal (1-17)
            $table->string('field_name')->nullable(); // Nama field yang dihapus (jika spesifik field)
            $table->text('comment'); // Komentar dari admin
            $table->unsignedBigInteger('admin_id'); // ID admin yang menghapus
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
}