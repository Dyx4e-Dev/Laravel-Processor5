@extends('backend.layouts.admin')
@section('title', 'Glossary')

@section('content')
<div class="reveal">
    <div class="card glass" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding: 20px;">
        <div>
            <h1 class="gradient-text">Glossary Management</h1>
            <p>Kelola istilah-istilah arsitektur komputer.</p>
        </div>
        <button class="btn-neon" onclick="handleOpenAddModal()">+ Tambah Istilah</button>
    </div>

    <div class="card glass">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul</th>
                    <th>Penjelasan</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($glossaries as $g)
                <tr>
                    <td>#{{ $g->id }}</td>
                    <td style="color: var(--primary); font-weight: 600;">{{ $g->title }}</td>
                    <td style="max-width: 400px; color: #ccc;">{{ Str::limit($g->explanation, 100) }}</td>
                    <td style="text-align: right;">
                        <div style="display: flex; gap: 15px; justify-content: flex-end;">
                            <button onclick='handleOpenEditModal({!! $g->toJson() !!})' style="background:none; border:none; color:var(--secondary); cursor:pointer;">
                                <i class='bx bx-edit-alt' style="font-size: 20px;"></i>
                            </button>
                            
                            <form action="{{ route('glossary.destroy', $g->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
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
        <h3 class="neon-text">Tambah Glosarium</h3>
        <form action="{{ route('glossary.store') }}" method="POST">
            @csrf
            <input type="text" name="title" placeholder="Judul Istilah" required class="glass-input">
            <textarea name="explanation" placeholder="Penjelasan Lengkap" required class="glass-input" style="height: 150px;"></textarea>
            <div class="modal-footer">
                <button type="button" onclick="closeAllModals()" class="btn-ghost">Batal</button>
                <button type="submit" class="btn-neon">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="modal-overlay" style="display:none;">
    <div class="card glass modal-content">
        <h3 class="neon-text" style="color: var(--secondary);">Edit Glosarium</h3>
        <form id="editFormGlossary" method="POST">
            @csrf @method('PUT')
            <input type="text" name="title" id="edit_title_input" required class="glass-input">
            <textarea name="explanation" id="edit_explanation_input" required class="glass-input" style="height: 150px;"></textarea>
            <div class="modal-footer">
                <button type="button" onclick="closeAllModals()" class="btn-ghost">Batal</button>
                <button type="submit" class="btn-neon" style="background: var(--secondary);">Update</button>
            </div>
        </form>
    </div>
</div>

<style>
    .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.85); z-index: 9999; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(8px); }
    .modal-content { width: 95%; max-width: 500px; padding: 30px; position: relative; }
    .glass-input { width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); padding: 12px; color: white; border-radius: 10px; margin-bottom: 15px; outline: none; }
    .modal-footer { display: flex; justify-content: flex-end; gap: 10px; }
    .btn-ghost { background: transparent; border: none; color: white; cursor: pointer; padding: 10px 20px; }
</style>

<script>
    // 1. Fungsi tutup semua modal untuk memastikan tidak ada tumpang tindih
    function closeAllModals() {
        document.getElementById('addModal').style.display = 'none';
        document.getElementById('editModal').style.display = 'none';
    }

    // 2. Fungsi buka modal tambah
    function handleOpenAddModal() {
        closeAllModals();
        document.getElementById('addModal').style.display = 'flex';
    }

    // 3. Fungsi buka modal edit dengan pengisian data
    window.handleOpenEditModal = function(data) {
        closeAllModals();
        
        const form = document.getElementById('editFormGlossary');
        // Pastikan endpoint URL benar
        form.action = "/admin/glossary/" + data.id; 
        
        // Isi data (Pastikan data.title dan data.explanation sesuai dengan database)
        document.getElementById('edit_title_input').value = data.title;
        document.getElementById('edit_explanation_input').value = data.explanation;
        
        document.getElementById('editModal').style.display = 'flex';
    }

    // 4. Klik di luar modal untuk menutup
    window.onclick = function(event) {
        if (event.target.classList.contains('modal-overlay')) {
            closeAllModals();
        }
    }
</script>
@endsection