@extends('layout')
@section('title', 'Products | Archilo')
@section('content')
<div class="flex flex-wrap justify-between items-center gap-3 mb-6">
  <div>
    <h1 class="font-serif text-3xl font-bold">Products</h1>
    <a href="{{ route('staff') }}" class="text-brand font-semibold">&larr; Staff dashboard</a>
  </div>
  <a href="{{ route('staff.products.create') }}" class="{{ $btn }} bg-brand text-white">Add product</a>
</div>

@if(session('ok'))<div class="mb-4 rounded-xl bg-green-100 text-green-800 px-4 py-3">{{ session('ok') }}</div>@endif
@if($errors->any())<div class="mb-4 rounded-xl bg-red-100 text-red-800 px-4 py-3">{{ $errors->first() }}</div>@endif

<div class="bg-white rounded-2xl shadow-sm overflow-x-auto">
  <table class="w-full text-sm">
    <thead class="bg-brand-light text-left"><tr><th class="p-3">Photo</th><th class="p-3">Name</th><th class="p-3">Category</th><th class="p-3">Price</th><th class="p-3">Total stock</th><th class="p-3">Actions</th></tr></thead>
    <tbody>
    @foreach($products as $p)
      <tr class="border-t">
        <td class="p-3">
          <div class="w-14 h-16 rounded-xl bg-brand-light overflow-hidden flex items-center justify-center font-serif text-brand">
            @if($p->image)<img src="{{ asset('images/'.$p->image) }}" alt="" class="w-full h-full object-cover" onerror="this.style.display='none'">@else {{ substr($p->name, 0, 1) }} @endif
          </div>
        </td>
        <td class="p-3 font-medium">{{ $p->name }}</td>
        <td class="p-3">{{ $p->category }}</td>
        <td class="p-3">₱{{ number_format($p->price, 2) }}</td>
        <td class="p-3 font-semibold">{{ $p->variants_sum_stock ?? 0 }}</td>
        <td class="p-3">
          <div class="flex gap-2">
            <a href="{{ route('staff.products.edit', $p) }}" class="rounded-lg bg-ink text-white px-3 py-2">Edit</a>
            <form method="POST" action="{{ route('staff.products.destroy', $p) }}" onsubmit="return confirm('Delete {{ addslashes($p->name) }}?')">@csrf @method('DELETE')
              <button class="rounded-lg bg-red-600 text-white px-3 py-2">Delete</button>
            </form>
          </div>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>
@endsection
