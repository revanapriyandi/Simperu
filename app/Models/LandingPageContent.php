<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;

class LandingPageContent extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'section',
        'title',
        'subtitle',
        'description',
        'content',
        'image',
        'button_text',
        'button_link',
        'is_active',
        'sort_order',
        'meta_data'
    ];

    protected $casts = [
        'content' => 'array',
        'meta_data' => 'array',
        'is_active' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeBySection($query, $section)
    {
        return $query->where('section', $section);
    }

    public static function getSection($section)
    {
        return self::bySection($section)->active()->first();
    }

    public static function getAllSections()
    {
        return self::active()->orderBy('sort_order')->get()->groupBy('section');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->singleFile();

        $this->addMediaCollection('hero_images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->hasMedia('images')
                ? $this->getFirstMediaUrl('images')
                : ($this->image ? asset('storage/' . $this->image) : null)
        );
    }

    public function getHeroImagesAttribute()
    {
        return $this->getMedia('hero_images')->map(function ($media) {
            return [
                'url' => $media->getUrl(),
                'alt' => $media->name,
                'title' => $media->getCustomProperty('title', $media->name)
            ];
        });
    }
}
