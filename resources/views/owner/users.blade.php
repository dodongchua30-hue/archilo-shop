@extends('layout')
@section('title', 'Accounts | Archilo')
@section('content')
<div class="flex flex-wrap justify-between items-center gap-3 mb-6">
  <div>
    <h1 class="font-serif text-3xl font-bold">Accounts</h1>
    <a href="{{ route('owner.dashboard') }}" class="text-brand font-semibold">&larr; Admin dashboard</a>
  </div>
  <a href="{{ route('owner.users.create') }}" class="{{ $btn }} bg-brand text-white">Add account</a>
</div>

@if(session('ok'))<div class="mb-4 rounded-xl bg-green-100 text-green-800 px-4 py-3">{{ session('ok') }}</div>@endif

<form class="flex flex-wrap gap-2 mb-4">
  <input name="q" value="{{ request('q') }}" placeholder="Search name or email" class="flex-1 min-w-[12rem] rounded-xl border border-ink/20 px-3 py-3">
  <button class="{{ $btn }} bg-ink text-white">Search</button>
</form>

<div class="bg-white rounded-2xl shadow-sm overflow-x-auto">
  <table class="w-full text-sm">
    <thead class="bg-brand-light text-left"><tr><th class="p-3">Name</th><th class="p-3">Email</th><th class="p-3">Role</th><th class="p-3">Orders</th><th class="p-3">Joined</th><th class="p-3">Actions</th></tr></thead>
    <tbody>
    @forelse($users as $u)
      <tr class="border-t">
        <td class="p-3 font-medium">{{ $u->name }} @if($u->id === auth()->id())<span class="text-xs text-ink/60">(you)</span>@endif</td>
        <td class="p-3">{{ $u->email }}</td>
        <td class="p-3"><span class="rounded-full px-3 py-1 text-xs font-semibold {{ $u->role === 'admin' ? 'bg-ink text-white' : ($u->role === 'staff' ? 'bg-brand text-white' : 'bg-brand-light text-ink') }}">{{ ucfirst($u->role) }}</span></td>
        <td class="p-3">{{ $orderCounts[$u->id] ?? 0 }}</td>
        <td class="p-3 whitespace-nowrap">{{ $u->created_at ? $u->created_at->format('M j, Y') : '' }}</td>
        <td class="p-3">
          <div class="flex gap-2">
            <a href="{{ route('owner.users.edit', $u) }}" class="rounded-lg bg-ink text-white px-3 py-2">Edit</a>
            @if($u->id !== auth()->id())
              <form method="POST" action="{{ route('owner.users.destroy', $u) }}" onsubmit="return confirm('Delete the account of {{ addslashes($u->name) }}?')">@csrf @method('DELETE')
                <button class="rounded-lg bg-red-600 text-white px-3 py-2">Delete</button>
              </form>
            @endif
          </div>
        </td>
      </tr>
    @empty
      <tr><td colspan="6" class="p-4 text-ink/60">No accounts found.</td></tr>
    @endforelse
    </tbody>
  </table>
</div>
@endsection
