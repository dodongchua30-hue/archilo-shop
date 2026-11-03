@extends('layout')
@section('title', 'Order '.$order->code)
@section('content')
<div class="max-w-2xl mx-auto text-center">
  <div class="mx-auto w-32 h-32 rounded-full border-8 border-brand flex items-center justify-center bg-white font-serif text-4xl text-brand">&#10003;</div>
  <h1 class="font-serif text-3xl font-bold mt-4">Your order is reserved</h1>
  <p class="text-ink/70 mt-1">Show this code at the counter</p>
  <div class="bg-brand text-white rounded-xl py-3 mt-3 font-serif text-2xl tracking-widest">{{ $order->code }}</div>
  <p class="mt-3 font-medium">Pickup: {{ $order->pickup_date->format('D, M j') }} at {{ $order->pickup_slot }}</p>
  <div class="flex justify-between text-xs font-medium mt-6">
    @foreach(['reserved'=>'Reserved','ready'=>'Ready for pickup','collected'=>'Collected'] as $k=>$label)
      <span class="flex-1 py-2 border-t-4 {{ $order->status==$k || ($order->status=='collected') ? 'border-brand text-brand' : 'border-panel text-ink/50' }}">{{ $label }}</span>
    @endforeach
  </div>
  @if($order->status=='cancelled')<p class="mt-3 text-blush font-semibold">This order was cancelled.</p>@endif
  <div class="bg-white rounded-2xl p-5 mt-6 text-left shadow-sm">
    @foreach($order->items as $i)<div class="flex justify-between text-sm py-1"><span>{{ $i->variant->product->name }} ({{ $i->variant->size }}) x{{ $i->qty }}</span><span>₱{{ number_format($i->price*$i->qty,0) }}</span></div>@endforeach
    <div class="flex justify-between font-semibold border-t mt-2 pt-2"><span>Total</span><span>₱{{ number_format($order->total,0) }}</span></div>
  </div>
  <a href="{{ route('home') }}" class="inline-block mt-5 text-blush font-medium">Back to shop</a>
</div>
@endsection
