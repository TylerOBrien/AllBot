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
        Schema::create('sounds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('folder_id')->nullable();
            $table->enum('disk', config('enum.filesystem.disk'));
            $table->string('name');
            $table->string('filepath');
            $table->string('mimetype')->nullable();
            $table->float('duration', 8, 3);
            $table->unsignedMediumInteger('filesize');
            $table->morphs('owner');
            $table->timestamps();

            $table->foreign('folder_id')
                  ->references('id')->on('folders')
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
        Schema::dropIfExists('sounds');
    }
};
