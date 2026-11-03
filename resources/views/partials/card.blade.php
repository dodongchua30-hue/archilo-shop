@php $wished = \App\Models\Wishlist::has($p->id); @endphp
<div class="relative">
  <a href="{{ route('product',$p) }}" class="lift block bg-white rounded-2xl overflow-hidden shadow-sm">
    <div class="aspect-[3/4] bg-brand-light flex items-center justify-center">
      @if($p->image)<img src="{{ asset('images/'.$p->image) }}" alt="{{ $p->name }}" loading="lazy" class="w-full h-full object-cover">
      @else<span class="font-serif text-5xl text-brand">{{ substr($p->name,0,1) }}</span>@endif
    </div>
    <div class="p-3">
      <p class="font-medium">{{ $p->name }}</p>
      <div class="flex justify-between items-center mt-1">
        <span class="font-serif font-semibold">₱{{ number_format($p->price,0) }}</span>
        <span class="text-xs {{ $p->inStock() ? 'text-green-700' : 'text-red-600' }}">{{ $p->inStock() ? 'In stock' : 'Out of stock' }}</span>
      </div>
    </div>
  </a>
  @auth
    <form method="POST" action="{{ route('wishlist.toggle',$p) }}" class="absolute top-2 right-2 z-10">@csrf
      <button type="submit" aria-label="{{ $wished ? 'Remove from wishlist' : 'Add to wishlist' }}" class="w-10 h-10 rounded-full bg-white/90 shadow flex items-center justify-center text-xl text-blush">{{ $wished ? '♥' : '♡' }}</button>
    </form>
  @else
    <a href="{{ route('login') }}" aria-label="Log in to save to wishlist" class="absolute top-2 right-2 z-10 w-10 h-10 rounded-full bg-white/90 shadow flex items-center justify-center text-xl text-blush">♡</a>
  @endauth
</div>
