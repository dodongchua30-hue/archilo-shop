@extends('layout')
@section('title', 'Reset password | Archilo')
@section('content')
<div class="max-w-md mx-auto bg-white rounded-3xl shadow-sm p-6 sm:p-8">
  <h1 class="font-serif text-3xl font-bold">Choose a new password</h1>
  <p class="text-ink/70 mb-6">Use at least 8 characters.</p>
  <form method="POST" action="{{ route('password.update') }}" class="space-y-4">@csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <label class="block text-sm font-medium">Email<input type="email" name="email" value="{{ old('email', $email) }}" required class="mt-1 w-full rounded-xl border border-ink/20 px-3 py-3"></label>
    <label class="block text-sm font-medium">New password<input type="password" name="password" required minlength="8" class="mt-1 w-full rounded-xl border border-ink/20 px-3 py-3"></label>
    <label class="block text-sm font-medium">Confirm new password<input type="password" name="password_confirmation" required minlength="8" class="mt-1 w-full rounded-xl border border-ink/20 px-3 py-3"></label>
    <button class="{{ $btn }} w-full bg-buy text-white hover:opacity-90">Change password</button>
  </form>
</div>
@endsection
