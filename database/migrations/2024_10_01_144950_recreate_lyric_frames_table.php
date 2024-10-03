<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreateLyricFramesTable extends Migration
{
    public function up()
    {
        Schema::create('lyric_frames', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('choreography_id');
            $table->text('lyrics')->nullable();
            $table->integer('frame_number');
            $table->timestamps();

            $table->foreign('choreography_id')->references('id')->on('choreographies')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lyric_frames');
    }
}