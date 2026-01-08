<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laptop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RecommendationController extends Controller
{
    public function index()
    {
        $laptops = Laptop::latest()->get();
        return view('backend.recommendation', compact('laptops'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'processor' => 'required',
            'ram' => 'required',
            'storage' => 'required',
            'vga' => 'required',
            'screen_size' => 'required',
            'price' => 'required|numeric',
            'recommendation' => 'required|array', // Menerima input checkbox/multiple select
            'app_usage' => 'required|in:single-threaded,multi-threaded',
        ]);

        if ($request->hasFile('photo')) {
            $filename = time() . '_' . $request->file('photo')->getClientOriginalName();
            $request->file('photo')->move(public_path('img/laptop'), $filename);
            $data['photo'] = 'img/laptop/' . $filename;
        }

        // Simpan ke database (Laravel otomatis handle casting JSON di Model)
        Laptop::create($data);

        return redirect()->back()->with('success', 'Laptop berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $laptop = Laptop::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($laptop->photo && file_exists(public_path($laptop->photo))) {
                unlink(public_path($laptop->photo));
            }
            $filename = time() . '_' . $request->file('photo')->getClientOriginalName();
            $request->file('photo')->move(public_path('img/laptop'), $filename);
            $data['photo'] = 'img/laptop/' . $filename;
        }

        $laptop->update($data);
        return redirect()->back()->with('success', 'Data laptop diperbarui!');
    }

    public function destroy($id)
    {
        $laptop = Laptop::findOrFail($id);
        if ($laptop->photo) Storage::disk('public')->delete($laptop->photo);
        $laptop->delete();
        
        return redirect()->back()->with('success', 'Laptop berhasil dihapus!');
    }
}