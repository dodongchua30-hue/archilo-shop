@extends('layout')
@section('title', 'Sales reports | Archilo')
@section('content')
<div class="flex flex-wrap justify-between items-center gap-3 mb-6">
  <div>
    <h1 class="font-serif text-3xl font-bold">Sales reports</h1>
    <a href="{{ route('owner.dashboard') }}" class="text-brand font-semibold">&larr; Admin dashboard</a>
  </div>
  <button onclick="window.print()" class="{{ $btn }} bg-ink text-white">Print</button>
</div>

<form method="GET" class="flex flex-wrap items-end gap-3 mb-6 bg-white rounded-2xl shadow-sm p-4">
  <div><label class="block text-xs font-semibold mb-1">From</label><input type="date" name="from" value="{{ $from }}" class="rounded-xl border border-ink/20 px-3 py-2"></div>
  <div><label class="block text-xs font-semibold mb-1">To</label><input type="date" name="to" value="{{ $to }}" class="rounded-xl border border-ink/20 px-3 py-2"></div>
  <button class="{{ $btn }} bg-brand text-white">Show report</button>
</form>

<p class="text-sm text-ink/70 mb-3">Report for {{ \Carbon\Carbon::parse($from)->format('M j, Y') }} to {{ \Carbon\Carbon::parse($to)->format('M j, Y') }}. Counts orders marked <strong>Collected</strong>, by pickup date.</p>

<div class="grid grid-cols-3 gap-3 mb-8">
  <div class="bg-white rounded-2xl p-4 shadow-sm"><p class="text-sm text-ink/70">Revenue</p><p class="font-serif text-2xl text-brand">₱{{ number_format($totalRevenue, 2) }}</p></div>
  <div class="bg-white rounded-2xl p-4 shadow-sm"><p class="text-sm text-ink/70">Orders</p><p class="font-serif text-2xl text-ink">{{ $totalOrders }}</p></div>
  <div class="bg-white rounded-2xl p-4 shadow-sm"><p class="text-sm text-ink/70">Items sold</p><p class="font-serif text-2xl text-ink">{{ $totalItems }}</p></div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
  <div>
    <h2 class="font-serif text-2xl font-bold mb-3">By day</h2>
    <div class="bg-white rounded-2xl shadow-sm overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-brand-light text-left"><tr><th class="p-3">Date</th><th class="p-3">Orders</th><th class="p-3">Revenue</th></tr></thead>
        <tbody>
        @forelse($byDay as $d)
          <tr class="border-t"><td class="p-3">{{ \Carbon\Carbon::parse($d->day)->format('M j, Y') }}</td><td class="p-3">{{ $d->orders }}</td><td class="p-3">₱{{ number_format($d->revenue, 2) }}</td></tr>
        @empty
          <tr><td colspan="3" class="p-4 text-ink/60">No collected orders in this period.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div>
    <h2 class="font-serif text-2xl font-bold mb-3">By product</h2>
    <div class="bg-white rounded-2xl shadow-sm overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-brand-light text-left"><tr><th class="p-3">Product</th><th class="p-3">Category</th><th class="p-3">Sold</th><th class="p-3">Revenue</th></tr></thead>
        <tbody>
        @forelse($byProduct as $p)
          <tr class="border-t"><td class="p-3 font-medium">{{ $p->name }}</td><td class="p-3">{{ $p->category }}</td><td class="p-3">{{ $p->qty }}</td><td class="p-3">₱{{ number_format($p->revenue, 2) }}</td></tr>
        @empty
          <tr><td colspan="4" class="p-4 text-ink/60">No sales in this period.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>

    <h2 class="font-serif text-2xl font-bold mt-8 mb-3">By category</h2>
    <div class="bg-white rounded-2xl shadow-sm overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-brand-light text-left"><tr><th class="p-3">Category</th><th class="p-3">Sold</th><th class="p-3">Revenue</th></tr></thead>
        <tbody>
        @forelse($byCategory as $name => $c)
          <tr class="border-t"><td class="p-3 font-medium">{{ $name }}</td><td class="p-3">{{ $c['qty'] }}</td><td class="p-3">₱{{ number_format($c['revenue'], 2) }}</td></tr>
        @empty
          <tr><td colspan="3" class="p-4 text-ink/60">No sales in this period.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
