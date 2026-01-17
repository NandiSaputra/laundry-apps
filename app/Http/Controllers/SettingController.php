<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\Setting\UpdateSettingRequest;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index()
    {
        $settings = $this->settingService->getAll();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(UpdateSettingRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('shop_logo')) {
            // Delete old logo if exists
            $oldLogo = $this->settingService->get('shop_logo');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }

            $path = $request->file('shop_logo')->store('logos', 'public');
            $data['shop_logo'] = $path;
        }

        $this->settingService->updateSettings($data);

        return redirect()->route('admin.settings')->with('success', 'Pengaturan berhasil diperbarui!');
    }
}
