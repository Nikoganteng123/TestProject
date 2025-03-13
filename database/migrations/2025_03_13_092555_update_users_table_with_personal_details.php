<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTableWithPersonalDetails extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Kolom yang sudah ada (asumsi sudah ada, tidak diubah)
            if (!Schema::hasColumn('users', 'id')) {
                $table->id();
            }
            if (!Schema::hasColumn('users', 'name')) {
                $table->string('name');
            }
            if (!Schema::hasColumn('users', 'email')) {
                $table->string('email')->unique();
            }
            if (!Schema::hasColumn('users', 'is_admin')) {
                $table->boolean('is_admin')->default(false);
            }
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'password')) {
                $table->string('password');
            }
            if (!Schema::hasColumn('users', 'profile_picture')) {
                $table->string('profile_picture')->nullable();
            }
            if (!Schema::hasColumn('users', 'nilai')) {
                $table->integer('nilai')->nullable();
            }
            if (!Schema::hasColumn('users', 'temporary_score')) {
                $table->integer('temporary_score')->nullable();
            }
            if (!Schema::hasColumn('users', 'remember_token')) {
                $table->string('remember_token')->nullable();
            }
            if (!Schema::hasColumn('users', 'created_at')) {
                $table->timestamps();
            }
            if (!Schema::hasColumn('users', 'last_submission_date')) {
                $table->date('last_submission_date')->nullable();
            }
            if (!Schema::hasColumn('users', 'is_verified')) {
                $table->boolean('is_verified')->default(false);
            }
            if (!Schema::hasColumn('users', 'can_take_test')) {
                $table->boolean('can_take_test')->default(true);
            }

            // Kolom baru yang ditambahkan
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->nullable();
            }
            if (!Schema::hasColumn('users', 'pekerjaan')) {
                $table->string('pekerjaan')->nullable();
            }
            if (!Schema::hasColumn('users', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable();
            }
            if (!Schema::hasColumn('users', 'informasi_ipbi')) {
                $table->text('informasi_ipbi')->nullable();
            }
            if (!Schema::hasColumn('users', 'domisili')) {
                $table->string('domisili')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'status', 'pekerjaan', 'tanggal_lahir', 'informasi_ipbi', 'domisili'
            ]);
        });
    }
}