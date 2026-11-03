@extends('layout')
@section('content')
<section class="relative overflow-hidden rounded-[3rem] bg-brand-dark text-white min-h-[22rem] lg:min-h-[26rem]">
  <img src="{{ asset('images/banner.jfif') }}" alt="" class="absolute inset-0 w-full h-full object-cover scale-110" onerror="this.style.display='none'">
  <div class="absolute inset-0 bg-gradient-to-r from-black/40 via-black/10 to-transparent"></div>
  <div class="relative z-10 flex flex-col justify-center min-h-[22rem] lg:min-h-[26rem] p-8 lg:p-14 max-w-xl">
    <p class="uppercase tracking-widest text-xs text-brand-light">New arrivals</p>
    <h1 class="font-serif text-4xl lg:text-6xl font-bold mt-2 leading-tight">Dress your vibe</h1>
    <p class="mt-4 text-white/90 max-w-md">Browse our latest jeans, skirts and dresses online, reserve your size, and pick it up in store.</p>
    <a href="{{ route('category','Jeans') }}" class="{{ $btn }} bg-ink text-white hover:opacity-90 mt-6 self-start">Shop now</a>
  </div>
</section>

<form action="{{ route('search') }}" method="GET" class="mt-6 flex gap-2">
  <input name="q" placeholder="Search jeans, dresses, tops..." aria-label="Search products" class="flex-1 rounded-xl border border-ink/20 px-4 py-3 bg-white">
  <button class="{{ $btn }} bg-brand text-white">Search</button>
</form>

<section class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-3 lg:gap-6">
  @foreach(['Jeans'=>'jeans.jfif','Skirts'=>'skirts.jfif','Dresses'=>'dresses.jfif','Tops'=>'tops.jfif'] as $c => $img)
    <a href="{{ route('category',$c) }}" class="lift relative block overflow-hidden rounded-2xl bg-brand-light h-32 md:h-44">
      <img src="{{ asset('images/categories/'.$img) }}" alt="{{ $c }}" loading="lazy" class="absolute inset-0 w-full h-full object-cover" onerror="this.style.display='none'">
      <span class="absolute inset-0 bg-black/30"></span>
      <span class="relative z-10 flex h-full items-center justify-center font-serif text-xl font-semibold text-white">{{ $c }}</span>
    </a>
  @endforeach
</section>

<h2 class="font-serif text-2xl font-bold mt-10 mb-4">New arrivals</h2>
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 lg:gap-6">
  @foreach($products as $p) @include('partials.card') @endforeach
</div>
@endsection
