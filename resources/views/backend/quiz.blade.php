@extends('backend.layouts.admin')

@section('title', 'Manage Quiz')

@section('content')
<div class="reveal">
    <div class="card glass welcome-card" style="margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center;">
        <div class="welcome-info">
            <h1 class="gradient-text">Quiz Management</h1>
            <p>Kelola pertanyaan, pilihan jawaban, dan kunci jawaban di sini.</p>
        </div>
        <button class="btn-neon" onclick="toggleModal()">+ Add Question</button>
    </div>

    <div class="card glass table-section">
        <table class="custom-table">
            <thead>
                <tr>
                    <th style="padding-right: 10px; text-align: center">No</th>
                    <th style="width: 40%;">Pertanyaan</th>
                    <th style="width: 40%;">Opsi A-B-C-D</th>
                    <th style="width: 10%; text-align: center;">Kunci</th>
                    <th style="width: 10%;text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quizzes as $quiz)
                <tr>
                    <td style="color: var(--primary); font-weight: bold;">
                        #{{ ($quizzes->currentPage() - 1) * $quizzes->perPage() + $loop->iteration }}
                    </td>
                    <td style="line-height: 1.4;">
                        <p>{{ Str::limit($quiz->question, 60) }}</p>
                    </td>
                    <td> A : {{ $quiz->option_a }} <br>
                         B : {{ $quiz->option_b }} <br>
                         C : {{ $quiz->option_c }} <br>
                         D : {{ $quiz->option_d }} </td>
                    <td style="text-align: center;">
                        <span class="badge success" style="font-weight: bold;">{{ strtoupper($quiz->answer) }}</span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 10px; justify-content: center;">
                            <button type="button" class="btn-edit" data-quiz='@json($quiz)'
                                    style="background:transparent; border:none; color:var(--secondary); cursor:pointer; font-size: 18px;">
                                <i class='bx bx-edit'></i>
                            </button>

                            <form action="{{ route('admin.quiz.destroy', $quiz->id) }}" method="POST" onsubmit="return confirm('Hapus soal ini?')">
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
<div class="pagination-wrapper">
        {{ $quizzes->links('pagination::bootstrap-4') }}
</div>

