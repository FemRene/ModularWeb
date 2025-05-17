@extends('dashboard')

@section('content')
    <div class="container mx-auto p-4 max-w-lg">
        <h1 class="text-2xl mb-4">Edit Role</h1>

        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('modules.rolemanager.roles.update', $role) }}" method="POST">
            @csrf
            @method('PUT')

            <label for="name" class="block mb-1 font-semibold">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" required
                   class="w-full rounded border px-3 py-2 mb-4 bg-gray-800">

            <label for="description" class="block mb-1 font-semibold">Description</label>
            <input type="text" name="description" id="description" value="{{ old('description', $role->description) }}"
                   class="w-full rounded border px-3 py-2 mb-4 bg-gray-800">

            <label class="block mb-1 font-semibold">Permissions</label>
            <div class="mb-4 space-y-2">
                @foreach ($allPermissions as $permission)
                    <div class="flex items-center">
                        <input type="checkbox"
                               name="permissions[]"
                               value="{{ $permission->name }}"
                               id="perm_{{ $permission->id }}"
                               class="mr-2"
                            {{ in_array($permission->name, $role->permissions->pluck('name')->toArray()) ? 'checked' : '' }}>
                        <label for="perm_{{ $permission->id }}">
                            {{ $permission->description ?? $permission->name }}
                        </label>
                    </div>
                @endforeach
            </div>

            <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                Update Role
            </button>
        </form>
    </div>
@endsection
