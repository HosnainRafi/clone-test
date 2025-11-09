<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'slug',
        'name',
        'designation',
        'department',
        'profile_image',
        'email',
        'phone_number',
        'office_location',
        'website_url',
        'about_me',
        'research_interests',
        'education',
        'experience',
        'publications',
        'projects',
        'courses_taught',
        'awards',
        'social_links',
        'is_active',
        'sort_order',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'research_interests' => 'array',
        'education' => 'array',
        'experience' => 'array',
        'publications' => 'array',
        'projects' => 'array',
        'courses_taught' => 'array',
        'awards' => 'array',
        'social_links' => 'array',
    ];

    public function scopeForSite($query, $siteId)
    {
        return $query->where('site_id', $siteId);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($teacher) {
            if (empty($teacher->slug)) {
                $teacher->generateSlug();
            }
        });

        static::updating(function ($teacher) {
            if ($teacher->isDirty('name')) {
                $teacher->generateSlug();
            }
        });
    }

    public function generateSlug()
    {
        $this->slug = Str::slug($this->name);
        $originalSlug = $this->slug;
        $counter = 1;

        while (static::where('slug', $this->slug)->where('id', '!=', $this->id)->exists()) {
            $this->slug = $originalSlug . '-' . $counter++;
        }
    }
}
