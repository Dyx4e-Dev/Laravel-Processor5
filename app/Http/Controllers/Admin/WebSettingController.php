<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebSetting;

class WebSettingController extends Controller
{
    public function index()
    {
        $setting = WebSetting::first() ?? new WebSetting;
        return view('backend.settings', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'copyright' => 'required|string|max:255',
        ]);

        $setting = WebSetting::first() ?? new WebSetting;
        $setting->title = $request->title;
        $setting->subtitle = $request->subtitle;
        $setting->copyright = $request->copyright;
        $setting->save();

        return back()->with('success', 'Tetapan berjaya dikemaskini!');
    }
}
