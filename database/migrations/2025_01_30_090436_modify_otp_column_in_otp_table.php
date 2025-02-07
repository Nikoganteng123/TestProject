<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('otp', function (Blueprint $table) {
            $table->string('otp')->nullable()->change(); // Ubah agar bisa NULL
        });
    }

    public function down()
    {
        Schema::table('otp', function (Blueprint $table) {
            $table->string('otp')->change(); // Kembalikan ke kondisi awal jika rollback
        });
    }
};

