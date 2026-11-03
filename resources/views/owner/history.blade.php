@extends('layout')
@section('title', 'Stock history | Archilo')
@section('content')
<div class="mb-6">
  <h1 class="font-serif text-3xl font-bold">Stock history</h1>
  <a href="{{ route('owner.dashboard') }}" class="text-brand font-semibold">&larr; Admin dashboard</a>
  <p class="text-sm text-ink/70 mt-2">The latest 200 stock changes. Changes made before this feature was installed are not recorded.</p>
</div>

<form class="flex flex-wrap gap-2 mb-4">
  <input name="q" value="{{ request('q') }}" placeholder="Search product" class="flex-1 min-w-[12rem] rounded-xl border border-ink/20 px-3 py-3">
  <button class="{{ $btn }} bg-brand text-white">Search</button>
</form>

<div class="bg-white rounded-2xl shadow-sm overflow-x-auto">
  <table class="w-full text-sm">
    <thead class="bg-brand-light text-left"><tr><th class="p-3">When</th><th class="p-3">Product</th><th class="p-3">Size</th><th class="p-3">Change</th><th class="p-3">Stock after</th><th class="p-3">Reason</th><th class="p-3">By</th></tr></thead>
    <tbody>
    @forelse($logs as $l)
      <tr class="border-t">
        <td class="p-3 whitespace-nowrap">{{ $l->created_at->format('M j, g:i A') }}</td>
        <td class="p-3 font-medium">{{ $l->variant->product->name ?? '' }}</td>
        <td class="p-3">{{ $l->variant->size ?? '' }}</td>
        <td class="p-3 font-semibold {{ $l->delta > 0 ? 'text-green-700' : 'text-red-700' }}">{{ $l->delta > 0 ? '+' : '' }}{{ $l->delta }}</td>
        <td class="p-3">{{ $l->stock_after }}</td>
        <td class="p-3">{{ $l->reason }}</td>
        <td class="p-3">{{ $l->user->name ?? 'System' }}</td>
      </tr>
    @empty
      <tr><td colspan="7" class="p-4 text-ink/60">No stock changes recorded yet.</td></tr>
    @endforelse
    </tbody>
  </table>
</div>
@endsection
