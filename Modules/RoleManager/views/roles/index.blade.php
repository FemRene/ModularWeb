@extends('dashboard')

@section('title', 'Roles Management')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Roles Management</h1>

    <a href="{{ route('modules.rolemanager.roles.create') }}" class="mb-4 inline-block bg-blue-600 text-white px-4 py-2 rounded">
        Create New Role
    </a>

    @if(session('success'))
        <div class="mb-4 p-2 bg-green-200 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full border-collapse border border-gray-300">
        <thead>
        <tr class="bg-gray-700">
            <th class="border border-gray-300 px-4 py-2 text-left">Name</th>
            <th class="border border-gray-300 px-4 py-2 text-left">Description</th>
            <th class="border border-gray-300 px-4 py-2 text-left">Permissions</th>
            <th class="border border-gray-300 px-4 py-2 text-left">Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($roles as $role)
            <tr>
                <td class="border border-gray-300 px-4 py-2">{{ $role->name }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $role->description ?? 'N/A' }}</td>
                <td class="border border-gray-300 px-4 py-2">
                    @if(count($role->all_permissions) > 0)
                        <ul class="list-disc list-inside space-y-1 text-sm">
                            @foreach($role->all_permissions as $permission)
                                <li>{{ $permission }}</li>
                            @endforeach
                        </ul>
                    @else
                        <span class="italic text-gray-500 text-sm">No permissions assigned</span>
                    @endif
                </td>
                <td class="border border-gray-300 px-4 py-2">
                    <a href="{{ route('modules.rolemanager.roles.edit', $role->id) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form action="{{ route('modules.rolemanager.roles.destroy', $role->id) }}"
                          method="POST"
                          style="display:inline-block"
                          onsubmit="return confirm('Are you sure you want to delete this role?');">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 hover:underline ml-2">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="border border-gray-300 px-4 py-2 text-center text-gray-500 italic">
                    No roles found.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    @if(method_exists($roles, 'hasPages') && $roles->hasPages())
        <div class="mt-4">
            {{ $roles->links() }}
        </div>
    @endif
@endsection
