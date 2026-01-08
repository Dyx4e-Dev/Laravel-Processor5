@extends('backend.layouts.admin')

@section('title', 'Log Aktivitas Admin')

@section('content')
<div style="transform: translateY(30px); transition: 0.8s all ease;" class="reveal">
    <div class="card glass welcome-card" style="margin-bottom: 25px;">
        <div class="welcome-info">
            <h1 class="gradient-text">📊 Log Aktivitas Admin</h1>
            <p>Pantau semua aktivitas admin termasuk login, logout, dan perubahan data</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card glass" style="margin-bottom: 25px;">
        <h3 style="margin-bottom: 20px; color: var(--primary);"><i class='bx bx-filter-alt'></i> Filter</h3>
        
        <form method="GET" action="{{ route('admin.activity_logs') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end;">

            <!-- Filter Admin -->
            <div class="input-group">
                <label style="color: var(--text); opacity: 0.7; font-size: 13px;">Admin</label>
                <select name="admin_id" style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); padding: 10px; border-radius: 8px; color: white; outline: none; margin-top: 5px;">
                    <option value="">-- Pilih Admin --</option>
                    @foreach($admins as $admin)
                        <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>
                            {{ $admin->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Activity -->
            <div class="input-group">
                <label style="color: var(--text); opacity: 0.7; font-size: 13px;">Jenis Aktivitas</label>
                <select name="activity" style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); padding: 10px; border-radius: 8px; color: white; outline: none; margin-top: 5px;">
                    <option value="">-- Pilih Aktivitas --</option>
                    @foreach($activities as $activity)
                        <option value="{{ $activity }}" {{ request('activity') == $activity ? 'selected' : '' }}>
                            {{ ucfirst($activity) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Date Range Start -->
            <div class="input-group">
                <label style="color: var(--text); opacity: 0.7; font-size: 13px;">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); padding: 10px; border-radius: 8px; color: white; outline: none; margin-top: 5px;">
            </div>

            <!-- Filter Date Range End -->
            <div class="input-group">
                <label style="color: var(--text); opacity: 0.7; font-size: 13px;">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); padding: 10px; border-radius: 8px; color: white; outline: none; margin-top: 5px;">
            </div>

            <!-- Buttons -->
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn-neon" style="min-width: 120px; margin-top: 0;">
                    <i class='bx bx-search'></i> Filter
                </button>
                <a href="{{ route('admin.activity_logs') }}" style="padding: 10px 20px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: white; text-decoration: none; display: flex; align-items: center; gap: 5px;">
                    <i class='bx bx-refresh'></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Action Buttons -->
    <div style="margin-bottom: 20px; display: flex; gap: 10px;">
        <button type="button" id="btnOpenClearLogModal" class="btn-neon" style="background: #ff6b6b; box-shadow: 0 0 15px rgba(255,107,107,0.4);">
            <i class='bx bx-trash'></i> Hapus Log
        </button>
    </div>

    <!-- Table Section -->
    <div class="card glass">
        <h3 style="margin-bottom: 20px; color: #00d2ff;"><i class='bx bx-list-check'></i> Daftar Aktivitas</h3>
        
        <div style="overflow-x: auto;">
            <table class="custom-table" id="activityLogsTable">
                <thead>
                    <tr style="border-bottom: 2px solid var(--glass-border);">
                        <th style="color: var(--primary); width: 5%;">No</th>
                        <th style="color: var(--primary);">Admin</th>
                        <th style="color: var(--primary);">Aktivitas</th>
                        <th style="color: var(--primary);">Deskripsi</th>
                        <th style="color: var(--primary);">IP Address</th>
                        <th style="color: var(--primary);">Waktu</th>
                        <th style="color: var(--primary);">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr style="border-bottom: 1px solid var(--glass-border);">
                            <td style="color: var(--gray);">{{ ($logs->currentPage() - 1) * $logs->perPage() + $loop->iteration }}</td>
                            <td>
                                <span style="background: rgba(44,232,185,0.15); color: var(--primary); padding: 5px 10px; border-radius: 5px; font-size: 12px;">
                                    {{ $log->admin?->name ?? 'System' }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $activityColors = [
                                        'login' => '#2ce8b9',
                                        'logout' => '#ffab00',
                                        'create' => '#6ec1ff',
                                        'update' => '#3b82f6',
                                        'delete' => '#ff6b6b',
                                    ];
                                    $color = $activityColors[$log->activity] ?? '#888';
                                @endphp
                                <span style="background: rgba({{ str_replace('#', 'rgb(', $color) }}, 0.2); color: {{ $color }}; padding: 5px 10px; border-radius: 5px; font-size: 12px; font-weight: 600;">
                                    {{ ucfirst($log->activity) }}
                                </span>
                            </td>
                            <td style="font-size: 13px; color: var(--text);">
                                {{ Str::limit($log->description, 60) }}
                            </td>
                            <td style="font-size: 12px; color: var(--gray);">
                                {{ $log->ip_address ?? '-' }}
                            </td>
                            <td style="font-size: 12px; color: var(--gray);">
                                {{ $log->created_at->format('d M Y H:i') }}
                            </td>
                            <td style="width: 110px;">
                                @if(!in_array($log->activity, ['login','logout']))
                                    <a href="#" data-log-id="{{ $log->id }}" class="btn-neon" style="padding: 6px 10px; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                                        <i class='bx bx-show'></i> Detail
                                    </a>
                                @else
                                    <span style="color: var(--gray); font-size: 13px;">-</span>
                                @endif
                            </td>
                        </tr>

                        
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 30px; color: var(--gray);">
                                <i class='bx bx-info-circle' style="font-size: 30px; opacity: 0.5;"></i>
                                <p style="margin-top: 10px;">Belum ada data aktivitas</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Hidden details container (rendered outside table to keep HTML valid) --}}
        <div id="hidden-activity-details" style="display:none;">
            @foreach($logs as $log)
                @if(!in_array($log->activity, ['login','logout']))
                    <div id="detail-{{ $log->id }}">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                            <div>
                                <strong>Admin</strong>
                                <div style="margin-top:6px;">{{ $log->admin?->name ?? 'System' }} (ID: {{ $log->admin_id ?? '-' }})</div>
                            </div>
                            <div>
                                <strong>Aktivitas</strong>
                                <div style="margin-top:6px;">{{ ucfirst($log->activity) }}</div>
                            </div>
                            <div>
                                <strong>Waktu</strong>
                                <div style="margin-top:6px;">{{ $log->created_at->format('d M Y H:i:s') }}</div>
                            </div>
                            <div>
                                <strong>IP / User Agent</strong>
                                <div style="margin-top:6px;">{{ $log->ip_address ?? '-' }} / {{ $log->user_agent ?? '-' }}</div>
                            </div>
                        </div>

                        <div style="margin-top: 8px;">
                            <strong>Deskripsi</strong>
                            <div style="margin-top:8px; background: rgba(255,255,255,0.03); padding: 12px; border-radius: 8px; color: var(--text); font-size: 14px;">
                                @php
                                    $raw = $log->description ?? '';
                                    $changes = null;
                                    $attrs = null;

                                    // update: expect JSON at end after '|'
                                    if ($log->activity === 'update' && str_contains($raw, '|')) {
                                        $parts = explode('|', $raw);
                                        $maybeJson = trim(array_pop($parts));
                                        $decoded = json_decode($maybeJson, true);
                                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                            $changes = $decoded;
                                        }
                                    }

                                    // create/delete: parse key: value, key2: value2 after '|'
                                    if (!$changes && str_contains($raw, '|')) {
                                        $parts = explode('|', $raw);
                                        $last = trim(array_pop($parts));
                                        // split by ', ' into pairs
                                        $pairs = preg_split('/,\s*/', $last);
                                        $parsed = [];
                                        foreach ($pairs as $p) {
                                            if (strpos($p, ':') !== false) {
                                                [$k, $v] = explode(':', $p, 2);
                                                $parsed[trim($k)] = trim($v);
                                            }
                                        }
                                        if (count($parsed)) {
                                            $attrs = $parsed;
                                        }
                                    }
                                @endphp

                                @if($changes)
                                    <div style="margin-bottom:8px;">{{ implode('|', array_map('trim', array_slice(explode('|', $raw), 0, -1))) }}</div>
                                    <table style="width:100%; border-collapse: collapse;">
                                        <thead>
                                            <tr style="text-align:left; border-bottom:1px solid rgba(255,255,255,0.06);">
                                                <th style="padding:8px; width:40%;">Field</th>
                                                <th style="padding:8px;">New Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($changes as $field => $value)
                                            <tr style="border-bottom:1px solid rgba(255,255,255,0.03);">
                                                <td style="padding:8px; vertical-align: top; font-weight:600;">{{ $field }}</td>
                                                <td style="padding:8px; white-space:pre-wrap;">{{ is_array($value) ? json_encode($value) : $value }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                @elseif($attrs)
                                    <table style="width:100%; border-collapse: collapse;">
                                        <thead>
                                            <tr style="text-align:left; border-bottom:1px solid rgba(255,255,255,0.06);">
                                                <th style="padding:8px; width:35%;">Field</th>
                                                <th style="padding:8px;">Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($attrs as $k => $v)
                                                <tr style="border-bottom:1px solid rgba(255,255,255,0.03);">
                                                    <td style="padding:8px; vertical-align: top; font-weight:600;">{{ $k }}</td>
                                                    <td style="padding:8px; white-space:pre-wrap;">{{ $v }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                @else
                                    <div>{{ $raw }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Pagination -->
        @if($logs->hasPages())
            <div style="margin-top: 20px; display: flex; justify-content: center;">
                {{ $logs->appends(request()->query())->render('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
</div>

<!-- Detail Modal (AJAX) -->
<div class="modal fade" id="logDetailModal" tabindex="-1" role="dialog" style="display: none; background: rgba(0,0,0,0.7);">
    <div class="modal-dialog" role="document" style="margin-top: 8%; max-width: 800px;">
        <div class="modal-content" style="background: var(--dark); border: 1px solid var(--glass-border); border-radius: 12px; padding: 16px;">
            <div class="modal-header" style="border-bottom: 1px solid var(--glass-border); color: white; display:flex; justify-content:space-between; align-items:center;">
                <h5 class="modal-title"><i class='bx bx-detail' style="color: var(--primary);"></i> Detail Aktivitas</h5>
                <button type="button" id="logDetailClose" aria-label="Close" style="background:none; border:none; color:white; font-size:22px;">&times;</button>
            </div>
            <div class="modal-body" id="logDetailModalBody" style="padding: 12px; color: var(--text);">
                <!-- Content loaded via AJAX -->
            </div>
            <div style="display:flex; justify-content:flex-end; margin-top:12px;">
                <button type="button" id="logDetailClose2" class="btn-neon">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Clear Log - OPTIMIZED -->
<div class="modal fade" id="clearLogModal" tabindex="-1" role="dialog" style="display: none; background: rgba(0,0,0,0.7);">
    <div class="modal-dialog" role="document" style="margin-top: 8%; max-width: 600px;">
        <div class="modal-content" style="background: var(--dark); border: 1px solid var(--glass-border); border-radius: 12px; padding: 0;">
            <div class="modal-header" style="border-bottom: 1px solid var(--glass-border); color: white; display:flex; justify-content:space-between; align-items:center; padding: 16px;">
                <h5 class="modal-title" style="margin: 0;"><i class='bx bx-trash' style="color: #ff6b6b; margin-right: 8px;"></i> Hapus Log</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="background:none; border:none; color:white; font-size:22px; margin: 0; padding: 0;">&times;</button>
            </div>
            <div class="modal-body" style="color: var(--text); padding: 16px;">
                <form id="clearLogForm" method="POST" action="{{ route('admin.activity_logs.destroy') }}">
                    @csrf

                    <!-- Delete Type Selection -->
                    <div style="margin-bottom: 16px;">
                        <label style="color: var(--text); font-size: 13px; font-weight: 500; display: block; margin-bottom: 10px;">Pilih Tipe Penghapusan</label>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <!-- All -->
                            <label style="display: flex; align-items: center; cursor: pointer; padding: 10px 12px; background: rgba(255,255,255,0.02); border-radius: 8px; transition: background 0.2s; border: 1px solid transparent;">
                                <input type="radio" name="clear_type" value="all" checked style="margin-right: 8px; cursor: pointer;">
                                <span style="font-size: 14px;">Hapus Semua Log</span>
                            </label>

                            <!-- By Admin -->
                            <label style="display: flex; align-items: center; cursor: pointer; padding: 10px 12px; background: rgba(255,255,255,0.02); border-radius: 8px; transition: background 0.2s; border: 1px solid transparent;">
                                <input type="radio" name="clear_type" value="admin" style="margin-right: 8px; cursor: pointer;">
                                <span style="font-size: 14px;">Per Admin</span>
                            </label>
                            <select name="admin_id_clear" style="width: calc(100% - 30px); margin-left: 30px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); padding: 8px 10px; border-radius: 6px; color: white; outline: none; font-size: 13px;">
                                <option value="">-- Pilih Admin --</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                @endforeach
                            </select>

                            <!-- By Activity -->
                            <label style="display: flex; align-items: center; cursor: pointer; padding: 10px 12px; background: rgba(255,255,255,0.02); border-radius: 8px; transition: background 0.2s; border: 1px solid transparent;">
                                <input type="radio" name="clear_type" value="activity" style="margin-right: 8px; cursor: pointer;">
                                <span style="font-size: 14px;">Per Aktivitas</span>
                            </label>
                            <select name="activity_clear" style="width: calc(100% - 30px); margin-left: 30px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); padding: 8px 10px; border-radius: 6px; color: white; outline: none; font-size: 13px;">
                                <option value="">-- Pilih Aktivitas --</option>
                                @foreach($activities as $activity)
                                    <option value="{{ $activity }}">{{ ucfirst($activity) }}</option>
                                @endforeach
                            </select>

                            <!-- By Date Range -->
                            <label style="display: flex; align-items: center; cursor: pointer; padding: 10px 12px; background: rgba(255,255,255,0.02); border-radius: 8px; transition: background 0.2s; border: 1px solid transparent;">
                                <input type="radio" name="clear_type" value="date_range" style="margin-right: 8px; cursor: pointer;">
                                <span style="font-size: 14px;">Per Tanggal</span>
                            </label>
                            <div style="margin-left: 30px; display: flex; gap: 8px; align-items: center;">
                                <input type="date" name="start_date_clear" style="flex: 1; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); padding: 8px 10px; border-radius: 6px; color: white; outline: none; font-size: 13px;">
                                <span style="color: var(--text); opacity: 0.5; font-size: 12px;">hingga</span>
                                <input type="date" name="end_date_clear" style="flex: 1; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); padding: 8px 10px; border-radius: 6px; color: white; outline: none; font-size: 13px;">
                            </div>
                        </div>
                    </div>

                    <!-- Warning -->
                    <div style="background: rgba(255,107,107,0.08); border: 1px solid rgba(255,107,107,0.2); padding: 10px 12px; border-radius: 6px; margin-bottom: 16px; color: #ff8080; font-size: 12px; line-height: 1.5;">
                        <i class='bx bx-exclamation-circle' style="margin-right: 4px;"></i> Tindakan tidak dapat dibatalkan
                    </div>
                </form>
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end; padding: 12px 16px; border-top: 1px solid var(--glass-border);">
                <button type="button" class="btn-neon" data-dismiss="modal" style="background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); color: var(--text); box-shadow: none; padding: 8px 16px; font-size: 13px; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" form="clearLogForm" class="btn-neon" style="background: #ff6b6b; box-shadow: 0 0 10px rgba(255,107,107,0.3); padding: 8px 16px; font-size: 13px; cursor: pointer;">
                    <i class='bx bx-trash' style="margin-right: 4px;"></i> Hapus
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Custom Modal Styling -->
<style>
    .modal.fade { z-index: 1050; position: fixed; inset: 0; display: none; align-items: center; justify-content: center; padding: 20px; background: rgba(0,0,0,0.7); }
    .modal.fade.show { display: flex !important; }
    .modal-dialog { max-width: 800px; width: 100%; max-height: 80vh; overflow: auto; }
    .modal-content { width: 100%; }
    .close { font-size: 28px; color: white; opacity: 0.8; cursor: pointer; }
    .close:hover { opacity: 1; }
</style>

<script>
    // Modal control handler
    (function(){
        const modal = document.getElementById('clearLogModal');
        const btnOpen = document.getElementById('btnOpenClearLogModal');
        const btnCloseAll = document.querySelectorAll('[data-dismiss="modal"]');
        
        // Open modal
        if (btnOpen) {
            btnOpen.addEventListener('click', function(e) {
                e.preventDefault();
                modal.classList.add('show');
                modal.style.display = 'flex';
            });
        }
        
        // Close modal on all close buttons
        btnCloseAll.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                modal.classList.remove('show');
                modal.style.display = 'none';
            });
        });
        
        // Close when clicking outside modal
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.remove('show');
                    modal.style.display = 'none';
                }
            });
        }
    })();

    // Clear log form handling
    (function(){
        const form = document.getElementById('clearLogForm');
        if (!form) return;

        form.addEventListener('submit', function(e) {
            const clearType = document.querySelector('input[name="clear_type"]:checked').value;

            if (clearType === 'admin') {
                const val = document.querySelector('select[name="admin_id_clear"]')?.value;
                if (!val) { alert('Pilih admin terlebih dahulu!'); e.preventDefault(); return; }
                const inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = 'admin_id';
                inp.value = val;
                form.appendChild(inp);
            }

            if (clearType === 'activity') {
                const val = document.querySelector('select[name="activity_clear"]')?.value;
                if (!val) { alert('Pilih aktivitas terlebih dahulu!'); e.preventDefault(); return; }
                const inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = 'activity';
                inp.value = val;
                form.appendChild(inp);
            }

            if (clearType === 'date_range') {
                const start = document.querySelector('input[name="start_date_clear"]')?.value;
                const end = document.querySelector('input[name="end_date_clear"]')?.value;
                if (!start || !end) { alert('Pilih tanggal awal dan akhir!'); e.preventDefault(); return; }
                const s = document.createElement('input');
                s.type = 'hidden';
                s.name = 'start_date';
                s.value = start;
                form.appendChild(s);
                const en = document.createElement('input');
                en.type = 'hidden';
                en.name = 'end_date';
                en.value = end;
                form.appendChild(en);
            }

            if (!confirm('Yakin ingin menghapus log? Tindakan ini tidak dapat dibatalkan!')) {
                e.preventDefault();
            }
        });

        // radio change handler
        document.querySelectorAll('input[name="clear_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('select[name*="_clear"]').forEach(select => { select.disabled = true; select.style.opacity = '0.5'; });
                document.querySelectorAll('input[name*="_clear"]').forEach(input => { if (input.type === 'date') { input.disabled = true; input.style.opacity = '0.5'; } });

                if (this.value === 'admin') {
                    const s = document.querySelector('select[name="admin_id_clear"]'); if (s) { s.disabled = false; s.style.opacity = '1'; }
                } else if (this.value === 'activity') {
                    const s = document.querySelector('select[name="activity_clear"]'); if (s) { s.disabled = false; s.style.opacity = '1'; }
                } else if (this.value === 'date_range') {
                    document.querySelectorAll('input[name*="_clear"][type="date"]').forEach(input => { input.disabled = false; input.style.opacity = '1'; });
                }
            });
        });
    })();

    // Detail modal via AJAX
    (function(){
        const modal = document.getElementById('logDetailModal');
        const body = document.getElementById('logDetailModalBody');
        function showModal(html){ if (typeof html !== 'undefined') body.innerHTML = html; modal.classList.add('show'); modal.style.display = 'flex'; }
        function hideModal(){ modal.classList.remove('show'); modal.style.display = 'none'; }

        document.addEventListener('click', function(e){
            const btn = e.target.closest('a[data-log-id]');
            if (btn) {
                e.preventDefault();
                const id = btn.dataset.logId;
                const hidden = document.getElementById('detail-' + id);
                if (hidden) {
                    body.innerHTML = hidden.innerHTML;
                    showModal();
                } else {
                    showModal('<div style="padding:20px; text-align:center; color:var(--gray)">Detail tidak ditemukan</div>');
                }
                return;
            }

            if (e.target.id === 'logDetailClose' || e.target.id === 'logDetailClose2' || (e.target.id === 'logDetailModal' && !e.target.closest('.modal-content'))) {
                hideModal();
            }
        });
    })();
</script>
@endsection
