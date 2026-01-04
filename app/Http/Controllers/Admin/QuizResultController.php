<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuizResult;


class QuizResultController extends Controller
{
    public function index()
    {
        // Mengambil data terbaru, paginate 10 agar tidak kepanjangan
        $results = QuizResult::with('team')->latest()->paginate(10);
        return view('backend.quiz_result', compact('results'));
    }

    public function destroy($id)
    {
        $result = QuizResult::findOrFail($id);
        $result->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    // Opsi Tambahan: Hapus Semua Data (Reset)
    public function flush()
    {
        QuizResult::truncate();
        return redirect()->back()->with('success', 'Semua data quiz telah dibersihkan');
    }
}
