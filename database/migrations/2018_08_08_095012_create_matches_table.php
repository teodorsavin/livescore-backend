<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->increments('match_id');
            $table->integer('country_id');
            $table->integer('league_id');
            $table->string('match_date', 20);
            $table->string('match_time', 40)->nullable();
            $table->string('match_status', 20)->nullable();
            $table->string('match_hometeam_name', 256);
            $table->integer('match_hometeam_score')->nullable();
            $table->string('match_awayteam_name', 256);
            $table->integer('match_awayteam_score')->nullable();
            $table->integer('match_hometeam_halftime_score')->nullable();
            $table->integer('match_awayteam_halftime_score')->nullable();
            $table->integer('match_hometeam_extra_score')->nullable();
            $table->integer('match_awayteam_extra_score')->nullable();
            $table->integer('match_hometeam_penalty_score')->nullable();
            $table->integer('match_awayteam_penalty_score')->nullable();
            $table->string('match_hometeam_system', 50);
            $table->string('match_awayteam_system', 50);
            $table->tinyInteger('match_live');
            $table->text('goalscorer')->nullable();
            $table->text('cards')->nullable();
            $table->text('lineup')->nullable();
            $table->text('statistics')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matches');
    }
}
