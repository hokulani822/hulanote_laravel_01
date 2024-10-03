<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Choreography; 


class Song extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'title', 'artist'];
    
     public function choreography()
    {
        return $this->hasOne(Choreography::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}


