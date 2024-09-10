<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChoreographiesTable extends Migration
{
    public function up()
    {
        Schema::create('choreographies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('song_id')->constrained()->onDelete('cascade');
            $table->text('description')->nullable();
            $table->json('frames')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('choreographies');
    }
}