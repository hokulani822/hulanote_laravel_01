<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChoreographyVideosTable extends Migration
{
    public function up()
    {
        Schema::create('choreography_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('choreography_id')->constrained()->onDelete('cascade');
            $table->string('url');
            $table->string('ai_edited_url')->nullable();
            $table->integer('frame_count')->nullable();
            $table->boolean('ai_edited')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('choreography_videos');
    }
}