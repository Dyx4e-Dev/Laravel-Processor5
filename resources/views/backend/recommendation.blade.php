@extends('backend.layouts.admin')

@section('title', 'Manage Laptops')

@section('content')
<div class="reveal">
    <div class="card glass welcome-card" style="margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center;">
        <div class="welcome-info">
            <h1 class="gradient-text">Laptop Inventory</h1>
            <p>Kelola katalog laptop, spesifikasi teknis, dan rekomendasi penggunaan.</p>
        </div>
        <button class="btn-neon" onclick="toggleModal()">+ Add Laptop</button>
    </div>

    <div class="card glass table-section">
        <table class="custom-table">
            <thead>
                <tr>
                    <th style="text-align: center">No</th>
                    <th>Laptop</th>
                    <th>Spesifikasi</th>
                    <th>Harga</th>
                    <th style="text-align: center;">Rekomendasi</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($laptops as $laptop)
                <tr>
                    <td style="color: var(--primary); font-weight: bold; text-align: center;">#{{ $loop->iteration }}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 15px;">
                            @if($laptop->photo)
                                <img src="{{ asset($laptop->photo) }}" style="width: 60px; height: 40px; border-radius: 5px; object-fit: cover; border: 1px solid var(--glass-border);">
                            @else
                                <div style="width: 60px; height: 40px; background: var(--glass); border-radius: 5px; display: flex; align-items: center; justify-content: center; font-size: 10px; color: var(--gray);">No Image</div>
                            @endif
                            <div>
                                <strong style="display: block;">{{ $laptop->name }}</strong>
                                <small style="color: var(--secondary);">{{ $laptop->brand }}</small>
                            </div>
                        </div>
                    </td>
                    <td style="font-size: 12px; line-height: 1.4;">
                        <i class='bx bx-chip'></i> {{ $laptop->processor }} | RAM {{ $laptop->ram }} <br>
                        <i class='bx bx-hdd'></i> {{ $laptop->storage }} | {{ $laptop->vga }}
                    </td>
                    <td style="color: #2ecc71; font-weight: bold;">Rp {{ number_format($laptop->price, 0, ',', '.') }}</td>
                    <td style="text-align: center;">
                        @foreach($laptop->recommendation as $rec)
                            <span class="badge" style="background: rgba(255,255,255,0.1); font-size: 10px; padding: 2px 6px; border-radius: 4px; border: 1px solid var(--glass-border); margin: 1px;">
                                {{ is_array($rec) ? strtoupper(implode(', ', $rec)) : strtoupper($rec) }}
                            </span>
                        @endforeach
                    </td>
                    <td>
                        <div style="display: flex; gap: 10px; justify-content: center;">
                            <button type="button" class="btn-edit" data-laptop='@json($laptop)'
                                    style="background:transparent; border:none; color:var(--secondary); cursor:pointer; font-size: 18px;">
                                <i class='bx bx-edit'></i>
                            </button>

                            <form action="{{ route('admin.recommendation.destroy', $laptop->id) }}" method="POST" onsubmit="return confirm('Hapus laptop ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:transparent; border:none; color:#ff3e1d; cursor:pointer; font-size: 18px;">
                                    <i class='bx bx-trash'></i>
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

