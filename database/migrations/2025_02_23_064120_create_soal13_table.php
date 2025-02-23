<?php
// Migration
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('soal13', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('guru_tetap')->nullable(); // 15 poin
            $table->string('asisten_guru')->nullable(); // 8 poin
            $table->string('owner_sekolah')->nullable(); // 8 poin
            $table->string('guru_tidak_tetap_offline')->nullable(); // 10 poin
            $table->string('guru_tidak_tetap_online')->nullable(); // 10 poin
            $table->string('guru_luar_negeri1')->nullable(); // 10 poin
            $table->string('guru_luar_negeri2')->nullable(); // maks 20 poin
            
            $table->integer('nilai')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('soal13');
    }
};