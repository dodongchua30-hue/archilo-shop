@extends('layout')
@section('title', 'Staff | Archilo')
@section('content')
<div class="flex flex-wrap justify-between items-center gap-3 mb-6">
  <h1 class="font-serif text-3xl font-bold">Staff dashboard</h1>
  <div class="flex flex-wrap gap-2">
    <a href="{{ route('staff.products.create') }}" class="{{ $btn }} bg-brand text-white">Add product</a>
    <a href="{{ route('staff.products') }}" class="{{ $btn }} bg-ink text-white">Manage products</a>
  </div>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-8">
  <div class="bg-white rounded-2xl p-4 shadow-sm"><p class="text-sm text-ink/70">Reserved orders</p><p class="font-serif text-3xl text-brand">{{ $reserved }}</p></div>
  <div class="bg-white rounded-2xl p-4 shadow-sm"><p class="text-sm text-ink/70">Ready for pickup</p><p class="font-serif text-3xl text-brand">{{ $ready }}</p></div>
  <div class="bg-white rounded-2xl p-4 shadow-sm"><p class="text-sm text-ink/70">Low or out of stock</p><p class="font-serif text-3xl text-blush">{{ $lowCount }}</p></div>
  <div class="bg-white rounded-2xl p-4 shadow-sm"><p class="text-sm text-ink/70">Products</p><p class="font-serif text-3xl text-ink">{{ $productCount }}</p></div>
</div>

<div class="flex justify-between items-center mb-3">
  <h2 class="font-serif text-2xl font-bold">Today's pickups</h2>
  <a href="{{ route('admin.orders') }}" class="text-brand font-semibold">All orders &rarr;</a>
</div>
<div class="bg-white rounded-2xl shadow-sm overflow-x-auto mb-8">
  <table class="w-full text-sm">
    <thead class="bg-brand-light text-left"><tr><th class="p-3">Code</th><th class="p-3">Customer</th><th class="p-3">Time slot</th><th class="p-3">Status</th></tr></thead>
    <tbody>
    @forelse($todayOrders as $o)
      <tr class="border-t"><td class="p-3 font-semibold">{{ $o->code }}</td><td class="p-3">{{ $o->user->name ?? '' }}</td><td class="p-3">{{ $o->pickup_slot }}</td><td class="p-3 capitalize">{{ $o->status }}</td></tr>
    @empty
      <tr><td colspan="4" class="p-4 text-ink/60">No pickups scheduled for today.</td></tr>
    @endforelse
    </tbody>
  </table>
</div>

<div class="flex justify-between items-center mb-3">
  <h2 class="font-serif text-2xl font-bold">Low stock</h2>
  <a href="{{ route('admin') }}" class="text-brand font-semibold">Stock Checker &rarr;</a>
</div>
<div class="bg-white rounded-2xl shadow-sm overflow-x-auto">
  <table class="w-full text-sm">
    <thead class="bg-brand-light text-left"><tr><th class="p-3">Product</th><th class="p-3">Size / colour</th><th class="p-3">Stock</th></tr></thead>
    <tbody>
    @forelse($lowItems as $v)
      <tr class="border-t"><td class="p-3 font-medium">{{ $v->product->name }}</td><td class="p-3">{{ $v->label() }}</td><td class="p-3 font-semibold {{ $v->stock == 0 ? 'text-red-700' : 'text-amber-700' }}">{{ $v->stock }}</td></tr>
    @empty
      <tr><td colspan="3" class="p-4 text-ink/60">Nothing is running low.</td></tr>
    @endforelse
    </tbody>
  </table>
</div>
@endsection
