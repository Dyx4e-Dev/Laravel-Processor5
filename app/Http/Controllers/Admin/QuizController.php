<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;

class QuizController extends Controller
{
    public function index() {
        $quizzes = Quiz::orderBy('id')->get();
        return view('backend.quiz', compact('quizzes'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'question' => 'required',
            'option_a' => 'required',
            'option_b' => 'required',
            'option_c' => 'required',
            'option_d' => 'required',
            'answer'   => 'required|in:a,b,c,d',
        ]);

        Quiz::create($data);
        return back()->with('success', 'Soal berhasil ditambahkan!');
    }

    public function update(Request $request, $id) {
        $quiz = Quiz::findOrFail($id);
        $data = $request->validate([
            'question' => 'required',
            'option_a' => 'required',
            'option_b' => 'required',
            'option_c' => 'required',
            'option_d' => 'required',
            'answer'   => 'required|in:a,b,c,d',
        ]);

        $quiz->update($data);
        return back()->with('success', 'Soal berhasil diperbarui!');
    }

    public function destroy($id) {
        Quiz::findOrFail($id)->delete();
        return back()->with('success', 'Soal berhasil dihapus!');
    }
}
