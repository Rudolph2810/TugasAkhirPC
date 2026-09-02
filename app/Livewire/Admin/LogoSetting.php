<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class LogoSetting extends Component
{
    use WithFileUploads;

    public $logo;
    public $currentLogo;
    public $uploadedLogo;

    public function mount()
    {
        $this->currentLogo = Setting::where('key', 'logo_path')->first();
        if ($this->currentLogo && $this->currentLogo->value) {
            $this->uploadedLogo = Storage::url($this->currentLogo->value);
        }
    }

    public function updatedLogo()
    {
        $this->validate([
            'logo' => 'required|image|mimes:png,jpg,jpeg,svg|max:2048',
        ]);

        $path = $this->logo->store('logo', 'public');

        Setting::updateOrCreate(
            ['key' => 'logo_path'],
            ['value' => $path]
        );

        $this->uploadedLogo = Storage::url($path);
        session()->flash('message', 'Logo berhasil diupload.');
    }

    public function removeLogo()
    {
        $setting = Setting::where('key', 'logo_path')->first();
        if ($setting && $setting->value) {
            Storage::disk('public')->delete($setting->value);
            $setting->delete();
            $this->uploadedLogo = null;
            session()->flash('message', 'Logo berhasil dihapus.');
        }
    }

    public function render()
    {
        return view('livewire.admin.logo-setting');
    }
}