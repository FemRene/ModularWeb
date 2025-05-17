<?php

namespace Modules\UserManager\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\RoleManager\Models\Role; // Import Role model

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('UserManager::admin.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();  // get roles dynamically
        return view('UserManager::admin.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $roles = Role::pluck('name')->toArray();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'role' => 'required|string|in:' . implode(',', $roles), // validate role from DB
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('modules.usermanager.admin.users.index')->with('success', 'User created.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();  // get roles dynamically
        return view('UserManager::admin.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $roles = Role::pluck('name')->toArray();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->id}",
            'role' => 'required|string|in:' . implode(',', $roles),
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('modules.usermanager.admin.users.index')->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('modules.usermanager.admin.users.index')->with('success', 'User deleted.');
    }
}
