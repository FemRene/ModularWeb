@extends('dashboard')

@section('content')
    <h1 class="text-3xl font-semibold mb-6">Create User</h1>

    @if ($errors->any())
        <div class="mb-6 rounded bg-red-50 border border-red-400 p-4 text-red-700">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('modules.usermanager.admin.users.store') }}" method="POST" class="space-y-6">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                   class="block w-full rounded-md border border-gray-300 px-4 py-2 text-gray-900
                          placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none focus:ring-1 transition"
                   placeholder="John Doe">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required
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
                    <option value="{{ $role->name }}" @if(old('role') === $role->name) selected @endif>
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" id="password" name="password" required
                   class="block w-full rounded-md border border-gray-300 px-4 py-2 text-gray-900
                          placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none focus:ring-1 transition"
                   placeholder="Enter password">
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required
                   class="block w-full rounded-md border border-gray-300 px-4 py-2 text-gray-900
                          placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none focus:ring-1 transition"
                   placeholder="Re-enter password">
        </div>

        <button type="submit"
                class="w-full bg-indigo-600 text-white font-semibold py-3 rounded-md shadow-sm
                       hover:bg-indigo-700 transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            Create User
        </button>
    </form>
@endsection
