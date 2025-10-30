<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Component extends Model
{
    use HasFactory, SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'content',
        'theme_id',
        'site_id',
        'page_id',
        'is_active',
        'is_homepage_component',
        'sort_order',
        'slug',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'content' => 'json',
        'is_active' => 'boolean',
        'is_homepage_component' => 'boolean',
    ];

    /**
     * Get the theme that owns the component.
     */
    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }

    /**
     * Get the site that owns the component.
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Get the page that owns the component.
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Get the sites that belong to this component.
     */
    public function sites(): BelongsToMany
    {
        return $this->belongsToMany(Site::class)
            ->withPivot(['position', 'settings', 'is_active'])
            ->withTimestamps();
    }

    /**
     * Get the user who created this component.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this component.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope a query to only include active components.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include homepage components.
     */
    public function scopeHomepage($query)
    {
        return $query->where('is_homepage_component', true);
    }

    /**
     * Scope a query to only include components for a specific site.
     */
    public function scopeForSite($query, $siteId)
    {
        return $query->where('site_id', $siteId);
    }

    /**
     * Scope a query to only include components for a specific page.
     */
    public function scopeForPage($query, $pageId)
    {
        return $query->where('page_id', $pageId);
    }

    /**
     * Scope a query to only include components of a specific type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Convert component name to snake_case type.
     */
    public static function nameToType($name)
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $name));
    }

    /**
     * Convert snake_case type to component name.
     */
    public static function typeToName($type)
    {
        return str_replace('_', '', ucwords($type, '_'));
    }
}
