<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('choreography_videos')) {
            Schema::create('choreography_videos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('choreography_id')->constrained()->onDelete('cascade');
                $table->string('url');
                $table->string('ai_edited_url')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('choreography_videos', function (Blueprint $table) {
                if (!Schema::hasColumn('choreography_videos', 'ai_edited_url')) {
                    $table->string('ai_edited_url')->nullable();
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('choreography_videos')) {
            if (Schema::hasColumn('choreography_videos', 'ai_edited_url')) {
                Schema::table('choreography_videos', function (Blueprint $table) {
                    $table->dropColumn('ai_edited_url');
                });
            }
        } else {
            Schema::dropIfExists('choreography_videos');
        }
    }
};
?>