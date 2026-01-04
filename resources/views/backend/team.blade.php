@extends('backend.layouts.admin')

@section('title', 'Manage Team')

@section('content')
<div class="reveal">
    <div class="card glass welcome-card" style="margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center;">
        <div class="welcome-info">
            <h1 class="gradient-text">Team Management</h1>
            <p>Atur anggota tim profesional Anda di sini.</p>
        </div>
        <button class="btn-neon" onclick="toggleModal()">+ Add Member</button>
    </div>

    <div class="card glass table-section">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teams as $member)
                <tr>
                    <td>
                        <img src="{{ asset($member->photo) }}" style="width: 40px; height: 40px; border-radius: 50%; border: 2px solid var(--primary); object-fit: cover;">
                    </td>
                    <td>{{ $member->name }}</td>
                    <td><span class="badge pending">{{ $member->role }}</span></td>
                    <td>{{ $member->email }}</td>
                    <td>
                        <div style="display: flex; gap: 10px;">
                            <button type="button" 
                                    onclick='openEditModal({!! $member->toJson() !!})' 
                                    style="background:transparent; border:none; color:var(--secondary); cursor:pointer; font-size: 18px;">
                                <i class='bx bx-edit'></i>
                            </button>

                            <form action="{{ route('admin.team.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Hapus member ini?')">
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

<div id="addModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); z-index:9999; place-items:center;">
    <div class="card glass" style="width: 500px; padding: 30px;">
        <h3 class="neon-text" style="margin-bottom: 20px;">Add New Member</h3>
        <form action="{{ route('admin.team.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="input-group" style="margin-bottom: 15px;">
                <input type="text" name="name" placeholder="Full Name" required 
                       style="width:100%; background:var(--glass); border:1px solid var(--glass-border); padding:10px; color:white; border-radius:8px;">
            </div>
            <div class="input-group" style="margin-bottom: 15px;">
                <input type="text" name="role" placeholder="Role" required 
                       style="width:100%; background:var(--glass); border:1px solid var(--glass-border); padding:10px; color:white; border-radius:8px;">
            </div>
            <div class="input-group" style="margin-bottom: 15px;">
                <input type="email" name="email" placeholder="Email" required 
                       style="width:100%; background:var(--glass); border:1px solid var(--glass-border); padding:10px; color:white; border-radius:8px;">
            </div>
            <div class="input-group" style="margin-bottom: 15px;">
                <textarea name="alamat" placeholder="Address" required 
                          style="width:100%; background:var(--glass); border:1px solid var(--glass-border); padding:10px; color:white; border-radius:8px;"></textarea>
            </div>
            <div class="input-group" style="margin-bottom: 15px;">
                <label style="font-size: 12px; color: #888;">Photo Profile</label>
                <input type="file" name="photo" style="color: #888; font-size: 12px; margin-top: 5px;">
            </div>
            
            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                <button type="button" onclick="toggleModal()" style="background:transparent; border:none; color:white; cursor:pointer;">Cancel</button>
                <button type="submit" class="btn-neon">Save Member</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); z-index:9999; place-items:center;">
    <div class="card glass" style="width: 500px; padding: 30px;">
        <h3 class="neon-text" style="margin-bottom: 20px; color: var(--secondary);">Edit Member</h3>
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="input-group" style="margin-bottom: 15px;">
                <input type="text" name="name" id="edit_name" required 
                       style="width:100%; background:var(--glass); border:1px solid var(--glass-border); padding:10px; color:white; border-radius:8px;">
            </div>
            <div class="input-group" style="margin-bottom: 15px;">
                <input type="text" name="role" id="edit_role" required 
                       style="width:100%; background:var(--glass); border:1px solid var(--glass-border); padding:10px; color:white; border-radius:8px;">
            </div>
            <div class="input-group" style="margin-bottom: 15px;">
                <input type="email" name="email" id="edit_email" required 
                       style="width:100%; background:var(--glass); border:1px solid var(--glass-border); padding:10px; color:white; border-radius:8px;">
            </div>
            <div class="input-group" style="margin-bottom: 15px;">
                <textarea name="alamat" id="edit_alamat" required 
                          style="width:100%; background:var(--glass); border:1px solid var(--glass-border); padding:10px; color:white; border-radius:8px;"></textarea>
            </div>
            <div class="input-group" style="margin-bottom: 15px;">
                <label style="font-size: 12px; color: #888;">Change Photo (Optional)</label>
                <input type="file" name="photo" style="color: #888; font-size: 12px; margin-top: 5px; display: block;">
            </div>
            
            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                <button type="button" onclick="closeEditModal()" style="background:transparent; border:none; color:white; cursor:pointer;">Cancel</button>
                <button type="submit" class="btn-neon" style="background: var(--secondary);">Update Member</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Fungsi untuk Modal Tambah
    function toggleModal() {
        const modal = document.getElementById('addModal');
        modal.style.display = modal.style.display === 'none' ? 'grid' : 'none';
    }

    // Fungsi untuk Modal Edit
    window.openEditModal = function(data) {
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');
        
        if(modal && form) {
            // Set URL Action Form secara dinamis
            form.action = `/admin/team/${data.id}`;
            
            // Isi data ke input field
            document.getElementById('edit_name').value = data.name;
            document.getElementById('edit_role').value = data.role;
            document.getElementById('edit_email').value = data.email;
            document.getElementById('edit_alamat').value = data.alamat;

            modal.style.display = 'grid';
        }
    }

    window.closeEditModal = function() {
        document.getElementById('editModal').style.display = 'none';
    }

    // Close modal jika klik background
    window.onclick = function(event) {
        if (event.target == document.getElementById('addModal')) toggleModal();
        if (event.target == document.getElementById('editModal')) closeEditModal();
    }
</script>
@endsection