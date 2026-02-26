<?php

namespace App\Console\Commands;

use App\Models\Feed;
use Illuminate\Console\Command;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\select;
use function Laravel\Prompts\table;
use function Laravel\Prompts\text;
use function Laravel\Prompts\warning;

class ManageFeeds extends Command
{
    protected $signature = 'rss:feeds';
    protected $description = 'Interactively manage RSS feeds';

    public function handle(): int
    {
        while (true) {
            $action = select('What would you like to do?', [
                'list' => 'List feeds',
                'add' => 'Add feed',
                'remove' => 'Remove feed',
                'toggle' => 'Toggle feed active/inactive',
                'exit' => 'Exit',
            ]);

            match ($action) {
                'list' => $this->listFeeds(),
                'add' => $this->addFeed(),
                'remove' => $this->removeFeed(),
                'toggle' => $this->toggleFeed(),
                'exit' => null,
            };

            if ($action === 'exit') {
                break;
            }
        }

        return self::SUCCESS;
    }

    private function listFeeds(): void
    {
        $feeds = Feed::withCount('articles')->get();

        if ($feeds->isEmpty()) {
            warning('No feeds found.');
            return;
        }

        table(
            ['ID', 'Name', 'URL', 'Active', 'Articles', 'Last Fetched'],
            $feeds->map(fn (Feed $feed) => [
                $feed->id,
                $feed->name,
                $feed->url,
                $feed->is_active ? 'Yes' : 'No',
                $feed->articles_count,
                $feed->last_fetched_at?->diffForHumans() ?? 'Never',
            ])->toArray(),
        );
    }

    private function addFeed(): void
    {
        $name = text('Feed name:', required: true);
        $url = text('Feed URL:', required: true, validate: fn (string $value) =>
            filter_var($value, FILTER_VALIDATE_URL) ? null : 'Please enter a valid URL.'
        );

        Feed::create(['name' => $name, 'url' => $url]);

        info("Feed \"{$name}\" added.");
    }

    private function removeFeed(): void
    {
        $feeds = Feed::all();

        if ($feeds->isEmpty()) {
            warning('No feeds to remove.');
            return;
        }

        $feedId = select(
            'Which feed to remove?',
            $feeds->pluck('name', 'id')->toArray(),
        );

        $feed = Feed::findOrFail($feedId);

        if (confirm("Remove \"{$feed->name}\" and all its articles?", default: false)) {
            $feed->articles()->delete();
            $feed->delete();
            info("Feed \"{$feed->name}\" removed.");
        } else {
            info('Cancelled.');
        }
    }

    private function toggleFeed(): void
    {
        $feeds = Feed::all();

        if ($feeds->isEmpty()) {
            warning('No feeds to toggle.');
            return;
        }

        $feedId = select(
            'Which feed to toggle?',
            $feeds->mapWithKeys(fn (Feed $feed) => [
                $feed->id => $feed->name . ' (' . ($feed->is_active ? 'active' : 'inactive') . ')',
            ])->toArray(),
        );

        $feed = Feed::findOrFail($feedId);
        $feed->update(['is_active' => !$feed->is_active]);

        $status = $feed->is_active ? 'active' : 'inactive';
        info("Feed \"{$feed->name}\" is now {$status}.");
    }
}
