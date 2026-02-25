<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'collection_id',
        'name',
        'slug',
        'reference',
        'price',
        'material',
        'movement',
        'complications',
        'images',
        'case_diameter',
        'case_thickness',
        'stock',
        'description',
        'sku',
        'is_visible',
        'is_swiss_made',
        'warranty_years',
    ];

    protected $casts = [
        'complications' => 'array',
        'images' => 'array',
        'price' => 'decimal:2',
        'is_visible' => 'boolean',
        'is_swiss_made' => 'boolean',
        'warranty_years' => 'integer',
    ];

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    public function options(): BelongsToMany
    {
        return $this->belongsToMany(Option::class)
            ->withTimestamps()
            ->withPivot('selected_values');
    }
}
