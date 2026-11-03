@extends('layout')
@section('title', 'Wishlist | Archilo')
@section('content')
<h1 class="font-serif text-3xl lg:text-4xl font-bold mb-6">Your wishlist</h1>
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 lg:gap-6">
  @forelse($products as $p) @include('partials.card') @empty
    <p class="col-span-full text-ink/70">Nothing saved yet. Tap the heart on a product to save it here. <a href="{{ route('home') }}" class="text-brand font-semibold">Keep shopping</a></p>
  @endforelse
</div>
@endsection
