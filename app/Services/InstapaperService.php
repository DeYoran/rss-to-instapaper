<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InstapaperService
{
    private string $email;
    private string $password;

    public function __construct()
    {
        $this->email = config('services.instapaper.email');
        $this->password = config('services.instapaper.password');
    }

    public function addUrl(string $url, ?string $title = null): bool
    {
        $params = ['url' => $url];

        if ($title !== null) {
            $params['title'] = $title;
        }

        try {
            $response = Http::withBasicAuth($this->email, $this->password)
                ->asForm()
                ->post('https://www.instapaper.com/api/add', $params);

            if ($response->successful()) {
                Log::info('Article sent to Instapaper', ['url' => $url]);
                return true;
            }

            Log::warning('Instapaper API returned non-success status', [
                'url' => $url,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Failed to send article to Instapaper', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
