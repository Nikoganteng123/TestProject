<?php
// Migration
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('soal10', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // IPBI offline (5 poin, max 15)
            $table->string('ipbi_offline1')->nullable();
            $table->string('ipbi_offline2')->nullable();
            $table->string('ipbi_offline3')->nullable();
            
            // IPBI online (3 poin, max 9)
            $table->string('ipbi_online1')->nullable();
            $table->string('ipbi_online2')->nullable();
            $table->string('ipbi_online3')->nullable();
            
            // Non IPBI offline (5 poin, max 15)
            $table->string('non_ipbi_offline1')->nullable();
            $table->string('non_ipbi_offline2')->nullable();
            $table->string('non_ipbi_offline3')->nullable();
            
            // Non IPBI online (3 poin, max 9)
            $table->string('non_ipbi_online1')->nullable();
            $table->string('non_ipbi_online2')->nullable();
            $table->string('non_ipbi_online3')->nullable();
            
            // Internasional offline (10 poin, max 20)
            $table->string('international_offline1')->nullable();
            $table->string('international_offline2')->nullable();
            
            // Internasional online (5 poin, max 10)
            $table->string('international_online1')->nullable();
            $table->string('international_online2')->nullable();
            
            // Host/Moderator (1 poin, max 5)
            $table->string('host_moderator1')->nullable();
            $table->string('host_moderator2')->nullable();
            $table->string('host_moderator3')->nullable();
            $table->string('host_moderator4')->nullable();
            $table->string('host_moderator5')->nullable();
            
            $table->integer('nilai')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('soal10');
    }
};