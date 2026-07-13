<?php

namespace App\Http\Controllers;

use App\Models\PointSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PointSettingController extends Controller
{
    public function index(): View
    {
        return view('point-settings.index', [
            'settings' => PointSetting::query()->orderBy('position')->get(),
        ]);
    }

    public function edit(PointSetting $pointSetting): View
    {
        return view('point-settings.edit', [
            'setting' => $pointSetting,
        ]);
    }

    public function update(Request $request, PointSetting $pointSetting): RedirectResponse
    {
        $data = $request->validate([
            'points' => ['required', 'integer', 'min:0'],
        ]);

        $pointSetting->update($data);

        return redirect()->route('point-settings.index')->with('success', 'Point setting updated successfully.');
    }
}