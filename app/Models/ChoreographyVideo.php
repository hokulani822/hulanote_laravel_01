<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChoreographyVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'choreography_id',
        'url',
    ];

    public function choreography()
    {
        return $this->belongsTo(Choreography::class);
    }
}