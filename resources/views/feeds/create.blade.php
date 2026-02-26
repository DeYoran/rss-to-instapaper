@extends('layouts.app')

@section('title', 'Add Feed')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Add Feed</h1>

    <form action="{{ route('feeds.store') }}" method="POST" class="bg-white rounded-lg border border-gray-200 p-6 max-w-lg space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="url" class="block text-sm font-medium text-gray-700 mb-1">URL</label>
            <input type="url" name="url" id="url" value="{{ old('url') }}" required
                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @error('url') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">Add Feed</button>
            <a href="{{ route('feeds.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
        </div>
    </form>
@endsection
