<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // <-- THIS IS THE FIX. Import the class here.

class Tender extends Model
{
    use HasFactory;

    // ... your $fillable and $casts arrays ...
    protected $fillable = [
        'title',
        'tender_number',
        'slug',
        'description',
        'published_at',
        'closing_at',
        'attachments',
        'is_active',
        'sort_order',
        'site_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'attachments' => 'array',
        'published_at' => 'date',
        'closing_at' => 'date',
    ];


    // ... the rest of your model ...
    public function scopeForSite($query, $siteId)
    {
        return $query->where('site_id', $siteId);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tender) {
            if (empty($tender->slug)) {
                $tender->generateSlug();
            }
        });

        static::updating(function ($tender) {
            if ($tender->isDirty('title')) {
                $tender->generateSlug();
            }
        });
    }

    public function generateSlug()
    {
        // Now that you've imported the class, Laravel knows that `Str`
        // refers to `Illuminate\Support\Str` and the error will be gone.
        $this->slug = Str::slug($this->title);

        $originalSlug = $this->slug;
        $counter = 1;

        while (static::where('slug', $this->slug)->where('id', '!=', $this->id)->exists()) {
            $this->slug = $originalSlug . '-' . $counter++;
        }
    }
}
