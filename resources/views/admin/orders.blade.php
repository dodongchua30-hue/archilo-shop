@extends('layout')
@section('title', 'Orders | Archilo')
@section('content')
<div class="flex justify-between items-center mb-6"><h1 class="font-serif text-3xl font-bold">Pickup orders</h1><a href="{{ route('admin') }}" class="text-brand font-semibold">&larr; Stock Checker</a></div>
<div class="bg-white rounded-2xl shadow-sm overflow-x-auto">
  <table class="w-full text-sm">
    <thead class="bg-brand-light text-left"><tr><th class="p-3">Code</th><th class="p-3">Customer</th><th class="p-3">Pickup</th><th class="p-3">Total</th><th class="p-3">Status</th></tr></thead>
    <tbody>
    @foreach($orders as $o)
      <tr class="border-t"><td class="p-3 font-semibold"><a href="{{ route('order.show',$o) }}" class="text-brand">{{ $o->code }}</a></td>
        <td class="p-3">{{ $o->user->name }}</td><td class="p-3">{{ $o->pickup_date->format('M j') }}, {{ $o->pickup_slot }}</td><td class="p-3">₱{{ number_format($o->total,0) }}</td>
        <td class="p-3"><form method="POST" action="{{ route('admin.status',$o) }}" class="flex gap-2">@csrf
          <select name="status" class="rounded-lg border border-ink/20 px-2 py-2 bg-white">@foreach(['reserved','ready','collected','cancelled'] as $s)<option value="{{ $s }}" {{ $o->status==$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach</select>
          <button class="rounded-lg bg-ink text-white px-3">Save</button></form></td></tr>
    @endforeach
    </tbody>
  </table>
</div>
@endsection
