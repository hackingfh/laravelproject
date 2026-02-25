<?php

namespace App\Livewire\Admin;

use App\Models\Collection;
use App\Models\Product;
use App\Services\AdminLog;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    public $search = '';
    public $collection_id = '';
    public $stock_status = '';
    public $sort_field = 'created_at';
    public $sort_direction = 'desc';
    public $selected = [];

    protected $queryString = ['search', 'collection_id', 'stock_status', 'sort_field', 'sort_direction'];

    public function sortBy($field)
    {
        if ($this->sort_field === $field) {
            $this->sort_direction = $this->sort_direction === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort_field = $field;
            $this->sort_direction = 'asc';
        }
    }

    public function deleteSelected()
    {
        $count = count($this->selected);
        Product::whereIn('id', $this->selected)->delete();

        AdminLog::log('bulk_deleted', Product::class, null, ['ids' => $this->selected]);

        $this->selected = [];
        session()->flash('success', "{$count} produits supprimés.");
    }

    public function toggleVisibility($id)
    {
        $product = Product::findOrFail($id);
        $product->is_visible = !$product->is_visible;
        $product->save();

        AdminLog::log('updated', Product::class, $product->id, ['field' => 'is_visible', 'value' => $product->is_visible]);
    }

    public function render()
    {
        $query = Product::query()
            ->with(['collection', 'media'])
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('reference', 'like', '%' . $this->search . '%'))
            ->when($this->collection_id, fn($q) => $q->where('collection_id', $this->collection_id))
            ->when($this->stock_status === 'low', fn($q) => $q->where('stock', '<', 5))
            ->when($this->stock_status === 'out', fn($q) => $q->where('stock', '<=', 0))
            ->orderBy($this->sort_field, $this->sort_direction);

        return view('livewire.admin.product-list', [
            'products' => $query->paginate(15),
            'collections' => Collection::orderBy('name')->get(),
        ])->layout('layouts.admin');
    }
}
