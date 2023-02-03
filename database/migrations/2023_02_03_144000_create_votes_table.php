<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotesTable extends Migration
{

    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('user_id');
            $table->foreignId('question_id')->references('id')->on('questions');
            $table->foreignId('answer_id')->references('id')->on('answers');
            $table->morphs('votable');
        });
    }

    public function down()
    {
        Schema::dropIfExists('votes');
    }
}
