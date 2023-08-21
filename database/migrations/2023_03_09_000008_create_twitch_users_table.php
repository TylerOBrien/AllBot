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
        Schema::create('twitch_users', function (Blueprint $table) {
            $table->id();
            $table->string('twitch_id');
            $table->unsignedBigInteger('identity_id');
            $table->string('email')->nullable();
            $table->string('name')->nullable();
            $table->string('profile_image_url')->nullable();
            $table->timestamps();

            $table->foreign('identity_id')
                  ->references('id')->on('identities')
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
        Schema::dropIfExists('twitch_users');
    }
};
