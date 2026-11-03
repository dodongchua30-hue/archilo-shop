@extends('layout')
@section('title', 'Order '.$order->code)
@section('content')
<div class="max-w-2xl mx-auto text-center">
  @if(session('ok'))<div class="mb-4 rounded-xl bg-green-100 text-green-800 px-4 py-3 text-sm">{{ session('ok') }}</div>@endif
  <div class="mx-auto w-32 h-32 rounded-full border-8 border-brand flex items-center justify-center bg-white font-serif text-4xl text-brand">&#10003;</div>
  <h1 class="font-serif text-3xl font-bold mt-4">{{ $order->status == 'cancelled' ? 'Order cancelled' : 'Your order is reserved' }}</h1>
  <p class="text-ink/70 mt-1">{{ $order->status == 'cancelled' ? 'This reservation is no longer active' : 'Show this code at the counter' }}</p>
  <div class="bg-brand text-white rounded-xl py-3 mt-3 font-serif text-2xl tracking-widest">{{ $order->code }}</div>
  <p class="mt-3 font-medium">Pickup: {{ $order->pickup_date->format('D, M j') }} at {{ $order->pickup_slot }}</p>
  <div class="flex justify-between text-xs font-medium mt-6">
    @foreach(['reserved'=>'Reserved','ready'=>'Ready for pickup','collected'=>'Collected'] as $k=>$label)
      <span class="flex-1 py-2 border-t-4 {{ $order->status==$k || ($order->status=='collected') ? 'border-brand text-brand' : 'border-panel text-ink/50' }}">{{ $label }}</span>
    @endforeach
  </div>
  @if($order->status=='cancelled')<p class="mt-3 text-blush font-semibold">This order was cancelled.</p>@endif
  <div class="bg-white rounded-2xl p-5 mt-6 text-left shadow-sm">
    @foreach($order->items as $i)<div class="flex justify-between text-sm py-1"><span>{{ $i->variant->product->name }} ({{ $i->variant->label() }}) x{{ $i->qty }}</span><span>₱{{ number_format($i->price*$i->qty,0) }}</span></div>@endforeach
    <div class="flex justify-between font-semibold border-t mt-2 pt-2"><span>Total</span><span>₱{{ number_format($order->total,0) }}</span></div>
  </div>
  @if($order->status !== 'cancelled')
    <div class="bg-white rounded-2xl p-5 mt-6 shadow-sm">
      <p class="font-semibold">Scan at the counter</p>
      <div id="qr" class="flex justify-center mt-3"></div>
      <p class="text-xs text-ink/60 mt-2">Code: {{ $order->code }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 mt-6 shadow-sm text-left">
      <p class="font-semibold">{{ config('store.name') }}</p>
      <p class="text-sm text-ink/70">{{ config('store.address') }}</p>
      <p class="text-sm text-ink/70">{{ config('store.hours') }}@if(config('store.phone')) &middot; {{ config('store.phone') }}@endif</p>
      <iframe class="w-full h-64 rounded-xl mt-3 border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
        src="https://maps.google.com/maps?q={{ config('store.lat') }},{{ config('store.lng') }}&z=17&output=embed"></iframe>
      <a href="https://www.google.com/maps/dir/?api=1&destination={{ config('store.lat') }},{{ config('store.lng') }}" target="_blank" rel="noopener"
         class="{{ $btn }} bg-brand text-white inline-block mt-3">Get directions</a>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
      if (window.QRCode) new QRCode(document.getElementById('qr'), {text: @json($order->code), width: 160, height: 160});
    </script>
  @endif
  @if($order->status === 'reserved' && $order->user_id === auth()->id())
    <form method="POST" action="{{ route('order.cancel', $order) }}" class="mt-5" onsubmit="return confirm('Cancel this reservation? The items will go back on sale.')">@csrf
      <button class="{{ $btn }} border border-blush text-blush hover:bg-blush/10">Cancel reservation</button>
    </form>
  @elseif($order->status === 'ready')
    <p class="mt-5 text-sm text-ink/70">Your order is ready. To cancel it, please contact the store.</p>
  @endif
  <a href="{{ route('home') }}" class="inline-block mt-5 text-blush font-medium">Back to shop</a>
</div>
@endsection
