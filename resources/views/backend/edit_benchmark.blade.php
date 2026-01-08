@extends('backend.layouts.admin')

@section('title', 'Edit Benchmark: ' . $benchmark->name)

@section('content')
<div>
    <div class="card glass welcome-card" style="margin-bottom: 25px;">
        <div class="welcome-info">
            <h1 class="gradient-text">Edit Analysis: {{ $benchmark->name }}</h1>
            <p>Berikan rekomendasi spesifik dan analisis mendalam berdasarkan data benchmark.</p>
        </div>
    </div>

    <form action="{{ route('benchmarks.update', $benchmark->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <div class="card glass">
                    <h3 style="margin-bottom: 20px; color: var(--primary);"><i class='bx bx-chip'></i> Rekomendasi Core Terbaik</h3>
                    
                    <div class="input-group" style="margin-bottom: 15px;">
                        <label style="color: var(--text); opacity: 0.7; font-size: 13px;">Pilih Core Optimal</label>
                        <select name="best_core" style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); padding: 12px; border-radius: 10px; color: white; outline: none; margin-top: 8px;">
                            @foreach([1, 2, 4, 6, 8] as $core)
                                <option value="{{ $core }}" {{ old('best_core', $benchmark->result?->best_core) == $core ? 'selected' : '' }}>{{ $core }} Core</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-group">
                        <label style="color: var(--text); opacity: 0.7; font-size: 13px;">Deskripsi Penjelasan Core</label>
                        <textarea name="core_description" rows="4" style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); padding: 12px; border-radius: 10px; color: white; outline: none; margin-top: 8px; resize: none;">{{ old('core_description', $benchmark->result?->desc_core) }}</textarea>
                    </div>
                </div>

                <div class="card glass">
                    <h3 style="margin-bottom: 20px; color: #00d2ff;"><i class='bx bx-bar-chart-alt-2'></i> Benchmark Scores (%)</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        @php
                            $scores = is_array($benchmark->scores) ? $benchmark->scores : json_decode($benchmark->scores, true);
                            $labels = ["1 Core", "2 Cores", "4 Cores", "6 Cores", "8 Cores"];
                        @endphp
                        @foreach($labels as $label)
                        <div class="input-group">
                            <label style="color: var(--text); opacity: 0.7; font-size: 12px;">{{ $label }}</label>
                            <input type="number" name="scores[{{ $label }}]" value="{{ old('scores.'. $label, $scores[$label] ?? 0) }}" 
                                style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); padding: 10px; border-radius: 8px; color: white; outline: none; margin-top: 5px;">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card glass">
                <h3 style="margin-bottom: 20px; color: var(--secondary); display: flex; justify-content: space-between; align-items: center;">
                    <span><i class='bx bx-microchip'></i> Rekomendasi CPU Spesifik</span>
                    <button type="button" id="add-cpu-btn" class="btn-neon" style="padding: 5px 15px; font-size: 12px; min-width: auto;">+ Tambah</button>
                </h3>
                
                <div id="cpu-container">
                    @php
                        $cpus = old('recommended_cpu', []);
                        $descriptions = old('cpu_descriptions', []);
                        
                        // If no old input, load from bestCpus relationship
                        if (empty($cpus) && $benchmark->bestCpus) {
                            foreach ($benchmark->bestCpus as $cpu) {
                                $cpus[] = $cpu->cpu_name;
                                $descriptions[] = $cpu->description;
                            }
                        }
                        
                        // Ensure at least one empty field for adding new CPU
                        if (empty($cpus)) {
                            $cpus = [''];
                            $descriptions = [''];
                        }
                    @endphp

                    @foreach($cpus as $index => $value)
                    <div class="cpu-item" style="background: rgba(255,255,255,0.02); padding: 15px; border-radius: 12px; border: 1px solid var(--glass-border); margin-bottom: 15px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <label style="color: var(--secondary); font-weight: bold; font-size: 13px;">CPU #{{ $index + 1 }}</label>
                            @if($index > 0)
                                <button type="button" class="remove-cpu" style="background: #ff6b6b; border: none; border-radius: 5px; color: white; cursor: pointer; padding: 2px 8px; font-size: 11px;">Hapus</button>
                            @endif
                        </div>
                        <input type="text" name="recommended_cpu[]" value="{{ $value }}" 
                            style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); padding: 10px; border-radius: 8px; color: white; outline: none; margin-bottom: 10px;"
                            placeholder="Contoh: Intel Core i5-13400">
                        
                        <textarea name="cpu_descriptions[]" rows="2" 
                            style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); padding: 10px; border-radius: 8px; color: white; outline: none; resize: none;"
                            placeholder="Deskripsi untuk CPU ini...">{{ $descriptions[$index] ?? '' }}</textarea>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card glass" style="margin-top: 20px;">
            <h3 style="margin-bottom: 20px; color: #ffab00;"><i class='bx bx-analyse'></i> Analisis Mendalam</h3>
            <textarea name="analysis_text" rows="6" style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); padding: 15px; border-radius: 12px; color: white; outline: none;">{{ old('analysis_text', $benchmark->result?->analysis) }}</textarea>
        </div>

        <div style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 15px;">
            <a href="{{ route('admin.benchmark') }}" style="text-decoration: none; padding: 12px 25px; color: var(--gray);">Batal</a>
            <button type="submit" class="btn-neon" style="min-width: 200px;">Simpan Perubahan</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('cpu-container');
    const addBtn = document.getElementById('add-cpu-btn');

    addBtn.addEventListener('click', () => {
        const itemCount = container.querySelectorAll('.cpu-item').length + 1;
        const div = document.createElement('div');
        div.className = 'cpu-item';
        div.style.cssText = "background: rgba(255,255,255,0.02); padding: 15px; border-radius: 12px; border: 1px solid var(--glass-border); margin-bottom: 15px;";
        
        div.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <label style="color: var(--secondary); font-weight: bold; font-size: 13px;">CPU #${itemCount}</label>
                <button type="button" class="remove-cpu" style="background: #ff6b6b; border: none; border-radius: 5px; color: white; cursor: pointer; padding: 2px 8px; font-size: 11px;">Hapus</button>
            </div>
            <input type="text" name="recommended_cpu[]" style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); padding: 10px; border-radius: 8px; color: white; outline: none; margin-bottom: 10px;" placeholder="Nama Processor">
            <textarea name="cpu_descriptions[]" rows="2" style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); padding: 10px; border-radius: 8px; color: white; outline: none; resize: none;" placeholder="Deskripsi untuk CPU ini..."></textarea>
        `;
        container.appendChild(div);
    });

    container.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-cpu')) {
            e.target.closest('.cpu-item').remove();
        }
    });
});
</script>
@endsection