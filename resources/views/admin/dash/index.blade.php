@extends("dashboard")
@section("title")
    General Overview
@endsection

@section("content")
    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">
        Enabled Modules: {{ count(\App\Helpers\ModuleManager::listActiveModules()) }} / {{ count(\App\Helpers\ModuleManager::listAllModules()) }}
    </h2>

    <hr class="border-gray-300 dark:border-gray-700 mb-6">

@endsection
