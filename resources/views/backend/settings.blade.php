@extends('backend.layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="reveal">
    <div class="card glass welcome-card" style="margin-bottom: 25px;">
        <div class="welcome-info">
            <h1 class="gradient-text">Web Configuration</h1>
            <p>Perbarui informasi dasar identitas situs web Anda di sini.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="notif success" style="margin-bottom: 20px; display: flex; align-items: center; gap: 10px; background: rgba(44, 232, 185, 0.1); padding: 15px; border-radius: 12px; border: 1px solid var(--primary);">
            <i class='bx bx-check-circle' style="color: var(--primary); font-size: 24px;"></i>
            <span style="color: var(--primary);">{{ session('success') }}</span>
        </div>
    @endif

    <div class="card glass">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="input-group">
                    <label style="color: var(--text); opacity: 0.7; font-size: 13px;">Nama Judul Web</label>
                    <div class="input-field" style="margin-top: 8px;">
                        <i class='bx bx-globe' style="position: absolute; left: 15px; color: var(--primary);"></i>
                        <input type="text" name="title" value="{{ old('title', $setting->title) }}" 
                               style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); padding: 12px 15px 12px 45px; border-radius: 10px; color: white; outline: none;" required>
                    </div>
                </div>

                <div class="input-group">
                    <label style="color: var(--text); opacity: 0.7; font-size: 13px;">Sub Judul Web</label>
                    <div class="input-field" style="margin-top: 8px;">
                        <i class='bx bx-text' style="position: absolute; left: 15px; color: var(--secondary);"></i>
                        <input type="text" name="subtitle" value="{{ old('subtitle', $setting->subtitle) }}" 
                               style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); padding: 12px 15px 12px 45px; border-radius: 10px; color: white; outline: none;" required>
                    </div>
                </div>
            </div>

            <div class="input-group" style="margin-top: 20px;">
                <label style="color: var(--text); opacity: 0.7; font-size: 13px;">Copyright Text</label>
                <div class="input-field" style="margin-top: 8px;">
                    <i class='bx bx-copyright' style="position: absolute; left: 15px; color: #ffab00;"></i>
                    <input type="text" name="copyright" value="{{ old('copyright', $setting->copyright) }}" 
                           style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); padding: 12px 15px 12px 45px; border-radius: 10px; color: white; outline: none;" required>
                </div>
            </div>

            <div style="margin-top: 30px; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn-neon" style="min-width: 150px;">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection