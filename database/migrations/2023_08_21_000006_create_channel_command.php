<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_command', function (Blueprint $table) {
            $table->unsignedBigInteger('channel_id');
            $table->unsignedBigInteger('command_id');

            $table->foreign('channel_id')
                  ->references('id')->on('channels')
                  ->onDelete('cascade')
                  ->onUpdate('restrict');

            $table->foreign('command_id')
                  ->references('id')->on('commands')
                  ->onDelete('cascade')
                  ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channel_command');
    }
};
