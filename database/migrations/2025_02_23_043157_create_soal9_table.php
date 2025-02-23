<?php
// Migration
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('soal9', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Maksimum 15 poin total
            $table->string('pembina_demonstrator')->nullable(); // 15 poin
            $table->string('panitia')->nullable(); // 10 poin
            $table->string('peserta')->nullable(); // 5 poin
            
            $table->integer('nilai')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('soal9');
    }
};
