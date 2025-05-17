@extends('dashboard')

@section('title', 'User Management')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Users</h1>

    <a href="{{ route('modules.usermanager.admin.users.create') }}" class="mb-4 inline-block bg-blue-600 text-white px-4 py-2 rounded">Add User</a>

    @if(session('success'))
        <div class="mb-4 p-2 bg-green-200 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full border-collapse border border-gray-300">
        <thead>
        <tr>
            <th class="border border-gray-300 px-4 py-2">Name</th>
            <th class="border border-gray-300 px-4 py-2">Email</th>
            <th class="border border-gray-300 px-4 py-2">Role</th>
            <th class="border border-gray-300 px-4 py-2">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr>
                <td class="border border-gray-300 px-4 py-2">{{ $user->name }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $user->email }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ ucfirst($user->role) }}</td>
                <td class="border border-gray-300 px-4 py-2">
                    <a href="{{ route('modules.usermanager.admin.users.edit', $user) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form action="{{ route('modules.usermanager.admin.users.destroy', $user) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete this user?');">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 hover:underline ml-2">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
