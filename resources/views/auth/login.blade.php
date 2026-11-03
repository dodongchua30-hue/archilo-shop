@extends('layout')
@section('title', 'Log in | Archilo')
@section('content')
<div class="max-w-md mx-auto bg-white rounded-3xl shadow-sm p-6 sm:p-8">
  <h1 class="font-serif text-3xl font-bold">Welcome back</h1>
  <p class="text-ink/70 mb-6">Log in to continue shopping</p>
  @if(session('status'))<div class="mb-4 rounded-xl bg-green-100 text-green-800 px-4 py-3 text-sm">{{ session('status') }}</div>@endif
  <form method="POST" action="{{ route('login') }}" class="space-y-4">@csrf
    <label class="block text-sm font-medium">Email<input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-xl border border-ink/20 px-3 py-3"></label>
    <label class="block text-sm font-medium">Password<input type="password" name="password" required class="mt-1 w-full rounded-xl border border-ink/20 px-3 py-3"></label>
    <div class="flex items-center justify-between text-sm">
      <label class="flex items-center gap-2"><input type="checkbox" name="remember"> Remember me</label>
      <a href="{{ route('password.request') }}" class="text-brand font-semibold">Forgot password?</a>
    </div>
    <button class="{{ $btn }} w-full bg-buy text-white hover:opacity-90">Log in</button>
  </form>
  <p class="text-sm text-center mt-5">New here? <a href="{{ route('register') }}" class="text-brand font-semibold">Create an account</a></p>
</div>
@endsection
