<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Benchmark;
use App\Models\BenchmarkResult;
use App\Models\BestCpu;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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


   public function edit($id)
{
    $benchmark = Benchmark::with(['result', 'bestCpus'])->findOrFail($id);
    
    // Memanggil view > backend > edit_benchmark.blade.php
    return view('backend.edit_benchmark', compact('benchmark'));
}

    public function update(Request $request, $id)
    {
        $benchmark = Benchmark::findOrFail($id);

        $request->validate([
            'scores' => 'nullable|array',
            'scores.*' => 'nullable|numeric',
            'best_core' => ['required', Rule::in([1,2,4,6,8,12,16])],
            'core_description' => 'nullable|string',
            'analysis_text' => 'nullable|string',
            'recommended_cpu' => 'nullable|array',
            'recommended_cpu.*' => 'nullable|string|max:255',
            'cpu_descriptions' => 'nullable|array',
            'cpu_descriptions.*' => 'nullable|string',
        ]);

        DB::transaction(function() use ($request, $benchmark) {
            if ($request->has('scores')) {
                $benchmark->scores = $request->input('scores');
                $benchmark->save();
            }

            $benchmark->result()->updateOrCreate(
                ['benchmark_id' => $benchmark->id],
                [
                    'best_core' => $request->input('best_core'),
                    'desc_core' => $request->input('core_description'),
                    'analysis' => $request->input('analysis_text'),
                ]
            );

            // Sync best CPUs: delete existing then create new
            $benchmark->bestCpus()->delete();
            $cpus = $request->input('recommended_cpu', []);
            $descs = $request->input('cpu_descriptions', []);
            foreach ($cpus as $index => $cpuName) {
                if ($cpuName === null || trim($cpuName) === '') continue;
                $description = $descs[$index] ?? '';
                $benchmark->bestCpus()->create([
                    'cpu_name' => $cpuName,
                    'description' => $description,
                ]);
            }
        });

        return redirect()->route('admin.benchmark')->with('success', 'Analisis berhasil diperbarui!');
    }
}
