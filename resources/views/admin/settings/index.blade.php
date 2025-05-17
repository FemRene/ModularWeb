@extends("dashboard")

@section("title")
    Modules List
@endsection

@section("content")
    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">
        Settings Provided from Plugins
    </h2>

    <hr class="border-gray-300 dark:border-gray-700 mb-6">

    <form method="POST" action="{{ route('admin.settings.save') }}">
        @csrf

        @php
            $settingsByModule = \App\Helpers\SettingsSpecLoader::getAllSettingsGroupedByModule();
        @endphp

        @foreach($settingsByModule as $moduleName => $settings)
            <h2 class="text-center text-lg font-semibold text-gray-800 dark:text-gray-200 mt-8 mb-4">
                {{ $moduleName }}
            </h2>

            @foreach($settings as $setting)
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-1" for="{{ $setting['key'] }}">
                        {{ ucfirst(str_replace(['-', '_'], ' ', $setting['key'])) }}:
                    </label>

                    @php
                        $type = $setting['type'] ?? 'text';
                        $value = \App\Http\Controllers\Admin\SettingsController::get($setting['key'], $setting['default']);
                    @endphp

                    @if($type === 'number')
                        <input
                            type="number"
                            id="{{ $setting['key'] }}"
                            name="{{ $setting['key'] }}"
                            value="{{ $value }}"
                            class="w-full px-3 py-2 border rounded dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                        >
                    @elseif($type === 'checkbox')
                        <!-- Hidden input to send 0 if checkbox is unchecked -->
                        <input type="hidden" name="{{ $setting['key'] }}" value="0">

                        <input
                            type="checkbox"
                            id="{{ $setting['key'] }}"
                            name="{{ $setting['key'] }}"
                            value="1"
                            @if($value) checked @endif
                            class="mr-2"
                        >
                    @else
                        <input
                            type="text"
                            id="{{ $setting['key'] }}"
                            name="{{ $setting['key'] }}"
                            value="{{ $value }}"
                            class="w-full px-3 py-2 border rounded dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                        >
                    @endif
                </div>
            @endforeach
        @endforeach

        <button
            type="submit"
            class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded"
        >
            Save
        </button>
    </form>
@endsection
