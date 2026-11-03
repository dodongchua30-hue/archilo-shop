@extends('layout')
@section('title', 'Cart | Archilo')
@section('content')
<h1 class="font-serif text-3xl font-bold mb-6">Your cart</h1>
@if($items->isEmpty())
  <p>Your cart is empty. <a href="{{ route('home') }}" class="text-brand font-semibold">Keep shopping</a></p>
@else
<div class="lg:grid lg:grid-cols-3 lg:gap-8">
  <div class="lg:col-span-2 space-y-3">
    @foreach($items as $i)
      <div class="bg-white rounded-2xl p-4 flex items-center gap-4 shadow-sm">
        <div class="w-16 h-20 rounded-xl bg-brand-light overflow-hidden flex items-center justify-center font-serif text-2xl text-brand">
          @if($i['v']->product->image)<img src="{{ asset('images/'.$i['v']->product->image) }}" alt="{{ $i['v']->product->name }}" class="w-full h-full object-cover">@else {{ substr($i['v']->product->name,0,1) }} @endif
        </div>
        <div class="flex-1"><p class="font-medium">{{ $i['v']->product->name }}</p><p class="text-sm text-ink/70">Size {{ $i['v']->label() }} &middot; Qty {{ $i['qty'] }}</p></div>
        <div class="text-right"><p class="font-serif font-semibold">₱{{ number_format($i['qty']*$i['v']->product->price,0) }}</p>
          <form method="POST" action="{{ route('cart.remove',$i['v']->id) }}">@csrf<button class="text-xs text-blush font-medium">Remove</button></form></div>
      </div>
    @endforeach
  </div>
  <div class="mt-6 lg:mt-0 bg-white rounded-2xl p-5 shadow-sm h-fit lg:sticky lg:top-24">
    <div class="flex justify-between bg-brand text-white rounded-xl px-4 py-3 font-semibold"><span>TOTAL</span><span>₱{{ number_format($total,0) }}</span></div>
    <p class="text-xs text-ink/70 mt-3">Pickup in store. No delivery fees. Pay when you collect.</p>
    <a href="{{ route('checkout') }}" class="{{ $btn }} w-full bg-buy text-white hover:opacity-90 mt-4">Reserve for pickup</a>
    <a href="{{ route('home') }}" class="block text-center text-sm text-blush font-medium mt-3">Continue shopping</a>
  </div>
</div>
@endif
@endsection
