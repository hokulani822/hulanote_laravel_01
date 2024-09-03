<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Song; 


class Choreography extends Model
{
    use HasFactory;

    protected $fillable = ['song_id', 'description', 'video_url'];

    public function song()
    {
        return $this->belongsTo(Song::class);
    }
}
