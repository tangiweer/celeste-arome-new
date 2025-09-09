<div>
    <h2 class="text-xl font-bold text-blue-300 mb-4">Settings</h2>
    <form wire:submit.prevent="saveSettings" class="space-y-6">
        <div>
            <label class="block text-sm text-blue-200 mb-2">Site Name</label>
            <input type="text" wire:model.defer="site_name" class="w-full rounded-lg bg-black/20 border border-white/10 px-4 py-2 text-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm text-blue-200 mb-2">Admin Email</label>
            <input type="email" wire:model.defer="admin_email" class="w-full rounded-lg bg-black/20 border border-white/10 px-4 py-2 text-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm text-blue-200 mb-2">Enable Maintenance Mode</label>
            <input type="checkbox" wire:model.defer="maintenance_mode" class="h-5 w-5 rounded border-white/20 bg-black/30">
        </div>
        <div>
            <label class="block text-sm text-blue-200 mb-2">Default Currency</label>
            <select wire:model.defer="currency" class="w-full rounded-lg bg-black/20 border border-white/10 px-4 py-2 text-blue-300">
                <option value="USD">USD</option>
                <option value="EUR">EUR</option>
                <option value="GBP">GBP</option>
                <option value="LKR">LKR</option>
            </select>
        </div>
        <div>
            <label class="block text-sm text-blue-200 mb-2">Theme</label>
            <select wire:model.defer="theme" class="w-full rounded-lg bg-black/20 border border-white/10 px-4 py-2 text-blue-300">
                <option value="dark">Dark</option>
                <option value="light">Light</option>
            </select>
        </div>
        <div>
            <label class="block text-sm text-blue-200 mb-2">Allow User Registration</label>
            <input type="checkbox" wire:model.defer="allow_registration" class="h-5 w-5 rounded border-white/20 bg-black/30">
        </div>
        <div>
            <label class="block text-sm text-blue-200 mb-2">Support Contact</label>
            <input type="text" wire:model.defer="support_contact" class="w-full rounded-lg bg-black/20 border border-white/10 px-4 py-2 text-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Phone or Email">
        </div>
        <button type="submit" class="px-6 py-2 rounded-lg bg-blue-600 text-white font-bold hover:bg-blue-500 transition">Save Settings</button>
    </form>
    @if(session('success'))
        <div class="mt-4 rounded bg-emerald-600/15 border border-emerald-500/30 text-emerald-200 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif
</div>
