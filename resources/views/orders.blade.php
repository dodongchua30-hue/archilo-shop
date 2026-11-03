@extends('layout')
@section('title', 'My orders | Archilo')
@section('content')
<h1 class="font-serif text-3xl font-bold mb-6">My orders</h1>
<div class="space-y-3">
  @forelse($orders as $o)
    <a href="{{ route('order.show',$o) }}" class="lift flex justify-between items-center bg-white rounded-2xl p-4 shadow-sm">
      <div><p class="font-semibold">{{ $o->code }}</p><p class="text-sm text-ink/70">{{ $o->pickup_date->format('M j') }} &middot; {{ $o->pickup_slot }}</p></div>
      <div class="text-right"><p class="font-serif font-semibold">₱{{ number_format($o->total,0) }}</p><span class="text-xs rounded-full bg-brand-light px-3 py-1 capitalize">{{ $o->status }}</span></div>
    </a>
  @empty <p>No orders yet.</p> @endforelse
</div>
@endsection
