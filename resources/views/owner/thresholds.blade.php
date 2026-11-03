@extends('layout')
@section('title', 'Low-stock thresholds | Archilo')
@section('content')
<div class="mb-6">
  <h1 class="font-serif text-3xl font-bold">Low-stock thresholds</h1>
  <a href="{{ route('owner.dashboard') }}" class="text-brand font-semibold">&larr; Admin dashboard</a>
  <p class="text-sm text-ink/70 mt-2">An item is marked <strong>Low</strong> when its stock is at or below its threshold.</p>
</div>

@if(session('ok'))<div class="mb-4 rounded-xl bg-green-100 text-green-800 px-4 py-3">{{ session('ok') }}</div>@endif

<form method="POST" action="{{ route('owner.thresholds.all') }}" class="flex flex-wrap items-end gap-3 mb-6 bg-white rounded-2xl shadow-sm p-4">
  @csrf
  <div><label class="block text-xs font-semibold mb-1">Set the same threshold for every item</label>
    <input type="number" name="low_stock_at" min="0" max="500" value="3" class="w-28 rounded-xl border border-ink/20 px-3 py-2"></div>
  <button class="{{ $btn }} bg-ink text-white" onclick="return confirm('Change the threshold of every item?')">Apply to all</button>
</form>

<div class="bg-white rounded-2xl shadow-sm overflow-x-auto">
  <table class="w-full text-sm">
    <thead class="bg-brand-light text-left"><tr><th class="p-3">Product</th><th class="p-3">Size</th><th class="p-3">Stock</th><th class="p-3">Threshold</th></tr></thead>
    <tbody>
    @foreach($variants as $v)
      <tr class="border-t">
        <td class="p-3 font-medium">{{ $v->product->name }}</td>
        <td class="p-3">{{ $v->size }}</td>
        <td class="p-3 font-semibold">{{ $v->stock }}</td>
        <td class="p-3">
          <form method="POST" action="{{ route('owner.thresholds.set', $v) }}" class="flex gap-2">@csrf
            <input type="number" name="low_stock_at" min="0" max="500" value="{{ $v->low_stock_at }}" class="w-20 rounded-lg border border-ink/20 px-2 py-2">
            <button class="rounded-lg bg-ink text-white px-3">Save</button>
          </form>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>
@endsection
