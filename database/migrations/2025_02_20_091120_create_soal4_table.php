<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('soal4', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('independent_org')->nullable(); // A: Organisasi independent
            $table->string('foreign_school_degree')->nullable(); // B: Luar negeri (dapat gelar)
            $table->string('foreign_school_no_degree_1')->nullable();
            $table->string('foreign_school_no_degree_2')->nullable();
            $table->string('foreign_school_no_degree_3')->nullable();
            $table->string('foreign_school_no_degree_4')->nullable();
            $table->string('foreign_school_no_degree_5')->nullable();
            $table->string('domestic_school_no_degree_1')->nullable();
            $table->string('domestic_school_no_degree_2')->nullable();
            $table->string('domestic_school_no_degree_3')->nullable();
            $table->string('domestic_school_no_degree_4')->nullable();
            $table->string('domestic_school_no_degree_5')->nullable();
            $table->integer('nilai')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soal4');
    }
};

