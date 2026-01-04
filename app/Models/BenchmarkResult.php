<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BenchmarkResult extends Model
{
    protected $fillable = ['nama', 'email', 'team_id', 'score'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
