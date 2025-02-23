<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('soal8', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Demo DPP/DPD/DPC IPBI (2 poin per file, max 5 files)
            $table->string('demo_dpp_dpd1')->nullable();
            $table->string('demo_dpp_dpd2')->nullable();
            $table->string('demo_dpp_dpd3')->nullable();
            $table->string('demo_dpp_dpd4')->nullable();
            $table->string('demo_dpp_dpd5')->nullable();
            
            // Acara diluar IPBI (1 poin per file, max 5 files)
            $table->string('non_ipbi1')->nullable();
            $table->string('non_ipbi2')->nullable();
            $table->string('non_ipbi3')->nullable();
            $table->string('non_ipbi4')->nullable();
            $table->string('non_ipbi5')->nullable();
            
            // Acara internasional (2 poin per file, max 2 files)
            $table->string('international1')->nullable();
            $table->string('international2')->nullable();
            
            $table->integer('nilai')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('soal8');
    }
};