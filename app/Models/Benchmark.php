<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Benchmark extends Model
{
    public function result() {
        return $this->hasOne(BenchmarkResult::class);
    }

    public function bestCpus() {
        return $this->hasMany(BestCpu::class);
    }
}