<div id="addModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); z-index:9999; place-items:center; overflow-y: auto; padding: 20px;">
    <div class="card glass" style="width: 600px; padding: 30px;">
        <h3 class="neon-text" style="margin-bottom: 20px;">Add New Quiz</h3>
        <form action="{{ route('admin.quiz.store') }}" method="POST">
            @csrf
            <div class="input-group" style="margin-bottom: 15px;">
                <label style="color: var(--gray); font-size: 13px;">Pertanyaan</label>
                <textarea name="question" placeholder="Tulis soal di sini..." required 
                          style="width:100%; background:var(--glass); border:1px solid var(--glass-border); padding:10px; color:white; border-radius:8px; min-height: 80px;"></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <input type="text" name="option_a" placeholder="Pilihan A" required style="width:100%; background:var(--glass); border:1px solid var(--glass-border); padding:10px; color:white; border-radius:8px;">
                </div>
                <div>
                    <input type="text" name="option_b" placeholder="Pilihan B" required style="width:100%; background:var(--glass); border:1px solid var(--glass-border); padding:10px; color:white; border-radius:8px;">
                </div>
                <div>
                    <input type="text" name="option_c" placeholder="Pilihan C" required style="width:100%; background:var(--glass); border:1px solid var(--glass-border); padding:10px; color:white; border-radius:8px;">
                </div>
                <div>
                    <input type="text" name="option_d" placeholder="Pilihan D" required style="width:100%; background:var(--glass); border:1px solid var(--glass-border); padding:10px; color:white; border-radius:8px;">
                </div>
            </div>

            <div class="input-group" style="margin-bottom: 15px;">
                <label style="color: var(--primary); font-size: 13px;">Jawaban Benar</label>
                <select name="answer" required style="width:100%; background:var(--darker); border:1px solid var(--primary); padding:10px; color:white; border-radius:8px;">
                    <option value="a">Opsi A</option>
                    <option value="b">Opsi B</option>
                    <option value="c">Opsi C</option>
                    <option value="d">Opsi D</option>
                </select>
            </div>
            
            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                <button type="button" onclick="toggleModal()" style="background:transparent; border:none; color:white; cursor:pointer;">Cancel</button>
                <button type="submit" class="btn-neon">Save Quiz</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); z-index:9999; place-items:center; overflow-y: auto; padding: 20px;">
    <div class="card glass" style="width: 600px; padding: 30px;">
        <h3 class="neon-text" style="margin-bottom: 20px; color: var(--secondary);">Edit Quiz</h3>
        <form id="editForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="input-group" style="margin-bottom: 15px;">
                <textarea name="question" id="edit_question" required 
                          style="width:100%; background:var(--glass); border:1px solid var(--glass-border); padding:10px; color:white; border-radius:8px; min-height: 80px;"></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <input type="text" name="option_a" id="edit_a" placeholder="Pilihan A" required style="width:100%; background:var(--glass); border:1px solid var(--glass-border); padding:10px; color:white; border-radius:8px;">
                <input type="text" name="option_b" id="edit_b" placeholder="Pilihan B" required style="width:100%; background:var(--glass); border:1px solid var(--glass-border); padding:10px; color:white; border-radius:8px;">
                <input type="text" name="option_c" id="edit_c" placeholder="Pilihan C" required style="width:100%; background:var(--glass); border:1px solid var(--glass-border); padding:10px; color:white; border-radius:8px;">
                <input type="text" name="option_d" id="edit_d" placeholder="Pilihan D" required style="width:100%; background:var(--glass); border:1px solid var(--glass-border); padding:10px; color:white; border-radius:8px;">
            </div>

            <div class="input-group" style="margin-bottom: 15px;">
                <label style="color: var(--secondary); font-size: 13px;">Jawaban Benar</label>
                <select name="answer" id="edit_answer" required style="width:100%; background:var(--darker); border:1px solid var(--secondary); padding:10px; color:white; border-radius:8px;">
                    <option value="a">Opsi A</option>
                    <option value="b">Opsi B</option>
                    <option value="c">Opsi C</option>
                    <option value="d">Opsi D</option>
                </select>
            </div>
            
            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                <button type="button" onclick="closeQuizEditModal()" style="background:transparent; border:none; color:white; cursor:pointer;">Cancel</button>
                <button type="submit" class="btn-neon" style="background: var(--secondary);">Update Quiz</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal() {
        const modal = document.getElementById('addModal');
        modal.style.display = modal.style.display === 'none' ? 'grid' : 'none';
    }

    window.openQuizEditModal = function(data) {
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');
        
        if(modal && form) {
            form.action = `/admin/quiz/${data.id}`;
            // Ensure form will submit as POST with method spoofing to PUT
            form.method = 'POST';
            let m = form.querySelector('input[name="_method"]');
            if (!m) {
                m = document.createElement('input');
                m.type = 'hidden';
                m.name = '_method';
                form.appendChild(m);
            }
            m.value = 'PUT';
            document.getElementById('edit_question').value = data.question;
            document.getElementById('edit_a').value = data.option_a;
            document.getElementById('edit_b').value = data.option_b;
            document.getElementById('edit_c').value = data.option_c;
            document.getElementById('edit_d').value = data.option_d;
            document.getElementById('edit_answer').value = data.answer;

            modal.style.display = 'grid';
        }
    }

    window.closeQuizEditModal = function() {
        const m = document.getElementById('editModal');
        if (m) m.style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('addModal')) toggleModal();
        if (event.target == document.getElementById('editModal')) closeQuizEditModal();
    }

        // Use event delegation for edit buttons and a robust parser for data-quiz JSON
        function safeParseQuizData(raw) {
            if (!raw) return null;
            try {
                return JSON.parse(raw);
            } catch (e) {
                // Handle HTML-escaped quotes/entities
                try {
                    const unescaped = raw
                        .replace(/&quot;/g, '"')
                        .replace(/&#039;/g, "'")
                        .replace(/&amp;/g, '&');
                    return JSON.parse(unescaped);
                } catch (e2) {
                    console.error('safeParseQuizData failed', e, e2, raw);
                    return null;
                }
            }
        }

        document.addEventListener('click', function (e) {
            const btn = e.target.closest && e.target.closest('.btn-edit');
            if (!btn) return;
            e.preventDefault();
            const raw = btn.getAttribute('data-quiz') || btn.dataset.quiz || '';
            const obj = safeParseQuizData(raw);
            if (!obj) return console.error('Could not parse quiz data for edit button');
            console.log('Edit button clicked for quiz id=', obj.id);
            window.openQuizEditModal(obj);
        });
</script>
@endsection