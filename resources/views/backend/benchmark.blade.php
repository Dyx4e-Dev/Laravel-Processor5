@extends('backend.layouts.admin')
@section('title', 'Benchmark Management')

@section('content')
<div class="container-fluid pb-5">
    <div class="card glass mb-4" style="display: flex; justify-content: space-between; align-items: center; padding: 20px;">
        <div>
            <h1 class="gradient-text">Benchmark Management</h1>
            <p class="text-secondary">Manage performance scores, technical analysis, and CPU recommendations.</p>
        </div>
        <button class="btn-neon" onclick="handleOpenAddModal()">+ Add New Benchmark</button>
    </div>

    @if(session('success'))
        <div class="alert alert-success bg-dark text-primary border-primary">
            {{ session('success') }}
        </div>
    @endif

    <div class="card glass">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Benchmark Name</th>
                    <th>Scores</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($benchmarks as $benchmark)
                <tr>
                    <td>#{{ $benchmark->id }}</td>
                    <td style="color: var(--primary); font-weight: 600; text-transform: uppercase;">{{ $benchmark->name }}</td>
                    <td style="color: #ccc;">
                        @php $scores = is_array($benchmark->scores) ? $benchmark->scores : json_decode($benchmark->scores, true); @endphp
                        {{ count($scores) }} Core Profiles
                    </td>
                    <td style="text-align: right;">
                        <div style="display: flex; gap: 15px; justify-content: flex-end; align-items: center;">
                            <button onclick='handleOpenEditModal({!! json_encode([
                                "id" => $benchmark->id,
                                "name" => $benchmark->name,
                                "scores" => $scores,
                                "best_core" => $benchmark->result->best_core ?? "",
                                "analysis" => $benchmark->result->analysis ?? "",
                                "cpus" => $benchmark->bestCpus
                            ]) !!})' style="background:none; border:none; color:var(--secondary); cursor:pointer;">
                                <i class='bx bx-edit-alt' style="font-size: 20px;"></i>
                            </button>

                            <form action="{{ route('admin.benchmark.destroy', $benchmark->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:none; border:none; color:#ff4444; cursor:pointer;">
                                    <i class='bx bx-trash' style="font-size: 20px;"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="addModal" class="modal-overlay" style="display:none;">
    <div class="card glass modal-content">
        <h3 class="neon-text">Tambah Benchmark</h3>
        <form action="{{ route('admin.benchmark.store') }}" method="POST">
            @csrf
            <input type="text" name="name" placeholder="Nama Benchmark (Cinebench, etc)" required class="glass-input">
            <p class="text-secondary small italic">*Default cores akan dibuat otomatis.</p>
            <div class="modal-footer">
                <button type="button" onclick="closeAllModals()" class="btn-ghost">Batal</button>
                <button type="submit" class="btn-neon">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="modal-overlay" style="display:none;">
    <div class="card glass modal-content" style="max-width: 800px;">
        <h3 class="neon-text" style="color: var(--secondary);">Edit Configuration</h3>
        <form id="editFormBenchmark" method="POST">
            @csrf @method('PUT')
            
            <div class="row" style="max-height: 60vh; overflow-y: auto; padding: 10px;">
                <div class="col-md-6 border-end border-secondary">
                    <h6 class="text-primary mb-3">Performance Scores (%)</h6>
                    <div id="scores_container">
                        </div>
                </div>

                <div class="col-md-6">
                    <h6 class="text-primary mb-3">Result & Recommendation</h6>
                    <input type="text" name="best_core" id="edit_best_core" placeholder="Best Core Recommendation" class="glass-input">
                    <textarea name="analysis" id="edit_analysis" placeholder="Technical Analysis" class="glass-input" style="height: 100px;"></textarea>
                    
                    <h6 class="text-primary mt-3 mb-2 small">Recommended CPUs</h6>
                    <div id="cpus_container">
                        @for($i = 0; $i < 3; $i++)
                            <input type="text" name="cpus[{{$i}}][name]" id="cpu_name_{{$i}}" placeholder="CPU Name" class="glass-input mb-1 py-1">
                            <input type="text" name="cpus[{{$i}}][desc]" id="cpu_desc_{{$i}}" placeholder="Description" class="glass-input mb-3 py-1 small">
                        @endfor
                    </div>
                </div>
            </div>

            <div class="modal-footer mt-4">
                <button type="button" onclick="closeAllModals()" class="btn-ghost">Batal</button>
                <button type="submit" class="btn-neon" style="background: var(--secondary); color: white;">Update Changes</button>
            </div>
        </form>
    </div>
</div>

<style>
    .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.85); z-index: 9999; display: none; align-items: center; justify-content: center; backdrop-filter: blur(8px); }
    .modal-content { width: 95%; max-width: 500px; padding: 30px; position: relative; border: 1px solid rgba(255,255,255,0.1); }
    .glass-input { width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); padding: 10px; color: white; border-radius: 10px; margin-bottom: 15px; outline: none; transition: 0.3s; }
    .glass-input:focus { border-color: var(--primary); box-shadow: 0 0 10px rgba(0, 255, 157, 0.2); }
    .modal-footer { display: flex; justify-content: flex-end; gap: 10px; }
    .btn-ghost { background: transparent; border: none; color: white; cursor: pointer; padding: 10px 20px; }
    .btn-neon { background: transparent; border: 1px solid var(--primary); color: var(--primary); padding: 10px 25px; border-radius: 30px; font-weight: bold; cursor: pointer; transition: 0.3s; }
    .btn-neon:hover { background: var(--primary); color: #000; box-shadow: 0 0 20px var(--primary); }
</style>

<script>
    function closeAllModals() {
        document.getElementById('addModal').style.display = 'none';
        document.getElementById('editModal').style.display = 'none';
    }

    function handleOpenAddModal() {
        closeAllModals();
        document.getElementById('addModal').style.display = 'flex';
    }

    window.handleOpenEditModal = function(data) {
        closeAllModals();
        
        const form = document.getElementById('editFormBenchmark');
        form.action = "/admin/benchmark/" + data.id; 

        // 1. Isi Data Result
        document.getElementById('edit_best_core').value = data.best_core;
        document.getElementById('edit_analysis').value = data.analysis;

        // 2. Render Score Inputs
        const scoreContainer = document.getElementById('scores_container');
        scoreContainer.innerHTML = '';
        for (const [core, value] of Object.entries(data.scores)) {
            scoreContainer.innerHTML += `
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <label class="small text-light">${core}</label>
                    <input type="number" name="scores[${core}]" value="${value}" class="glass-input text-center mb-0" style="width: 80px;">
                </div>
            `;
        }

        // 3. Isi Data CPU
        for (let i = 0; i < 3; i++) {
            const cpu = data.cpus[i] || { cpu_name: '', description: '' };
            document.getElementById(`cpu_name_${i}`).value = cpu.cpu_name || '';
            document.getElementById(`cpu_desc_${i}`).value = cpu.description || '';
        }

        document.getElementById('editModal').style.display = 'flex';
    }

    window.onclick = function(event) {
        if (event.target.classList.contains('modal-overlay')) {
            closeAllModals();
        }
    }
</script>
@endsection