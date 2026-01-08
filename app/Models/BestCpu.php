<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BestCpu extends Model
{
    protected $fillable = [
        'benchmark_id',
        'cpu_name',
        'description',
    ];

    public function benchmark()
    {
        return $this->belongsTo(Benchmark::class);
    }
}
