<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Archilo Fashion Boutique')</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = { theme: { extend: {
  colors: { brand: { DEFAULT:'#B08462', dark:'#8F6A4B', light:'#EAD9C8' }, ink:'#4A4658', paper:'#F5F5F5', panel:'#D9D9D9', buy:'#6F94B8', blush:'#E0829F' },
  fontFamily: { serif:['"Playfair Display"','serif'], sans:['Inter','sans-serif'] } } } }
</script>
<style>
  .lift { transition: transform .2s, box-shadow .2s; }
  @media (hover: hover) { .lift:hover { transform: translateY(-3px); box-shadow: 0 10px 24px rgba(74,70,88,.15); } }
  @media (pointer: coarse) { .btn, select, input, .chip { min-height: 44px; } }
  @media (min-width: 768px) and (max-width: 1023px) { .tablet-narrow { max-width: 42rem; margin-inline: auto; } }
  @media (prefers-reduced-motion: reduce) { * { transition: none !important; } }
  @media print { header, nav.bottom, footer { display: none !important; } }
</style>
</head>
@php
  $count = collect(session('cart', []))->sum();
  $staff = auth()->check() && in_array(auth()->user()->role, ['admin','staff']);
  $btn = 'btn inline-flex items-center justify-center rounded-xl px-5 py-3 font-semibold transition';
@endphp
<body class="bg-paper text-ink font-sans pb-20 md:pb-0">
<header class="bg-white shadow-sm sticky top-0 z-30">
  <div class="max-w-7xl mx-auto px-4 lg:px-8 h-16 flex items-center justify-between">
    <a href="{{ route('home') }}" class="font-serif text-xl lg:text-2xl font-bold text-brand">Archilo <span class="text-ink font-semibold">Boutique</span></a>
    <nav class="hidden md:flex gap-6 text-sm font-medium">
      @foreach(['Jeans','Skirts','Dresses','Tops'] as $c)<a href="{{ route('category',$c) }}" class="hover:text-brand">{{ $c }}</a>@endforeach
      @if($staff)<a href="{{ route('staff') }}" class="text-brand font-semibold">Staff</a><a href="{{ route('admin') }}" class="text-brand font-semibold">Stock Checker</a>@endif
      @if(auth()->check() && auth()->user()->role === 'admin')<a href="{{ route('owner.dashboard') }}" class="text-brand font-semibold">Admin</a>@endif
    </nav>
    <div class="flex items-center gap-4 text-sm font-medium">
      <form action="{{ route('search') }}" method="GET" class="hidden lg:block"><input name="q" value="{{ request('q') }}" placeholder="Search" aria-label="Search products" class="w-40 rounded-full border border-ink/20 px-4 py-2 text-sm font-normal"></form>
      <a href="{{ route('cart') }}" class="hidden md:inline hover:text-brand">Cart ({{ $count }})</a>
      @auth <a href="{{ route('wishlist') }}" class="hidden md:inline hover:text-brand">Wishlist</a>
        <a href="{{ route('orders') }}" class="hidden md:inline hover:text-brand">My orders</a>
        <form method="POST" action="{{ route('logout') }}">@csrf<button class="hover:text-brand">Log out</button></form>
      @else <a href="{{ route('login') }}" class="hover:text-brand">Log in</a>
        <a href="{{ route('register') }}" class="{{ $btn }} bg-brand text-white hover:bg-brand-dark !py-2 hidden sm:inline-flex">Register</a> @endauth
    </div>
  </div>
</header>

<main class="max-w-7xl mx-auto px-4 lg:px-8 py-6 lg:py-10">
  @if($errors->any())<div class="mb-4 rounded-xl bg-red-50 text-red-700 px-4 py-3 text-sm">{{ $errors->first() }}</div>@endif
  @yield('content')
</main>

<footer class="bg-ink text-white/80 mt-10">
  <div class="max-w-7xl mx-auto px-4 lg:px-8 py-8 grid gap-4 md:grid-cols-2 text-sm">
    <div><p class="font-serif text-lg text-white">Archilo Fashion Boutique</p><p>In-store pickup only. No delivery.</p></div>
    <div><p>Visit us: [store address]</p><p>Open daily: [opening hours]</p></div>
  </div>
</footer>

<nav class="bottom md:hidden fixed bottom-0 inset-x-0 bg-white border-t z-30 flex justify-around items-center py-2 text-xs font-medium">
  @php $tabs = [['Home','home',route('home')],['Search','search',route('search')],['Cart ('.$count.')','cart',route('cart')],
    ['Wishlist','wishlist',auth()->check()?route('wishlist'):route('login')],
    $staff ? ['Staff','staff*',route('staff')] : ['Orders','orders',auth()->check()?route('orders'):route('login')]]; @endphp
  @foreach($tabs as [$label,$match,$url])
    <a href="{{ $url }}" class="px-3 py-2 rounded-full {{ request()->routeIs($match) ? 'bg-ink text-white' : '' }}">{{ $label }}</a>
  @endforeach
</nav>
</body>
</html>
