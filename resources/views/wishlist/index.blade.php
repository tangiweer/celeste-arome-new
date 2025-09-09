@php
  /** @var \Illuminate\Support\Collection $collection */
  $collection = $wishlistItems ?? $items ?? $wishlist ?? collect();
@endphp

@if($collection->isEmpty())
  <div class="rounded-2xl bg-white/5 backdrop-blur border border-white/10 p-10 text-center text-gray-300">
    Your wishlist is empty.
  </div>
@else
  {{-- Add all to cart --}}
  <div class="mb-6 flex justify-end">
    <form method="POST" action="{{ route('wishlist.moveAllToCart') }}" onsubmit="this.querySelector('button').disabled=true">
      @csrf
      <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold">
        Add all to cart
      </button>
    </form>
  </div>

  <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($collection as $w)
      @php $p = $w->product ?? $w; @endphp
      @continue(!$p) {{-- skip if product no longer exists --}}

      <div class="rounded-2xl bg-white/5 backdrop-blur border border-white/10 p-4">
        <div class="aspect-[4/3] rounded-xl overflow-hidden bg-white/5 border border-white/10">
          @if(($p->primaryImage->url ?? null))
            <img src="{{ $p->primaryImage->url }}" alt="{{ $p->name }}" class="w-full h-full object-cover">
          @else
            <div class="w-full h-full flex items-center justify-center text-gray-500">No image</div>
          @endif
        </div>

        <div class="mt-3 text-white font-semibold">{{ $p->name }}</div>
        <div class="text-blue-300">${{ number_format($p->price ?? 0, 2) }}</div>

        <div class="mt-3 flex items-center gap-2">
          {{-- Move ONE to cart (also removes from wishlist) --}}
          <form method="POST" action="{{ route('wishlist.moveToCart', $p->id) }}" class="inline" onsubmit="this.querySelector('button').disabled=true">
            @csrf
            <button type="submit" class="px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm">
              Add to cart
            </button>
          </form>

          {{-- Remove from wishlist --}}
          <form method="POST" action="{{ route('wishlist.remove', $p->id) }}" class="inline" onsubmit="this.querySelector('button').disabled=true">
            @csrf @method('DELETE')
            <button type="submit" class="px-3 py-2 rounded-lg bg-rose-600/20 hover:bg-rose-600/30 text-rose-300 text-sm border border-rose-500/30">
              Remove
            </button>
          </form>
        </div>
      </div>
    @endforeach
  </div>
@endif
