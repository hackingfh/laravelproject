<?php

namespace App\Observers;

use App\Models\Collection;
use Illuminate\Support\Str;

class CollectionObserver
{
    public function creating(Collection $collection): void
    {
        if (empty($collection->slug)) {
            $base = Str::slug($collection->name ?? Str::random(8));
            $slug = $base;
            $i = 1;
            while (Collection::withTrashed()->where('slug', $slug)->exists()) {
                $slug = $base.'-'.$i++;
            }
            $collection->slug = $slug;
        }
    }
}
