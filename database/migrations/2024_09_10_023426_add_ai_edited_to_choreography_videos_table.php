<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAiEditedToChoreographyVideosTable extends Migration
{
    public function up()
    {
        Schema::table('choreography_videos', function (Blueprint $table) {
            $table->boolean('ai_edited')->default(false);
            $table->string('ai_edited_url')->nullable();
        });
    }

    public function down()
    {
        Schema::table('choreography_videos', function (Blueprint $table) {
            $table->dropColumn('ai_edited');
            $table->dropColumn('ai_edited_url');
        });
    }
}