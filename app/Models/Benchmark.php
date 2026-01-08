<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Benchmark extends Model
{
    // app/Models/Benchmark.php
    protected $fillable = [
        'name', 
        'scores', 
    ];

    protected $casts = [
        'scores' => 'array', // Otomatis konversi JSON ke array
    ];

    public function result() {
        return $this->hasOne(BenchmarkResult::class);
    }

    public function bestCpus() {
        return $this->hasMany(BestCpu::class);
    }
}
