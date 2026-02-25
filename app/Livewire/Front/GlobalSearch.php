<?php

namespace App\Livewire\Front;

use App\Models\Product;
use App\Models\Collection;
use Livewire\Component;

class GlobalSearch extends Component
{
    public $search = '';
    public $results = [];
    public $showResults = false;

    public function updatedSearch()
    {
        if (strlen($this->search) < 2) {
            $this->results = [];
            $this->showResults = false;
            return;
        }

        $products = Product::where('is_visible', true)
            ->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('reference', 'like', '%' . $this->search . '%');
            })
            ->with(['media', 'collection'])
            ->limit(5)
            ->get();

        $collections = Collection::where('is_published', true)
            ->where('name', 'like', '%' . $this->search . '%')
            ->limit(3)
            ->get();

        $this->results = [
            'products' => $products,
            'collections' => $collections,
        ];

        $this->showResults = true;
    }

    public function render()
    {
        return view('livewire.front.global-search');
    }
}
