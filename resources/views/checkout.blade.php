@extends('layout')
@section('title', 'Pickup | Archilo')
@section('content')
<h1 class="font-serif text-3xl font-bold mb-6">Pick up in store</h1>
<form method="POST" action="{{ route('order.store') }}" class="lg:grid lg:grid-cols-3 lg:gap-8">@csrf
  <div class="lg:col-span-2 space-y-4">
    <div class="bg-white rounded-2xl p-5 shadow-sm"><p class="font-semibold">Archilo Fashion Boutique</p><p class="text-sm text-ink/70">[store address] &middot; [opening hours]</p></div>
    <div class="bg-white rounded-2xl p-5 shadow-sm grid sm:grid-cols-2 gap-4">
      <label class="text-sm font-medium">Pickup date
        <input type="date" name="pickup_date" min="{{ now()->toDateString() }}" value="{{ old('pickup_date') }}" required class="mt-1 w-full rounded-xl border border-ink/20 px-3 py-3"></label>
      <label class="text-sm font-medium">Time slot
        <select name="pickup_slot" required class="mt-1 w-full rounded-xl border border-ink/20 px-3 py-3 bg-white">
          @foreach($slots as $s)<option {{ old('pickup_slot')==$s?'selected':'' }}>{{ $s }}</option>@endforeach</select></label>
    </div>
    <p class="text-sm text-ink/70 px-1">Payment is made in store when you collect your items.</p>
  </div>
  <div class="mt-6 lg:mt-0 bg-white rounded-2xl p-5 shadow-sm h-fit">
    @foreach($items as $i)<div class="flex justify-between text-sm py-1"><span>{{ $i['v']->product->name }} ({{ $i['v']->label() }}) x{{ $i['qty'] }}</span><span>₱{{ number_format($i['qty']*$i['v']->product->price,0) }}</span></div>@endforeach
    <div class="flex justify-between bg-brand text-white rounded-xl px-4 py-3 font-semibold mt-3"><span>TOTAL</span><span>₱{{ number_format($total,0) }}</span></div>
    <button class="{{ $btn }} w-full bg-buy text-white hover:opacity-90 mt-4">Reserve for pickup</button>
  </div>
</form>
@endsection
