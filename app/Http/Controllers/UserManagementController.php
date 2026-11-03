<?php

namespace App\Http\Controllers;

use App\Models\{Order, User};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    const ROLES = ['customer', 'staff', 'admin'];

    public function index()
    {
        $q = User::orderByRaw("FIELD(role, 'admin', 'staff', 'customer')")->orderBy('name');
        if ($s = request('q')) {
            $q->where(fn ($w) => $w->where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%"));
        }

        return view('owner.users', [
            'users' => $q->get(),
            'orderCounts' => Order::selectRaw('user_id, COUNT(*) as n')->groupBy('user_id')->pluck('n', 'user_id'),
        ]);
    }

    public function create()
    {
        return view('owner.user-form', ['user' => new User(), 'roles' => self::ROLES]);
    }

    public function store(Request $r)
    {
        $d = $r->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in(self::ROLES)],
        ]);

        $u = new User(['name' => $d['name'], 'email' => $d['email'], 'password' => $d['password']]);
        $u->role = $d['role'];
        $u->save();

        return redirect()->route('owner.users')->with('ok', "{$u->name} was added as {$d['role']}.");
    }

    public function edit(User $user)
    {
        return view('owner.user-form', ['user' => $user, 'roles' => self::ROLES]);
    }

    public function update(Request $r, User $user)
    {
        $d = $r->validate([
            'name' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'role' => ['required', Rule::in(self::ROLES)],
        ]);

        if ($user->id === auth()->id() && $d['role'] !== 'admin') {
            return back()->withErrors('You cannot remove your own administrator role.')->withInput();
        }
        if ($user->role === 'admin' && $d['role'] !== 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->withErrors('At least one administrator account must remain.')->withInput();
        }

        $user->name = $d['name'];
        $user->email = $d['email'];
        $user->role = $d['role'];
        if (! empty($d['password'])) {
            $user->password = $d['password'];
        }
        $user->save();

        return redirect()->route('owner.users')->with('ok', "{$user->name} was updated.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors('You cannot delete your own account.');
        }
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->withErrors('At least one administrator account must remain.');
        }
        if (Order::where('user_id', $user->id)->exists()) {
            return back()->withErrors("{$user->name} has placed orders, so the account cannot be deleted. You can change the role instead.");
        }

        $user->delete();

        return redirect()->route('owner.users')->with('ok', 'Account deleted.');
    }
}
