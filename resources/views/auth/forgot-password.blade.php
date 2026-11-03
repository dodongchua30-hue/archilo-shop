@extends('layout')
@section('title', 'Forgot password | Archilo')
@section('content')
<div class="max-w-md mx-auto bg-white rounded-3xl shadow-sm p-6 sm:p-8">
  <h1 class="font-serif text-3xl font-bold">Forgot password?</h1>
  <p class="text-ink/70 mb-6">Enter your email and we will prepare a link to choose a new password.</p>

  @if(session('status'))<div class="mb-4 rounded-xl bg-green-100 text-green-800 px-4 py-3 text-sm">{{ session('status') }}</div>@endif
  @if(session('demo_link'))
    <div class="mb-4 rounded-xl bg-amber-50 text-amber-900 px-4 py-3 text-sm">
      <p class="font-semibold">Demo mode</p>
      <p>No mail server is set up, so the reset link is shown here instead of being emailed.</p>
      <a href="{{ session('demo_link') }}" class="{{ $btn }} bg-brand text-white mt-3">Choose a new password</a>
    </div>
  @endif

  <form method="POST" action="{{ route('password.email') }}" class="space-y-4">@csrf
    <label class="block text-sm font-medium">Email<input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-xl border border-ink/20 px-3 py-3"></label>
    <button class="{{ $btn }} w-full bg-buy text-white hover:opacity-90">Send reset link</button>
  </form>
  <p class="text-sm text-center mt-5"><a href="{{ route('login') }}" class="text-brand font-semibold">Back to log in</a></p>
</div>
@endsection
