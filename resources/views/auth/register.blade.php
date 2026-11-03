@extends('layout')
@section('title', 'Register | Archilo')
@section('content')
<div class="max-w-md mx-auto bg-white rounded-3xl shadow-sm p-6 sm:p-8">
  <h1 class="font-serif text-3xl font-bold">Create your account</h1>
  <p class="text-ink/70 mb-6">Reserve items and track your pickups</p>
  <form method="POST" action="{{ route('register') }}" class="space-y-4">@csrf
    <label class="block text-sm font-medium">Full name<input name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded-xl border border-ink/20 px-3 py-3"></label>
    <label class="block text-sm font-medium">Email<input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-xl border border-ink/20 px-3 py-3"></label>
    <label class="block text-sm font-medium">Password (8+ characters)<input type="password" name="password" required class="mt-1 w-full rounded-xl border border-ink/20 px-3 py-3"></label>
    <label class="block text-sm font-medium">Confirm password<input type="password" name="password_confirmation" required class="mt-1 w-full rounded-xl border border-ink/20 px-3 py-3"></label>
    <button class="{{ $btn }} w-full bg-buy text-white hover:opacity-90">Create account</button>
  </form>
  <p class="text-sm text-center mt-5">Already have an account? <a href="{{ route('login') }}" class="text-brand font-semibold">Log in</a></p>
</div>
@endsection
