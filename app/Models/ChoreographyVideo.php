<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Choreography;

class ChoreographyVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'choreography_id',
        'url',
        'ai_edited',
        'ai_edited_url',
    ];

    protected $casts = [
        'ai_edited' => 'boolean',
    ];

    public function choreography()
    {
        return $this->belongsTo(Choreography::class);
    }

    // frames() メソッドを削除
}