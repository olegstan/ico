<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameSessionsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_sessions_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('session_id');
            $table->integer('user_id');
            $table->timestamps();

            $table->integer('credits_before')->nullable();
            $table->integer('credits_after')->nullable();
            $table->unique(['session_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_sessions_users');
    }
}
