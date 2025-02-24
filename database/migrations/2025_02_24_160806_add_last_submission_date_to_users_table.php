<?php

// database/migrations/xxxx_xx_xx_add_last_submission_date_to_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastSubmissionDateToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_submission_date')->nullable();
            $table->boolean('can_take_test')->default(true);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['last_submission_date', 'can_take_test']);
        });
    }
}