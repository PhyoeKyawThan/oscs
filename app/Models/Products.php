<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Products extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category_id',
        'image',
        'images',
        'weight',
        'dimensions',
        'is_featured',
        'is_available'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'weight' => 'decimal:2',
        'images' => 'array',
        'is_featured' => 'boolean',
        'is_available' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }

    /**
     * Get the main image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }
        return asset('images/placeholder.jpg'); // Default placeholder
    }

    /**
     * Get gallery images with URLs
     */
    public function getGalleryImagesAttribute()
    {
        $gallery = [];
        if ($this->images && is_array(json_decode($this->images))) {
            foreach (json_decode($this->images) as $imagePath) {
                if (Storage::disk('public')->exists($imagePath)) {
                    $gallery[] = [
                        'path' => $imagePath,
                        'url' => asset('storage/' . $imagePath),
                        'filename' => basename($imagePath)
                    ];
                }
            }
        }
        
        return $gallery;
    }

    /**
     * Check if product has gallery images
     */
    public function hasGalleryImages()
    {
        return !empty($this->getGalleryImagesAttribute());
    }

    /**
     * Get the first gallery image URL (for thumbnails)
     */
    public function getFirstGalleryImageUrl()
    {
        $gallery = $this->getGalleryImagesAttribute();
        return !empty($gallery) ? $gallery[0]['url'] : $this->image_url;
    }
}