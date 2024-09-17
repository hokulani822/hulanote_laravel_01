<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasColumn('choreographies', 'lyrics_frames')) {
            Schema::table('choreographies', function (Blueprint $table) {
                $table->json('lyrics_frames')->nullable();
            });
        }
    }
    
    public function down()
    {
        if (Schema::hasColumn('choreographies', 'lyrics_frames')) {
            Schema::table('choreographies', function (Blueprint $table) {
                $table->dropColumn('lyrics_frames');
            });
        }
    }
};
