@extends('layouts.admin')
@section('title','Settings')

@section('content')
<div class="max-w-3xl">
  <h1 class="text-2xl font-bold mb-6">Store Settings</h1>

  @if(session('success'))
    <div class="mb-4 rounded bg-emerald-600/15 border border-emerald-500/30 text-emerald-200 px-4 py-3">
      {{ session('success') }}
    </div>
  @endif

  <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')

    <div>
      <label class="block text-sm mb-1">Site Name</label>
      <input type="text" name="site_name" class="w-full rounded-lg bg-white/10 border border-white/10 px-3 py-2"
             value="{{ old('site_name', $settings['site_name'] ?? 'Céleste Arôme') }}" required>
      @error('site_name')<p class="text-rose-300 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm mb-1">Store Email</label>
        <input type="email" name="store_email" class="w-full rounded-lg bg-white/10 border border-white/10 px-3 py-2"
               value="{{ old('store_email', $settings['store_email'] ?? '') }}">
        @error('store_email')<p class="text-rose-300 text-sm mt-1">{{ $message }}</p>@enderror
      </div>
      <div>
        <label class="block text-sm mb-1">Support Phone</label>
        <input type="text" name="support_phone" class="w-full rounded-lg bg-white/10 border border-white/10 px-3 py-2"
               value="{{ old('support_phone', $settings['support_phone'] ?? '') }}">
        @error('support_phone')<p class="text-rose-300 text-sm mt-1">{{ $message }}</p>@enderror
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <label class="block text-sm mb-1">Currency</label>
        <select name="currency" class="w-full rounded-lg bg-white/10 border border-white/10 px-3 py-2">
          @php $curr = old('currency', $settings['currency'] ?? 'USD'); @endphp
          @foreach (['USD','EUR','GBP','LKR','NZD','AUD'] as $c)
            <option value="{{ $c }}" {{ $curr === $c ? 'selected' : '' }}>{{ $c }}</option>
          @endforeach
        </select>
        @error('currency')<p class="text-rose-300 text-sm mt-1">{{ $message }}</p>@enderror
      </div>
      <div>
        <label class="block text-sm mb-1">Tax Rate (%)</label>
        <input type="number" step="0.01" min="0" max="100" name="tax_rate"
               class="w-full rounded-lg bg-white/10 border border-white/10 px-3 py-2"
               value="{{ old('tax_rate', $settings['tax_rate'] ?? '0') }}">
        @error('tax_rate')<p class="text-rose-300 text-sm mt-1">{{ $message }}</p>@enderror
      </div>
      <div>
        <label class="block text-sm mb-1">Free Shipping Threshold</label>
        <input type="number" step="0.01" min="0" name="free_shipping_threshold"
               class="w-full rounded-lg bg-white/10 border border-white/10 px-3 py-2"
               value="{{ old('free_shipping_threshold', $settings['free_shipping_threshold'] ?? '0') }}">
        @error('free_shipping_threshold')<p class="text-rose-300 text-sm mt-1">{{ $message }}</p>@enderror
      </div>
    </div>

    <div class="flex items-center gap-3">
      <input id="maintenance_mode" type="checkbox" name="maintenance_mode" value="1"
             class="h-4 w-4 rounded border-white/20 bg-white/10"
             {{ old('maintenance_mode', ($settings['maintenance_mode'] ?? '0') === '1' ? 'checked' : '') }}>
      <label for="maintenance_mode" class="text-sm">Enable maintenance mode banner (UI-only)</label>
    </div>

    <div>
      <label class="block text-sm mb-1">Logo</label>
      <input type="file" name="logo" accept="image/*"
             class="w-full rounded-lg bg-white/10 border border-white/10 px-3 py-2 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white">
      @error('logo')<p class="text-rose-300 text-sm mt-1">{{ $message }}</p>@enderror

      @if(!empty($settings['logo_path']))
        <div class="mt-3">
          <img src="{{ asset('storage/'.$settings['logo_path']) }}" alt="Current Logo" class="h-12">
        </div>
      @endif
    </div>

    <div>
      <button class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 font-semibold">Save Settings</button>
    </div>
  </form>
</div>
@endsection
