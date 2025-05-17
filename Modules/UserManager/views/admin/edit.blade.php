@extends('dashboard')

@section('content')
    <h1 class="text-3xl font-semibold mb-6">Edit User</h1>

    @if ($errors->any())
        <div class="mb-6 rounded bg-red-50 border border-red-400 p-4 text-red-700">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('modules.usermanager.admin.users.update', $user->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                   class="block w-full rounded-md border border-gray-300 px-4 py-2 text-gray-900
                          placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none focus:ring-1 transition"
                   placeholder="John Doe">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                   class="block w-full rounded-md border border-gray-300 px-4 py-2 text-gray-900
                          placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none focus:ring-1 transition"
                   placeholder="john@example.com">
        </div>

        <div>
            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
            <select id="role" name="role" required
                    class="block w-full rounded-md border border-gray-300 px-4 py-2 text-gray-900
                           focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none focus:ring-1 transition">
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" @if(old('role', $user->role) === $role->name) selected @endif>
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                New Password <span class="text-gray-500 text-xs">(leave blank to keep current)</span>
            </label>
            <input type="password" id="password" name="password"
                   class="block w-full rounded-md border border-gray-300 px-4 py-2 text-gray-900
                          placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none focus:ring-1 transition"
                   placeholder="Enter new password">
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                   class="block w-full rounded-md border border-gray-300 px-4 py-2 text-gray-900
                          placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none focus:ring-1 transition"
                   placeholder="Re-enter new password">
        </div>

        <button type="submit"
                class="w-full bg-green-600 text-white font-semibold py-3 rounded-md shadow-sm
                       hover:bg-green-700 transition focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
            Update User
        </button>
    </form>
@endsection
