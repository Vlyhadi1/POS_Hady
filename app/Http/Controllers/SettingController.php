<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        return view('settings.index', [
            'settings' => Setting::current(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'store_name' => ['required', 'string', 'max:100'],
            'store_address' => ['nullable', 'string', 'max:255'],
            'store_phone' => ['nullable', 'string', 'max:30'],
            'currency' => ['required', 'in:IDR'],
            'low_stock_limit' => ['required', 'integer', 'min:0', 'max:1000'],
            'receipt_footer' => ['nullable', 'string', 'max:255'],
            'store_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_logo' => ['nullable', 'boolean'],
        ]);

        $setting = Setting::current();

        if ($request->hasFile('store_logo')) {
            $this->deleteLogo($setting->store_logo);
            $data['store_logo'] = $request->file('store_logo')->store('settings', 'public');
        } elseif ($request->boolean('remove_logo')) {
            $this->deleteLogo($setting->store_logo);
            $data['store_logo'] = null;
        }

        unset($data['remove_logo']);
        $setting->update($data);

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    private function deleteLogo(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
