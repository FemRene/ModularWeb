@extends("dashboard")
@section("title")
    Modules List
@endsection

@section("content")
    <h1 class="text-2xl font-bold mb-4">Modules</h1>
    <form method="POST" action="{{ route('modules.upload') }}" enctype="multipart/form-data">
        @csrf
        <input type="file" name="module_zip" required class="border p-2 rounded" />
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Modul hochladen</button>
    </form>

    @if (count($modules))
        <ul class="space-y-4">
            @foreach ($modules as $module)
                <li class="p-4 border rounded shadow">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-semibold">
                                {{ $module['name'] ?? 'Unnamed Module' }}
                                @if (!($module['enabled'] ?? true))
                                    <span class="text-sm text-red-500">(Deaktiviert)</span>
                                @endif
                            </h2>
                            <p><strong>Version:</strong> {{ $module['version'] ?? 'N/A' }}</p>
                            <p><strong>Author:</strong> {{ $module['author'] ?? 'Unknown' }}</p>
                            <p><strong>Description:</strong> {{ $module['description'] ?? '-' }}</p>
                        </div>

                        <div class="flex gap-2">
                            {{-- Aktivieren / Deaktivieren --}}
                            <form method="POST" action="{{ route('modules.' . (($module['enabled'] ?? true) ? 'deactivate' : 'activate'), $module['name']) }}">
                                @csrf
                                <button type="submit" class="px-3 py-1 rounded bg-blue-500 text-white hover:bg-blue-600">
                                    {{ ($module['enabled'] ?? true) ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>

                            {{-- Löschen --}}
                            <form method="POST" action="{{ route('modules.delete', $module['name']) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1 rounded bg-red-500 text-white hover:bg-red-600"
                                        onclick="return confirm('Delete module {{ $module['name'] }}?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <p>No modules found.</p>
    @endif
@endsection
