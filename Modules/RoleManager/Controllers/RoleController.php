<?php
namespace Modules\RoleManager\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\RoleManager\Models\Permission;
use Modules\RoleManager\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->paginate(10);
        return view('RoleManager::roles.index', compact('roles'));
    }

    public function create()
    {
        $allPermissions = Permission::all();
        return view('RoleManager::roles.create', compact('allPermissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'description' => 'nullable|string',
            'permissions' => 'nullable|string',
        ]);

        $permissions = array_filter(array_map('trim', explode(',', $request->input('permissions', ''))));

        Role::create([
            'name' => $request->name,
            'description' => $request->description,
            'permissions' => $permissions,
        ]);

        return redirect()->route('modules.rolemanager.roles.index')->with('success', 'Role created');
    }

    public function edit(Role $role)
    {
        $allPermissions = Permission::all();
        return view('RoleManager::roles.edit', compact('role', 'allPermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);

        $role->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        $permissionIds = Permission::whereIn('name', $validated['permissions'] ?? [])->pluck('id')->toArray();
        $role->permissions()->sync($permissionIds);

        return redirect()->route('modules.rolemanager.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('modules.rolemanager.roles.index')->with('success', 'Role deleted');
    }
}
