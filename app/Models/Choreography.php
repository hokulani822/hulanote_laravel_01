<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Song;
use App\Models\ChoreographyVideo;

class Choreography extends Model
{
    use HasFactory;

    protected $fillable = [
        'song_id',
        'description',
        'frames', // frames カラムを追加
    ];

    protected $casts = [
        'frames' => 'array', // frames を配列としてキャストする
    ];

    public function song()
    {
        return $this->belongsTo(Song::class);
    }

    public function videos()
    {
        return $this->hasMany(ChoreographyVideo::class);
    }

    // frames メソッドは削除します
    // public function frames()
    // {
    //     return $this->hasManyThrough(VideoFrame::class, ChoreographyVideo::class);
    // }
}