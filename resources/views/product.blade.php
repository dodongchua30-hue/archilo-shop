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
      <p class="font-medium mb-2">Select size</p>
      <div class="flex flex-wrap gap-2">
        @foreach($product->variants as $v)
          <label class="{{ $v->stock ? 'cursor-pointer' : 'opacity-40 cursor-not-allowed' }}">
            <input type="radio" name="variant_id" value="{{ $v->id }}" class="peer sr-only" {{ $v->stock ? 'required' : 'disabled' }}>
            <span class="chip block min-w-[56px] text-center px-4 py-3 rounded-xl border border-ink/20 bg-white peer-checked:bg-ink peer-checked:text-white {{ $v->stock ? '' : 'line-through' }}">{{ $v->size }}</span>
          </label>
        @endforeach
      </div>
      <p class="text-sm mt-3 {{ $product->inStock() ? 'text-green-700' : 'text-red-600' }}">{{ $product->inStock() ? 'Available for in-store pickup' : 'Currently out of stock' }}</p>
      <button class="{{ $btn }} w-full md:w-auto bg-buy text-white hover:opacity-90 mt-5" {{ $product->inStock() ? '' : 'disabled' }}>Add to cart</button>
    </form>
  </div>
</div>
@endsection
