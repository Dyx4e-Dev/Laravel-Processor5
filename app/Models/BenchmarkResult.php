<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BenchmarkResult extends Model
{
    protected $fillable = [
        'benchmark_id',
        'best_core',
        'desc_core',
        'analysis',
    ];

    public function benchmark()
    {
        return $this->belongsTo(Benchmark::class);
    }

}
