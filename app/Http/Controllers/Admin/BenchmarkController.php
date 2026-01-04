<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Benchmark;
use App\Models\BenchmarkResult;
use App\Models\BestCpu;

class BenchmarkController extends Controller
{
    public function index()
{
    // Eager loading semua relasi agar performa cepat
    $benchmarks = Benchmark::with(['result', 'bestCpus'])->get();
    return view('backend.benchmark', compact('benchmarks'));
}
public function store(Request $request)
{
    // 1. Simpan Benchmark Baru
    $benchmark = Benchmark::create([
        'name' => $request->name,
        // Inisialisasi default core scores
        'scores' => json_encode([
            "1 Core" => 0, "2 Cores" => 0, "4 Cores" => 0, 
            "6 Cores" => 0, "8 Cores" => 0, "12 Cores" => 0, "16 Cores" => 0
        ])
    ]);

    // 2. Buat Result Detail Kosong
    $benchmark->result()->create([
        'best_core' => '',
        'analysis' => ''
    ]);

    return redirect()->back()->with('success', 'Benchmark created successfully!');
}

public function destroy($id)
{
    $benchmark = Benchmark::findOrFail($id);
    // Hapus relasi terlebih dahulu jika tidak ada cascade delete di database
    $benchmark->result()->delete();
    $benchmark->bestCpus()->delete();
    $benchmark->delete();

    return redirect()->back()->with('success', 'Benchmark deleted successfully!');
}
    public function storeOrUpdate(Request $request, $id)
{
    // 1. Update Scores pada Benchmark
    $benchmark = Benchmark::findOrFail($id);
    $benchmark->update(['scores' => $request->scores]);

    // 2. Update Narasi (Result)
    $benchmark->result()->updateOrCreate(
        ['benchmark_id' => $id],
        [
            'best_core' => $request->best_core,
            'analysis'  => $request->analysis
        ]
    );

    // 3. Update Best CPUs (Sync)
    $benchmark->bestCpus()->delete();
    foreach ($request->cpus as $cpu) {
        if ($cpu['name']) {
            $benchmark->bestCpus()->create([
                'cpu_name'    => $cpu['name'],
                'description' => $cpu['desc']
            ]);
        }
    }

    return back()->with('success', 'Benchmark configuration updated!');
}
}
