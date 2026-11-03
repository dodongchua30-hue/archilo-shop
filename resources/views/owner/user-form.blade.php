@extends('layout')
@section('title', ($user->exists ? 'Edit' : 'Add') . ' account | Archilo')
@section('content')
<div class="mb-6">
  <h1 class="font-serif text-3xl font-bold">{{ $user->exists ? 'Edit account' : 'Add account' }}</h1>
  <a href="{{ route('owner.users') }}" class="text-brand font-semibold">&larr; Back to accounts</a>
</div>

@if($errors->count() > 1)
  <div class="mb-4 rounded-xl bg-red-100 text-red-800 px-4 py-3">@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>
@endif

<form method="POST" action="{{ $user->exists ? route('owner.users.update', $user) : route('owner.users.store') }}"
      class="bg-white rounded-2xl shadow-sm p-5 lg:p-8 max-w-xl space-y-4">
  @csrf
  @if($user->exists) @method('PUT') @endif

  <div>
    <label class="block text-sm font-semibold mb-1">Name</label>
    <input name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-xl border border-ink/20 px-3 py-3">
  </div>
  <div>
    <label class="block text-sm font-semibold mb-1">Email</label>
    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-xl border border-ink/20 px-3 py-3">
  </div>
  <div>
    <label class="block text-sm font-semibold mb-1">Role</label>
    <select name="role" class="w-full rounded-xl border border-ink/20 px-3 py-3 bg-white">
      @foreach($roles as $r)<option value="{{ $r }}" {{ old('role', $user->role ?: 'staff') === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>@endforeach
    </select>
    <p class="text-xs text-ink/60 mt-1">Customer: shops and reserves. Staff: products, stock and pickup orders. Admin: everything, including reports and accounts.</p>
  </div>
  <div>
    <label class="block text-sm font-semibold mb-1">{{ $user->exists ? 'New password (leave blank to keep the current one)' : 'Password' }}</label>
    <input type="password" name="password" minlength="8" {{ $user->exists ? '' : 'required' }} class="w-full rounded-xl border border-ink/20 px-3 py-3">
    <p class="text-xs text-ink/60 mt-1">At least 8 characters.</p>
  </div>

  <button class="{{ $btn }} bg-brand text-white">{{ $user->exists ? 'Save changes' : 'Add account' }}</button>
</form>
@endsection
