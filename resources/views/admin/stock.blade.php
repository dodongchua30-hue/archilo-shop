@extends('layout')
@section('title', 'Stock Checker | Archilo')
@section('content')
<div class="flex flex-wrap justify-between items-center gap-3 mb-6">
  <h1 class="font-serif text-3xl font-bold">Stock Checker</h1>
  <a href="{{ route('admin.orders') }}" class="{{ $btn }} bg-ink text-white">Orders ({{ $pending }} reserved)</a>
</div>
<div class="grid grid-cols-2 gap-3 mb-6">
  <div class="bg-white rounded-2xl p-4 shadow-sm"><p class="text-sm text-ink/70">Low or out of stock</p><p class="font-serif text-3xl text-blush">{{ $low }}</p></div>
  <div class="bg-white rounded-2xl p-4 shadow-sm"><p class="text-sm text-ink/70">Reserved orders</p><p class="font-serif text-3xl text-brand">{{ $pending }}</p></div>
</div>
<form class="flex flex-wrap gap-2 mb-4">
  <input name="q" value="{{ request('q') }}" placeholder="Search product" class="flex-1 min-w-[12rem] rounded-xl border border-ink/20 px-3 py-3">
  <select name="status" class="rounded-xl border border-ink/20 px-3 py-3 bg-white">
    <option value="">All</option><option value="low" {{ request('status')=='low'?'selected':'' }}>Low</option><option value="out" {{ request('status')=='out'?'selected':'' }}>Out</option></select>
  <button class="{{ $btn }} bg-brand text-white">Check</button>
</form>
<div class="bg-white rounded-2xl shadow-sm overflow-x-auto">
  <table class="w-full text-sm">
    <thead class="bg-brand-light text-left"><tr><th class="p-3">Product</th><th class="p-3">Size / colour</th><th class="p-3">Stock</th><th class="p-3">Status</th><th class="p-3">Adjust</th></tr></thead>
    <tbody>
    @foreach($variants as $v)
      @php [$label,$cls] = $v->stock==0 ? ['Out','bg-red-100 text-red-700'] : ($v->stock <= $v->low_stock_at ? ['Low','bg-amber-100 text-amber-700'] : ['OK','bg-green-100 text-green-700']); @endphp
      <tr class="border-t">
        <td class="p-3 font-medium">{{ $v->product->name }}</td><td class="p-3">{{ $v->label() }}</td><td class="p-3 font-semibold">{{ $v->stock }}</td>
        <td class="p-3"><span class="rounded-full px-3 py-1 text-xs font-semibold {{ $cls }}">{{ $label }}</span></td>
        <td class="p-3"><form method="POST" action="{{ route('admin.adjust',$v) }}" class="flex gap-2">@csrf
          <input type="number" name="change" value="1" class="w-20 rounded-lg border border-ink/20 px-2 py-2"><button class="rounded-lg bg-ink text-white px-3">Update</button></form></td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>
<p class="text-xs text-ink/60 mt-2">Enter a positive number to add stock, a negative number to remove stock (e.g. walk-in sale).</p>
@endsection
