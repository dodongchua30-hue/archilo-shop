@extends('layout')
@section('title', 'Search | Archilo')
@section('content')
<h1 class="font-serif text-3xl lg:text-4xl font-bold mb-4">Search</h1>
<form action="{{ route('search') }}" method="GET" class="flex gap-2 mb-6">
  <input name="q" value="{{ $term }}" placeholder="Search jeans, dresses, tops..." autofocus class="flex-1 rounded-xl border border-ink/20 px-4 py-3 bg-white">
  <button class="{{ $btn }} bg-brand text-white">Search</button>
</form>

@if($term === '')
  <p class="text-ink/70">Type a product name or a category, such as "jeans" or "dress".</p>
  <div class="flex flex-wrap gap-2 mt-4">
    @foreach(['Jeans','Skirts','Dresses','Tops'] as $c)
      <a href="{{ route('category',$c) }}" class="chip inline-flex items-center px-4 rounded-full border border-ink/20 bg-white">{{ $c }}</a>
    @endforeach
  </div>
@else
  <p class="text-sm text-ink/70 mb-4">{{ $products->count() }} {{ \Illuminate\Support\Str::plural('result', $products->count()) }} for "{{ $term }}"</p>
  <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 lg:gap-6">
    @forelse($products as $p) @include('partials.card') @empty
      <p class="col-span-full text-ink/70">No products found. Try another word, or browse a category from the home page.</p>
    @endforelse
  </div>
@endif
@endsection
