<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SimplifyChoreographyVideosTable extends Migration
{
    public function up()
    {
        Schema::table('choreography_videos', function (Blueprint $table) {
            $table->dropColumn(['frame_count', 'ai_edited', 'ai_edited_url']);
        });
    }

    public function down()
    {
        Schema::table('choreography_videos', function (Blueprint $table) {
            $table->integer('frame_count')->nullable();
            $table->boolean('ai_edited')->default(false);
            $table->string('ai_edited_url')->nullable();
        });
    }
}