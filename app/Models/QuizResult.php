<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    protected $fillable = ['nama', 'email', 'team_id', 'score', 'reward_status'];

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}


