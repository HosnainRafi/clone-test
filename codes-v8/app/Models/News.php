<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'excerpt',
        'content',
        'image',
        'slug',
        'category',
        'link',
        'is_active',
        'is_featured',
        'views_count',
        'sort_order',
        'published_at',
        'expires_at',
        'meta_data',
        'site_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'meta_data' => 'array',
        'views_count' => 'integer',
        'sort_order' => 'integer',
    ];

    protected $dates = [
        'published_at',
        'expires_at',
    ];

    /**
     * Get the site this news item belongs to.
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Get the user who created this news item.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this news item.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope a query to only include active news items.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured news items.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include published news items.
     */
    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', now())
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    /**
     * Scope a query to only include news items by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to only include news items for a specific site.
     */
    public function scopeForSite($query, $siteId)
    {
        return $query->where('site_id', $siteId);
    }

    /**
     * Get the formatted date attribute.
     */
    protected function date(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->published_at?->format('Y-m-d') ?? $this->created_at->format('Y-m-d'),
        );
    }

    /**
     * Increment the views count.
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Generate a slug from the title.
     */
    public function generateSlug()
    {
        $this->slug = \Str::slug($this->title);
        
        // Ensure uniqueness
        $originalSlug = $this->slug;
        $counter = 1;
        
        while (static::where('slug', $this->slug)->where('id', '!=', $this->id)->exists()) {
            $this->slug = $originalSlug . '-' . $counter;
            $counter++;
        }
    }

    /**
     * Boot method to auto-generate slug.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($news) {
            if (empty($news->slug)) {
                $news->generateSlug();
            }
        });

        static::updating(function ($news) {
            if ($news->isDirty('title') && empty($news->slug)) {
                $news->generateSlug();
            }
        });
    }
}
