@extends('layouts.app')

@section('title', 'Feeds')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Feeds</h1>
        <a href="{{ route('feeds.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">Add Feed</a>
    </div>

    @if ($feeds->isEmpty())
        <p class="text-gray-500">No feeds yet. Add one to get started.</p>
    @else
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-gray-600">
                    <tr>
                        <th class="px-4 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">URL</th>
                        <th class="px-4 py-3 font-medium text-center">Articles</th>
                        <th class="px-4 py-3 font-medium">Last Fetched</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($feeds as $feed)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $feed->name }}</td>
                            <td class="px-4 py-3 text-gray-500 truncate max-w-48">{{ $feed->url }}</td>
                            <td class="px-4 py-3 text-center">{{ $feed->articles_count }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $feed->last_fetched_at?->diffForHumans() ?? 'Never' }}</td>
                            <td class="px-4 py-3">
                                <form action="{{ route('feeds.toggle', $feed) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center gap-1 text-xs rounded-full px-2 py-0.5 font-medium {{ $feed->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                        {{ $feed->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('feeds.edit', $feed) }}" class="text-blue-600 hover:text-blue-800 text-xs mr-3">Edit</a>
                                <form action="{{ route('feeds.destroy', $feed) }}" method="POST" class="inline" onsubmit="return confirm('Delete this feed and all its articles?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-xs">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
