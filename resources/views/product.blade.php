@extends('layout')
@section('title', $product->name.' | Archilo')
@section('content')
<div class="grid md:grid-cols-2 gap-6 lg:gap-12">
  <div class="aspect-[3/4] bg-brand-light rounded-3xl flex items-center justify-center overflow-hidden">
    @if($product->image)<img src="{{ asset('images/'.$product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
    @else<span class="font-serif text-8xl text-brand">{{ substr($product->name,0,1) }}</span>@endif
  </div>
  <div>
    <a href="{{ route('category',$product->category) }}" class="text-sm text-brand font-medium">&larr; {{ $product->category }}</a>
    <h1 class="font-serif text-3xl lg:text-4xl font-bold mt-1">{{ $product->name }}</h1>
    <p class="font-serif text-2xl mt-2">₱{{ number_format($product->price,0) }}</p>
    <p class="mt-4 text-ink/80">{{ $product->description }}</p>
    <div class="mt-4">
      @auth
        <form method="POST" action="{{ route('wishlist.toggle',$product) }}">@csrf
          <button class="inline-flex items-center gap-2 rounded-full border border-blush/50 px-4 py-2 text-sm font-medium text-blush hover:bg-blush/10">{{ \App\Models\Wishlist::has($product->id) ? '♥ In your wishlist' : '♡ Add to wishlist' }}</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-full border border-blush/50 px-4 py-2 text-sm font-medium text-blush hover:bg-blush/10">♡ Log in to save to wishlist</a>
      @endauth
    </div>
    <form method="POST" action="{{ route('cart.add') }}" class="mt-6">@csrf
      @php
        $colors = $product->variants->pluck('color')->unique()->values();
        $multi = $colors->count() > 1;
        $first = $colors->first(fn ($c) => $product->variants->where('color', $c)->sum('stock') > 0) ?? $colors->first();
      @endphp
      @if($multi)
        <p class="font-medium mb-2">Select colour</p>
        <div class="flex flex-wrap gap-2 mb-4">
          @foreach($colors as $c)
            <button type="button" data-colour="{{ $c }}" class="colour-chip chip px-4 py-3 rounded-xl border border-ink/20 {{ $c === $first ? 'bg-ink text-white' : 'bg-white' }} {{ $product->variants->where('color', $c)->sum('stock') ? '' : 'line-through opacity-60' }}">{{ $c }}</button>
          @endforeach
        </div>
      @endif
      <p class="font-medium mb-2">Select size</p>
      @foreach($colors as $c)
        <div class="size-group flex flex-wrap gap-2 {{ $c === $first ? '' : 'hidden' }}" data-colour="{{ $c }}">
          @foreach($product->variants->where('color', $c) as $v)
            <label class="{{ $v->stock ? 'cursor-pointer' : 'opacity-40 cursor-not-allowed' }}">
              <input type="radio" name="variant_id" value="{{ $v->id }}" data-instock="{{ $v->stock ? 1 : 0 }}" class="peer sr-only" {{ $v->stock && $c === $first ? 'required' : 'disabled' }}>
              <span class="chip block min-w-[56px] text-center px-4 py-3 rounded-xl border border-ink/20 bg-white peer-checked:bg-ink peer-checked:text-white {{ $v->stock ? '' : 'line-through' }}">{{ $v->size }}</span>
            </label>
          @endforeach
        </div>
      @endforeach
      <p class="text-sm mt-3 {{ $product->inStock() ? 'text-green-700' : 'text-red-600' }}">{{ $product->inStock() ? 'Available for in-store pickup' : 'Currently out of stock' }}</p>
      <button class="{{ $btn }} w-full md:w-auto bg-buy text-white hover:opacity-90 mt-5" {{ $product->inStock() ? '' : 'disabled' }}>Add to cart</button>
    </form>
  </div>
</div>
@if($multi)
<script>
document.querySelectorAll('.colour-chip').forEach(function (chip) {
  chip.addEventListener('click', function () {
    var colour = chip.dataset.colour;
    document.querySelectorAll('.colour-chip').forEach(function (c) {
      var on = c === chip;
      c.classList.toggle('bg-ink', on);
      c.classList.toggle('text-white', on);
      c.classList.toggle('bg-white', !on);
    });
    document.querySelectorAll('.size-group').forEach(function (g) {
      var active = g.dataset.colour === colour;
      g.classList.toggle('hidden', !active);
      g.querySelectorAll('input[type=radio]').forEach(function (i) {
        var ok = active && i.dataset.instock === '1';
        i.disabled = !ok;
        i.required = ok;
        if (!active) { i.checked = false; }
      });
    });
  });
});
</script>
@endif
@endsection
