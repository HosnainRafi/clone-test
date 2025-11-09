<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Publication extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'abstract',
        'content',
        'authors',
        'journal',
        'journal_rank',
        'impact_factor',
        'conference',
        'publication_type',
        'doi',
        'url',
        'publisher',
        'year',
        'volume',
        'issue',
        'pages',
        'citation',
        'citation_count',
        'download_count',
        'keywords',
        'attachments',
        'link',
        'image',
        'is_active',
        'is_open_access',
        'is_featured',
        'fallback_gradient',
        'sort_order',
        'published_at',
        'site_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_open_access' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'date',
        'keywords' => 'array',
        'attachments' => 'array',
        'sort_order' => 'integer',
        'impact_factor' => 'decimal:2',
        'citation_count' => 'integer',
        'download_count' => 'integer',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function scopeForSite($query, $siteId)
    {
        return $query->where('site_id', $siteId);
    }
}
