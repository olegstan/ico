<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameBets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_bets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('game_id');
            $table->decimal('bet', 20, 10);
            $table->timestamps();

            $table->unique(['game_id', 'bet']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_bets');
    }
}
