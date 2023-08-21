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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->string('name')->nullable();
            $table->boolean('is_enabled')->default(1);
            $table->timestamp('last_active_at')->nullable();
            $table->timestamp('identified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('is_enabled');
            $table->index('last_active_at');
            $table->index('identified_at');

            $table->foreign('account_id')
                  ->references('id')->on('accounts')
                  ->onDelete('set null')
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
        Schema::dropIfExists('users');
    }
};
