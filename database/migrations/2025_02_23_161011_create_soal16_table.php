<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('soal16', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('aktif_merangkai')->nullable(); // 10 poin
            $table->string('owner_berbadan_hukum')->nullable(); // 10 poin
            $table->string('owner_tanpa_badan_hukum')->nullable(); // 5 poin
            $table->string('freelance_designer')->nullable(); // 5 poin
            
            $table->integer('nilai')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('soal16');
    }
};