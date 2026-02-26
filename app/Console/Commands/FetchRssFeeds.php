<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Feed;
use App\Services\InstapaperService;
use Illuminate\Console\Command;
use Vedmant\FeedReader\Facades\FeedReader;

class FetchRssFeeds extends Command
{
    protected $signature = 'rss:fetch';
    protected $description = 'Fetch RSS feeds and send new articles to Instapaper';

    public function handle(InstapaperService $instapaper): int
    {
        $feeds = Feed::active()->get();

        if ($feeds->isEmpty()) {
            $this->info('No active feeds found.');
            return self::SUCCESS;
        }

        $totalNew = 0;
        $totalSent = 0;
        $totalFailed = 0;

        foreach ($feeds as $feed) {
            $this->info("Fetching: {$feed->name} ({$feed->url})");

            try {
                $reader = FeedReader::read($feed->url);
                $items = $reader->get_items();
            } catch (\Exception $e) {
                $this->error("  Failed to fetch feed: {$e->getMessage()}");
                continue;
            }

            $feedNew = 0;
            $feedSent = 0;

            foreach ($items as $item) {
                $guid = $item->get_id() ?: $item->get_permalink();
                $url = $item->get_permalink();
                $title = $item->get_title() ?: 'Untitled';

                if (!$guid || !$url) {
                    continue;
                }

                $existing = Article::where('guid', $guid)->first();

                if ($existing && $existing->sent_to_instapaper_at) {
                    continue;
                }

                if ($existing) {
                    $article = $existing;
                } else {
                    $publishedAt = $item->get_date('Y-m-d H:i:s');

                    if ($publishedAt && now()->diffInHours($publishedAt) > 24) {
                        continue;
                    }

                    $article = Article::create([
                        'feed_id' => $feed->id,
                        'guid' => $guid,
                        'title' => $title,
                        'url' => $url,
                        'published_at' => $publishedAt,
                    ]);

                    $feedNew++;
                }

                if ($instapaper->addUrl($url, $title)) {
                    $article->update(['sent_to_instapaper_at' => now()]);
                    $feedSent++;
                } else {
                    $totalFailed++;
                    $this->warn("  Failed to send: {$title}");
                }
            }

            $feed->update(['last_fetched_at' => now()]);

            $this->info("  Found {$feedNew} new articles, sent {$feedSent} to Instapaper.");

            $totalNew += $feedNew;
            $totalSent += $feedSent;
        }

        $this->newLine();
        $this->info("Done. Total: {$totalNew} new, {$totalSent} sent, {$totalFailed} failed.");

        return self::SUCCESS;
    }
}