<div id="addModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); z-index:9999; place-items:center; overflow-y: auto; padding: 20px;">
    <div class="card glass" style="width: 700px; padding: 30px;">
        <h3 class="neon-text" style="margin-bottom: 20px;">Add New Laptop</h3>
        <form action="{{ route('admin.recommendation.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <input type="text" name="name" placeholder="Laptop Name" required class="glass-input">
                <input type="text" name="brand" placeholder="Brand (e.g., ASUS, Apple)" required class="glass-input">
                <input type="text" name="processor" placeholder="Processor" required class="glass-input">
                <input type="text" name="ram" placeholder="RAM (e.g., 16GB)" required class="glass-input">
                <input type="text" name="storage" placeholder="Storage (e.g., 512GB SSD)" required class="glass-input">
                <input type="text" name="vga" placeholder="Graphics Card" required class="glass-input">
                <input type="text" name="screen_size" placeholder="Screen Size" required class="glass-input">
                <input type="number" name="price" placeholder="Price" required class="glass-input">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
                <div>
                    <label style="color: var(--primary); font-size: 12px; display:block; margin-bottom: 5px;">Recommendation Usage:</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 5px; color: white; font-size: 12px;">
                        <label><input type="checkbox" name="recommendation[]" value="gaming"> Gaming</label>
                        <label><input type="checkbox" name="recommendation[]" value="productivity"> Productivity</label>
                        <label><input type="checkbox" name="recommendation[]" value="programming"> Programming</label>
                        <label><input type="checkbox" name="recommendation[]" value="content-creation"> Content Creation</label>
                    </div>
                </div>
                <div>
                    <label style="color: var(--primary); font-size: 12px; display:block; margin-bottom: 5px;">App Usage Type:</label>
                    <select name="app_usage" required class="glass-input">
                        <option value="single-threaded">Single-Threaded</option>
                        <option value="multi-threaded">Multi-Threaded</option>
                    </select>
                </div>
            </div>

            <div class="input-group" style="margin-bottom: 15px;">
                <label style="color: var(--gray); font-size: 12px;">Photo</label>
                <input type="file" name="photo" class="glass-input" style="padding: 5px;">
            </div>
            
            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                <button type="button" onclick="toggleModal()" style="background:transparent; border:none; color:white; cursor:pointer;">Cancel</button>
                <button type="submit" class="btn-neon">Save Laptop</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); z-index:9999; place-items:center; overflow-y: auto; padding: 20px;">
    <div class="card glass" style="width: 700px; padding: 30px;">
        <h3 class="neon-text" style="margin-bottom: 20px;">Edit Laptop</h3>
        <form action="" method="POST" id="editForm" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <input type="text" name="name" id="edit_name" placeholder="Laptop Name" required class="glass-input">
                <input type="text" name="brand" id="edit_brand" placeholder="Brand (e.g., ASUS, Apple)" required class="glass-input">
                <input type="text" name="processor" id="edit_processor" placeholder="Processor" required class="glass-input">
                <input type="text" name="ram" id="edit_ram" placeholder="RAM (e.g., 16GB)" required class="glass-input">
                <input type="text" name="storage" id="edit_storage" placeholder="Storage (e.g., 512GB SSD)" required class="glass-input">
                <input type="text" name="vga" id="edit_vga" placeholder="Graphics Card" required class="glass-input">
                <input type="text" name="screen_size" id="edit_screen_size" placeholder="Screen Size" required class="glass-input">
                <input type="number" name="price" id="edit_price" placeholder="Price" required class="glass-input">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
                <div>
                    <label style="color: var(--primary); font-size: 12px; display:block; margin-bottom: 5px;">Recommendation Usage:</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 5px; color: white; font-size: 12px;">
                        <label><input type="checkbox" name="recommendation[]" value="gaming" id="edit_gaming"> Gaming</label>
                        <label><input type="checkbox" name="recommendation[]" value="productivity" id="edit_productivity"> Productivity</label>
                        <label><input type="checkbox" name="recommendation[]" value="programming" id="edit_programming"> Programming</label>
                        <label><input type="checkbox" name="recommendation[]" value="content-creation" id="edit_content_creation"> Content Creation</label>
                    </div>
                </div>
                <div>
                    <label style="color: var(--primary); font-size: 12px; display:block; margin-bottom: 5px;">App Usage Type:</label>
                    <select name="app_usage" id="edit_app_usage" required class="glass-input">
                        <option value="single-threaded">Single-Threaded</option>
                        <option value="multi-threaded">Multi-Threaded</option>
                    </select>
                </div>
            </div>

            <div class="input-group" style="margin-bottom: 15px;">
                <label style="color: var(--gray); font-size: 12px;">Photo</label>
                <input type="file" name="photo" class="glass-input" style="padding: 5px;">
                <div id="currentPhoto" style="margin-top: 10px;"></div>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                <button type="button" onclick="closeEditModal()" style="background:transparent; border:none; color:white; cursor:pointer;">Cancel</button>
                <button type="submit" class="btn-neon">Update Laptop</button>
            </div>
        </form>
    </div>
</div>

<style>
    .glass-input {
        width: 100%;
        background: var(--glass);
        border: 1px solid var(--glass-border);
        padding: 10px;
        color: white;
        border-radius: 8px;
    }
    .badge { padding: 4px 8px; border-radius: 4px; font-size: 11px; }
</style>

<script>
    function toggleModal() {
        const modal = document.getElementById('addModal');
        modal.style.display = modal.style.display === 'none' ? 'grid' : 'none';
    }

    function closeEditModal() {
        const modal = document.getElementById('editModal');
        modal.style.display = 'none';
    }

    // Event listener untuk tombol edit
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-edit');
        if (!btn) return;

        const data = JSON.parse(btn.getAttribute('data-laptop'));
        console.log("Editing laptop:", data);

        // Populate edit form with existing data
        document.getElementById('edit_name').value = data.name;
        document.getElementById('edit_brand').value = data.brand;
        document.getElementById('edit_processor').value = data.processor;
        document.getElementById('edit_ram').value = data.ram;
        document.getElementById('edit_storage').value = data.storage;
        document.getElementById('edit_vga').value = data.vga;
        document.getElementById('edit_screen_size').value = data.screen_size;
        document.getElementById('edit_price').value = data.price;
        document.getElementById('edit_app_usage').value = data.app_usage;

        // Handle recommendation checkboxes
        document.getElementById('edit_gaming').checked = data.recommendation.includes('gaming');
        document.getElementById('edit_productivity').checked = data.recommendation.includes('productivity');
        document.getElementById('edit_programming').checked = data.recommendation.includes('programming');
        document.getElementById('edit_content_creation').checked = data.recommendation.includes('content-creation');

        // Show current photo if exists
        const currentPhotoDiv = document.getElementById('currentPhoto');
        if (data.photo) {
            currentPhotoDiv.innerHTML = '<img src="/' + data.photo + '" style="width: 100px; height: 60px; border-radius: 5px; object-fit: cover; border: 1px solid var(--glass-border);">';
        } else {
            currentPhotoDiv.innerHTML = '<small style="color: var(--gray);">No current photo</small>';
        }

        // Update form action
        document.getElementById('editForm').action = '{{ url("admin/recommendations") }}/' + data.id;

        // Show edit modal
        document.getElementById('editModal').style.display = 'grid';
    });
</script>
@endsection