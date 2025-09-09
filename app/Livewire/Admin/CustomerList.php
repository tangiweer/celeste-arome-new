<?php
namespace App\Livewire\Admin;
use Livewire\Component;
use App\Models\User;
class CustomerList extends Component
{
    public $customers;
    public function mount() {
        $this->customers = User::where('role', 'customer')->get();
    }
    public function render() {
        return view('livewire.admin.customer-list');
    }
}
