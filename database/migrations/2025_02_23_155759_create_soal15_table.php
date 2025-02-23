<?php
// Migration
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('soal15', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('ikebana_murid')->nullable(); // 5 poin
            $table->string('ikebana_guru')->nullable(); // 15 poin
            $table->string('rangkaian_tradisional')->nullable(); // 10 poin
            $table->string('lainnya')->nullable(); // 5 poin
            
            $table->integer('nilai')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('soal15');
    }
};