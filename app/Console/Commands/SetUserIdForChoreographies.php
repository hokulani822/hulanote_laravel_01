<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Choreography;

class SetUserIdForChoreographies extends Command
{
    protected $signature = 'choreographies:set-user-id';
    protected $description = 'Set user_id for existing choreographies based on associated songs';

    public function handle()
    {
        $choreographies = Choreography::whereNull('user_id')->get();

        foreach ($choreographies as $choreography) {
            $song = $choreography->song;
            if ($song) {
                $choreography->user_id = $song->user_id;
                $choreography->save();
                $this->info("Updated choreography ID: {$choreography->id} with user_id: {$song->user_id}");
            } else {
                $this->warn("Choreography ID: {$choreography->id} has no associated song");
            }
        }

        $this->info('All choreographies have been updated with user_id');
    }
}
