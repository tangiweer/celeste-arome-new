{{-- resources/views/account/partials/addresses.blade.php --}}
<div x-data="{ openAdd:false, openEdit:false, editAddress:{} }">

    {{-- Header + Add button --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-3xl font-bold text-white">Addresses</h2>
        <button
            @click="openAdd=true"
            class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow">
            + Add New Address
        </button>
    </div>

    {{-- Empty state --}}
    @if(($addresses ?? collect())->isEmpty())
        <div class="rounded-2xl bg-white/5 backdrop-blur border border-white/10 p-10 text-center">
            <div class="text-gray-300 text-lg">No addresses yet.</div>
            <div class="text-gray-400 mt-2">Add your shipping or billing address to speed up checkout.</div>
            <button
                @click="openAdd=true"
                class="mt-6 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold">
                Add Address
            </button>
        </div>
    @else
        {{-- Grid of address cards --}}
        <div class="grid sm:grid-cols-2 gap-6">
            @foreach($addresses as $addr)
                <div class="rounded-2xl bg-white/5 backdrop-blur border border-white/10 p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="font-semibold text-white">
                                {{ $addr->label ?? 'Address' }}
                                @if($addr->is_default_shipping)
                                    <span class="ml-2 text-xs px-2 py-0.5 rounded-full bg-blue-600/20 text-blue-300 border border-blue-500/30">Default Shipping</span>
                                @endif
                                @if($addr->is_default_billing)
                                    <span class="ml-2 text-xs px-2 py-0.5 rounded-full bg-violet-600/20 text-violet-300 border border-violet-500/30">Default Billing</span>
                                @endif
                            </div>
                            <div class="mt-1 text-gray-300">
                                {{ $addr->name }} @if($addr->phone) · <span class="text-gray-400">{{ $addr->phone }}</span> @endif
                            </div>
                        </div>

                        <div class="flex gap-2">
                            {{-- Edit --}}
                            <button
                                class="px-3 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-gray-200"
                                @click="openEdit=true; editAddress={{ $addr->only(['id','label','name','phone','line1','line2','city','state','zip','country','is_default_shipping','is_default_billing']) | json_encode() }}">
                                Edit
                            </button>

                            {{-- Delete --}}
                            <form method="POST" action="{{ route('account.addresses.destroy', $addr->id) }}"
                                  onsubmit="return confirm('Delete this address?')">
                                @csrf @method('DELETE')
                                <button
                                    class="px-3 py-1.5 rounded-lg bg-rose-600/20 hover:bg-rose-600/30 text-rose-300 border border-rose-500/30">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="mt-4 text-gray-300 leading-relaxed">
                        {{ $addr->line1 }}<br>
                        @if($addr->line2) {{ $addr->line2 }}<br> @endif
                        {{ $addr->city }}, {{ $addr->state }} {{ $addr->zip }}<br>
                        {{ $addr->country }}
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- =================== Add Address Modal =================== --}}
    <div x-show="openAdd" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
        <div @click.away="openAdd=false"
             class="w-full max-w-2xl rounded-2xl bg-slate-900/70 backdrop-blur border border-white/10 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-white">Add New Address</h3>
                <button @click="openAdd=false" class="text-gray-400 hover:text-white">✕</button>
            </div>

            <form method="POST" action="{{ route('account.addresses.store') }}" class="grid sm:grid-cols-2 gap-4">
                @csrf

                <div class="sm:col-span-2">
                    <label class="block text-sm text-gray-300 mb-1">Label (e.g., Home, Office)</label>
                    <input name="label" class="w-full px-3 py-2 rounded-lg bg-black/40 border border-white/10 text-white" />
                </div>

                <div>
                    <label class="block text-sm text-gray-300 mb-1">Full Name</label>
                    <input name="name" required class="w-full px-3 py-2 rounded-lg bg-black/40 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Phone</label>
                    <input name="phone" class="w-full px-3 py-2 rounded-lg bg-black/40 border border-white/10 text-white" />
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm text-gray-300 mb-1">Address Line 1</label>
                    <input name="line1" required class="w-full px-3 py-2 rounded-lg bg-black/40 border border-white/10 text-white" />
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm text-gray-300 mb-1">Address Line 2 (optional)</label>
                    <input name="line2" class="w-full px-3 py-2 rounded-lg bg-black/40 border border-white/10 text-white" />
                </div>

                <div>
                    <label class="block text-sm text-gray-300 mb-1">City</label>
                    <input name="city" required class="w-full px-3 py-2 rounded-lg bg-black/40 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="block text-sm text-gray-300 mb-1">State / Province</label>
                    <input name="state" class="w-full px-3 py-2 rounded-lg bg-black/40 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="block text-sm text-gray-300 mb-1">ZIP / Postal Code</label>
                    <input name="zip" required class="w-full px-3 py-2 rounded-lg bg-black/40 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Country</label>
                    <input name="country" required class="w-full px-3 py-2 rounded-lg bg-black/40 border border-white/10 text-white" />
                </div>

                <div class="sm:col-span-2 flex flex-wrap gap-4 mt-2">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-300">
                        <input type="checkbox" name="is_default_shipping" class="rounded bg-black/40 border-white/20">
                        Default Shipping
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-300">
                        <input type="checkbox" name="is_default_billing" class="rounded bg-black/40 border-white/20">
                        Default Billing
                    </label>
                </div>

                <div class="sm:col-span-2 flex items-center justify-end gap-3 mt-4">
                    <button type="button" @click="openAdd=false" class="px-4 py-2 rounded-xl bg-white/10 text-gray-200">
                        Cancel
                    </button>
                    <button class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold">
                        Save Address
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- =================== Edit Address Modal =================== --}}
    <div x-show="openEdit" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
        <div @click.away="openEdit=false"
             class="w-full max-w-2xl rounded-2xl bg-slate-900/70 backdrop-blur border border-white/10 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-white">Edit Address</h3>
                <button @click="openEdit=false" class="text-gray-400 hover:text-white">✕</button>
            </div>

            <form method="POST" :action="`{{ route('account.addresses.update', 0) }}`.replace('/0','/') + editAddress.id" class="grid sm:grid-cols-2 gap-4">
                @csrf @method('PATCH')

                <div class="sm:col-span-2">
                    <label class="block text-sm text-gray-300 mb-1">Label</label>
                    <input name="label" x-model="editAddress.label" class="w-full px-3 py-2 rounded-lg bg-black/40 border border-white/10 text-white" />
                </div>

                <div>
                    <label class="block text-sm text-gray-300 mb-1">Full Name</label>
                    <input name="name" x-model="editAddress.name" required class="w-full px-3 py-2 rounded-lg bg-black/40 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Phone</label>
                    <input name="phone" x-model="editAddress.phone" class="w-full px-3 py-2 rounded-lg bg-black/40 border border-white/10 text-white" />
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm text-gray-300 mb-1">Address Line 1</label>
                    <input name="line1" x-model="editAddress.line1" required class="w-full px-3 py-2 rounded-lg bg-black/40 border border-white/10 text-white" />
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm text-gray-300 mb-1">Address Line 2</label>
                    <input name="line2" x-model="editAddress.line2" class="w-full px-3 py-2 rounded-lg bg-black/40 border border-white/10 text-white" />
                </div>

                <div>
                    <label class="block text-sm text-gray-300 mb-1">City</label>
                    <input name="city" x-model="editAddress.city" required class="w-full px-3 py-2 rounded-lg bg-black/40 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="block text-sm text-gray-300 mb-1">State / Province</label>
                    <input name="state" x-model="editAddress.state" class="w-full px-3 py-2 rounded-lg bg-black/40 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="block text-sm text-gray-300 mb-1">ZIP / Postal Code</label>
                    <input name="zip" x-model="editAddress.zip" required class="w-full px-3 py-2 rounded-lg bg-black/40 border border-white/10 text-white" />
                </div>
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Country</label>
                    <input name="country" x-model="editAddress.country" required class="w-full px-3 py-2 rounded-lg bg-black/40 border border-white/10 text-white" />
                </div>

                <div class="sm:col-span-2 flex flex-wrap gap-4 mt-2">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-300">
                        <input type="checkbox" name="is_default_shipping" :checked="!!editAddress.is_default_shipping" class="rounded bg-black/40 border-white/20">
                        Default Shipping
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-300">
                        <input type="checkbox" name="is_default_billing" :checked="!!editAddress.is_default_billing" class="rounded bg-black/40 border-white/20">
                        Default Billing
                    </label>
                </div>

                <div class="sm:col-span-2 flex items-center justify-end gap-3 mt-4">
                    <button type="button" @click="openEdit=false" class="px-4 py-2 rounded-xl bg-white/10 text-gray-200">
                        Cancel
                    </button>
                    <button class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold">
                        Update Address
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
