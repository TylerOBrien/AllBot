<?php

use App\Support\Token;

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
        Schema::create('tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('identity_id');
            $table->enum('type', config('enum.token.type'));
            $table->enum('transformation', config('enum.token.transformation'));
            $table->enum('algorithm', config('enum.token.algorithm'));
            $table->char('hash', Token::length(config('auth.tokens.hash.algorithm')));
            $table->text('value');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();

            $table->unique('hash');

            $table->index('type');
            $table->index('last_used_at');

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
        Schema::dropIfExists('tokens');
    }
};
