@extends('layout')
@section('title', ($product->exists ? 'Edit' : 'Add') . ' product | Archilo')
@section('content')
<div class="mb-6">
  <h1 class="font-serif text-3xl font-bold">{{ $product->exists ? 'Edit product' : 'Add product' }}</h1>
  <a href="{{ route('staff.products') }}" class="text-brand font-semibold">&larr; Back to products</a>
</div>

@if(session('ok'))<div class="mb-4 rounded-xl bg-green-100 text-green-800 px-4 py-3">{{ session('ok') }}</div>@endif
@if($errors->count() > 1)
  <div class="mb-4 rounded-xl bg-red-100 text-red-800 px-4 py-3">
    @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
  </div>
@endif

<form method="POST" enctype="multipart/form-data"
      action="{{ $product->exists ? route('staff.products.update', $product) : route('staff.products.store') }}"
      class="bg-white rounded-2xl shadow-sm p-5 lg:p-8 max-w-2xl space-y-4">
  @csrf
  @if($product->exists) @method('PUT') @endif

  <div>
    <label class="block text-sm font-semibold mb-1">Name</label>
    <input name="name" value="{{ old('name', $product->name) }}" required class="w-full rounded-xl border border-ink/20 px-3 py-3">
  </div>

  <div class="grid grid-cols-2 gap-4">
    <div>
      <label class="block text-sm font-semibold mb-1">Category</label>
      <select name="category" class="w-full rounded-xl border border-ink/20 px-3 py-3 bg-white">
        @foreach($categories as $c)<option value="{{ $c }}" {{ old('category', $product->category) === $c ? 'selected' : '' }}>{{ $c }}</option>@endforeach
      </select>
    </div>
    <div>
      <label class="block text-sm font-semibold mb-1">Price (₱)</label>
      <input name="price" type="number" step="0.01" min="0" value="{{ old('price', $product->price) }}" required class="w-full rounded-xl border border-ink/20 px-3 py-3">
    </div>
  </div>

  <div>
    <label class="block text-sm font-semibold mb-1">Description</label>
    <textarea name="description" rows="3" class="w-full rounded-xl border border-ink/20 px-3 py-3">{{ old('description', $product->description) }}</textarea>
  </div>

  <div>
    <label class="block text-sm font-semibold mb-1">Photo (JPG, PNG, WebP or AVIF, up to 5 MB)</label>
    @if($product->image)
      <img src="{{ asset('images/'.$product->image) }}" alt="" class="w-24 h-28 object-cover rounded-xl mb-2" onerror="this.style.display='none'">
      <p class="text-xs text-ink/60 mb-2">Choose a new file only if you want to replace the current photo.</p>
    @endif
    <input type="file" name="photo" accept="image/*" class="block w-full text-sm">
  </div>

  @unless($product->exists)
    <div>
      <label class="block text-sm font-semibold mb-1">Colours (optional)</label>
      <input name="colours" value="{{ old('colours') }}" placeholder="e.g. Black, White, Blue" maxlength="200" class="w-full rounded-xl border border-ink/20 px-3 py-3">
      <p class="text-xs text-ink/60 mt-1">Separate colours with commas. Leave blank if the product has only one colour.</p>
    </div>
    <div>
      <label class="block text-sm font-semibold mb-1">Starting stock per size (for each colour)</label>
      <div class="grid grid-cols-4 gap-3">
        @foreach($sizes as $s)
          <div>
            <span class="block text-xs text-ink/70 mb-1">{{ $s }}</span>
            <input type="number" min="0" name="stock[{{ $s }}]" value="{{ old('stock.'.$s, 0) }}" class="w-full rounded-xl border border-ink/20 px-3 py-3">
          </div>
        @endforeach
      </div>
    </div>
  @else
    <p class="text-sm text-ink/70">To change stock levels, use the <a href="{{ route('admin') }}" class="text-brand font-semibold">Stock Checker</a>.</p>
  @endunless

  <button class="{{ $btn }} bg-brand text-white">{{ $product->exists ? 'Save changes' : 'Add product' }}</button>
</form>

@if($product->exists)
<div class="bg-white rounded-2xl shadow-sm p-5 lg:p-8 max-w-2xl mt-6">
  <h2 class="font-serif text-2xl font-bold mb-2">Colours</h2>
  <p class="text-sm text-ink/70 mb-4">Every colour has its own stock for each size. A product with one colour named "Classic" shows no colour choice to customers. Rename it to a real colour, or add another colour below.</p>

  @foreach($product->variants->groupBy('color') as $colour => $vs)
    <form method="POST" action="{{ route('staff.products.colours.rename', $product) }}" class="flex flex-wrap items-center gap-2 mb-3">@csrf @method('PUT')
      <input type="hidden" name="old" value="{{ $colour }}">
      <input name="colour" value="{{ $colour }}" maxlength="30" required class="rounded-xl border border-ink/20 px-3 py-2 w-44">
      <span class="text-sm text-ink/70">{{ $vs->sum('stock') }} in stock</span>
      <button class="rounded-lg bg-ink text-white px-3 py-2 text-sm">Rename</button>
    </form>
  @endforeach

  <h3 class="font-semibold mt-6 mb-2">Add a colour</h3>
  <form method="POST" action="{{ route('staff.products.colours.add', $product) }}" class="space-y-3">@csrf
    <input name="colour" placeholder="e.g. Black" maxlength="30" required class="w-full rounded-xl border border-ink/20 px-3 py-3">
    <div class="grid grid-cols-4 gap-3">
      @foreach($sizes as $s)
        <div>
          <span class="block text-xs text-ink/70 mb-1">{{ $s }}</span>
          <input type="number" min="0" name="stock[{{ $s }}]" value="0" class="w-full rounded-xl border border-ink/20 px-3 py-3">
        </div>
      @endforeach
    </div>
    <button class="{{ $btn }} bg-brand text-white">Add colour</button>
  </form>
</div>
@endif
@endsection
