<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    public function index() {
        $teams = Team::orderBy('id')->get();
        return view('backend.team', compact('teams'));
        $teams = Team::orderBy('id')->get();
        return view('frontend.team', compact('teams'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'role' => 'required',
            'email' => 'required|email|unique:teams',
            'alamat' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('img/anggota'), $filename);
            $data['photo'] = 'img/anggota/'.$filename;
        }


        Team::create($data);
        return redirect()->back()->with('success', 'Anggota tim berhasil ditambahkan!');
    }

    public function destroy(Team $team)
    {
        if ($team->photo && file_exists(public_path($team->photo))) {
            unlink(public_path($team->photo));
        }

        $team->delete();
        return redirect()->back()->with('success', 'Anggota tim berhasil dihapus!');
    }


    public function update(Request $request, Team $team){
    $request->validate([
        'name' => 'required',
        'role' => 'required',
        'email' => 'required|email|unique:teams,email,' . $team->id,
        'alamat' => 'required',
        'photo' => 'image|mimes:jpeg,png,jpg|max:2048'
    ]);

    $data = $request->all();

    if ($request->hasFile('photo')) {

        // hapus foto lama
        if ($team->photo && file_exists(public_path($team->photo))) {
            unlink(public_path($team->photo));
        }

        $file = $request->file('photo');
        $filename = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('img/anggota'), $filename);
        $data['photo'] = 'img/anggota/'.$filename;

    } else {
        $data['photo'] = $team->photo;
    }


    $team->update($data);
    return redirect()->back()->with('success', 'Data tim berhasil diperbarui!');
}
}
