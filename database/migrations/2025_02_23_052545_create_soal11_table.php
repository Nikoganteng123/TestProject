<?php
// Migration
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('soal11', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Penguji sertifikasi (10 poin, max 20)
            $table->string('penguji_sertifikasi1')->nullable();
            $table->string('penguji_sertifikasi2')->nullable();
            
            // Juri IPBI (10 poin, max 20)
            $table->string('juri_ipbi1')->nullable();
            $table->string('juri_ipbi2')->nullable();
            
            // Juri non IPBI (5 poin, max 10)
            $table->string('juri_non_ipbi1')->nullable();
            $table->string('juri_non_ipbi2')->nullable();
            
            $table->integer('nilai')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('soal11');
    }
};