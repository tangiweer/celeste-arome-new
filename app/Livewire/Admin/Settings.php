<?php
namespace App\Livewire\Admin;
use Livewire\Component;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
class Settings extends Component
{
    public $site_name;
    public $admin_email;
    public $maintenance_mode;
    public $currency;
    public $theme;
    public $allow_registration;
    public $support_contact;

    public function mount()
    {
        $this->site_name = config('app.name');
        $this->admin_email = config('mail.from.address');
        $this->maintenance_mode = config('app.maintenance_mode', false);
        $this->currency = config('app.currency', 'USD');
        $this->theme = config('app.theme', 'dark');
        $this->allow_registration = config('app.allow_registration', true);
        $this->support_contact = config('app.support_contact', '');
    }

    public function saveSettings()
    {
        // Here you would save to .env or a settings table
        // For demo, just flash success
        Session::flash('success', 'Settings saved successfully!');
    }

    public function render()
    {
        return view('livewire.admin.settings');
    }
}
