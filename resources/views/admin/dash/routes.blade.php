@extends("dashboard")
@section("title")
    Routes List
@endsection

@section("content")
    <h1>All Routes</h1>

    <table class="table-auto border-collapse border border-gray-400 w-full">
        <thead>
        <tr>
            <th class="border border-gray-300 px-4 py-2">Name</th>
            <th class="border border-gray-300 px-4 py-2">URI</th>
            <th class="border border-gray-300 px-4 py-2">Methods</th>
            <th class="border border-gray-300 px-4 py-2">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($routes as $route)
            <tr>
                <td class="border border-gray-300 px-4 py-2">{{ $route['name'] }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $route['uri'] }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $route['methods'] }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $route['action'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
