<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoFramesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('video_frames')) {
            Schema::create('video_frames', function (Blueprint $table) {
                $table->id();
                $table->foreignId('choreography_video_id')->constrained()->onDelete('cascade');
                $table->string('frame_url');
                $table->float('timestamp');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('video_frames');
    }
}
?>