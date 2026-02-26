<?php

namespace Database\Seeders;

use App\Models\Feed;
use Illuminate\Database\Seeder;

class FeedSeeder extends Seeder
{
    public function run(): void
    {
        $feeds = [
            ['name' => 'Hacker News', 'url' => 'https://hnrss.org/frontpage'],
            ['name' => 'Laravel News', 'url' => 'https://feed.laravel-news.com/'],
            ['name' => 'TechCrunch', 'url' => 'https://techcrunch.com/feed/'],
        ];

        foreach ($feeds as $feed) {
            Feed::firstOrCreate(['url' => $feed['url']], $feed);
        }
    }
}
