<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index()
    {
        $feeds = Feed::withCount('articles')
            ->orderBy('name')
            ->get();

        return view('feeds.index', compact('feeds'));
    }

    public function create()
    {
        return view('feeds.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:255',
        ]);

        Feed::create($validated);

        return redirect()->route('feeds.index')->with('success', 'Feed added.');
    }

    public function edit(Feed $feed)
    {
        return view('feeds.edit', compact('feed'));
    }

    public function update(Request $request, Feed $feed)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $feed->update($validated);

        return redirect()->route('feeds.index')->with('success', 'Feed updated.');
    }

    public function destroy(Feed $feed)
    {
        $feed->delete();

        return redirect()->route('feeds.index')->with('success', 'Feed deleted.');
    }

    public function toggle(Feed $feed)
    {
        $feed->update(['is_active' => !$feed->is_active]);

        return redirect()->back()->with('success', 'Feed toggled.');
    }
}
