@extends('layout')
@section('title', 'Admin dashboard | Archilo')
@section('content')
<div class="flex flex-wrap justify-between items-center gap-3 mb-6">
  <h1 class="font-serif text-3xl font-bold">Admin dashboard</h1>
  <div class="flex flex-wrap gap-2">
    <a href="{{ route('owner.reports') }}" class="{{ $btn }} bg-brand text-white">Sales reports</a>
    <a href="{{ route('owner.thresholds') }}" class="{{ $btn }} bg-ink text-white">Low-stock thresholds</a>
    <a href="{{ route('owner.history') }}" class="{{ $btn }} bg-ink text-white">Stock history</a>
    <a href="{{ route('owner.users') }}" class="{{ $btn }} bg-ink text-white">Accounts</a>
  </div>
</div>

<p class="text-sm text-ink/70 mb-2">Sales count orders marked <strong>Collected</strong>, by pickup date.</p>
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-8">
  <div class="bg-white rounded-2xl p-4 shadow-sm"><p class="text-sm text-ink/70">Sales today</p><p class="font-serif text-2xl text-brand">₱{{ number_format($salesToday, 2) }}</p></div>
  <div class="bg-white rounded-2xl p-4 shadow-sm"><p class="text-sm text-ink/70">Sales this month</p><p class="font-serif text-2xl text-brand">₱{{ number_format($salesMonth, 2) }}</p></div>
  <div class="bg-white rounded-2xl p-4 shadow-sm"><p class="text-sm text-ink/70">Sales, all time</p><p class="font-serif text-2xl text-brand">₱{{ number_format($salesAll, 2) }}</p></div>
  <div class="bg-white rounded-2xl p-4 shadow-sm"><p class="text-sm text-ink/70">Waiting for pickup</p><p class="font-serif text-2xl text-ink">₱{{ number_format($pendingValue, 2) }}</p></div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
  <div>
    <h2 class="font-serif text-2xl font-bold mb-3">Orders by status</h2>
    <div class="bg-white rounded-2xl shadow-sm p-4 grid grid-cols-2 gap-3 text-sm">
      @foreach(['reserved' => 'Reserved', 'ready' => 'Ready', 'collected' => 'Collected', 'cancelled' => 'Cancelled'] as $k => $label)
        <div class="flex justify-between border-b py-2"><span>{{ $label }}</span><span class="font-semibold">{{ $counts[$k] ?? 0 }}</span></div>
      @endforeach
    </div>

    <h2 class="font-serif text-2xl font-bold mt-8 mb-3">Stock alerts</h2>
    <div class="grid grid-cols-2 gap-3">
      <a href="{{ route('admin', ['status' => 'low']) }}" class="bg-white rounded-2xl p-4 shadow-sm lift"><p class="text-sm text-ink/70">Low or out</p><p class="font-serif text-3xl text-blush">{{ $lowCount }}</p></a>
      <a href="{{ route('admin', ['status' => 'out']) }}" class="bg-white rounded-2xl p-4 shadow-sm lift"><p class="text-sm text-ink/70">Out of stock</p><p class="font-serif text-3xl text-red-700">{{ $outCount }}</p></a>
    </div>
  </div>

  <div>
    <h2 class="font-serif text-2xl font-bold mb-3">Best sellers (collected)</h2>
    <div class="bg-white rounded-2xl shadow-sm overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-brand-light text-left"><tr><th class="p-3">Product</th><th class="p-3">Sold</th><th class="p-3">Revenue</th></tr></thead>
        <tbody>
        @forelse($top as $t)
          <tr class="border-t"><td class="p-3 font-medium">{{ $t->name }}</td><td class="p-3">{{ $t->qty }}</td><td class="p-3">₱{{ number_format($t->revenue, 2) }}</td></tr>
        @empty
          <tr><td colspan="3" class="p-4 text-ink/60">No collected orders yet. Mark an order as Collected in Orders to see sales here.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
