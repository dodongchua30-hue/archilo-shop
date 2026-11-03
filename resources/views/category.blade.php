@extends('layout')
@section('title', $category.' | Archilo')
@section('content')
<h1 class="font-serif text-3xl lg:text-4xl font-bold">{{ $category }}</h1>
<div class="flex flex-wrap items-center gap-2 mt-4 mb-6 text-sm">
  <span class="font-medium mr-1">Size:</span>
  @foreach(['S','M','L','XL'] as $s)
    <a href="{{ request('size')==$s ? route('category',$category) : route('category',$category).'?size='.$s }}"
       class="chip inline-flex items-center px-4 rounded-full border {{ request('size')==$s ? 'bg-ink text-white border-ink' : 'bg-white border-ink/20' }}">{{ $s }}</a>
  @endforeach
</div>
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 lg:gap-6">
  @forelse($products as $p) @include('partials.card') @empty <p class="col-span-full text-ink/70">No items found.</p> @endforelse
</div>
@endsection
