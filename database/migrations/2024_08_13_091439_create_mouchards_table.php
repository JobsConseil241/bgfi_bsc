<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mouchards', function (Blueprint $table) {
            $table->id();
            $table->string("ip_address");
            $table->string("os_system");
            $table->string("navigator");
            $table->string("author_action");
            $table->integer("action_id")->nullable();
            $table->string("agences");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mouchards');
    }
};
