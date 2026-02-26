<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    protected $fillable = ['feed_id', 'guid', 'title', 'url', 'sent_to_instapaper_at', 'published_at'];

    protected function casts(): array
    {
        return [
            'sent_to_instapaper_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function feed(): BelongsTo
    {
        return $this->belongsTo(Feed::class);
    }

    public function scopeUnsent($query)
    {
        return $query->whereNull('sent_to_instapaper_at');
    }

    public function scopeSent($query)
    {
        return $query->whereNotNull('sent_to_instapaper_at');
    }
}
