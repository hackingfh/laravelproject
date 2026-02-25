<?php

namespace App\Livewire\Admin;

use App\Models\Collection;
use App\Services\AdminLog;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class CatalogueManager extends Component
{
    use WithFileUploads;
    public $collections = [];
    public $name, $slug, $description, $parent_id;
    public $new_image;
    public $existing_image;
    public $editing_id = null;
    public $showForm = false;

    public function mount()
    {
        $this->loadCollections();
    }

    public function loadCollections()
    {
        $this->collections = Collection::with('children')
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->parent_id = null;
        $this->new_image = null;
        $this->existing_image = null;
        $this->editing_id = null;
        $this->showForm = false;
    }

    public function edit($id)
    {
        $collection = Collection::findOrFail($id);
        $this->editing_id = $id;
        $this->name = $collection->name;
        $this->slug = $collection->slug;
        $this->description = $collection->description;
        $this->parent_id = $collection->parent_id;
        $this->existing_image = $collection->image;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:collections,id',
            'new_image' => 'nullable|image|max:10240', // Max 10MB
        ]);

        $data = [
            'name' => $this->name,
            'slug' => $this->slug ?: Str::slug($this->name),
            'description' => $this->description,
            'parent_id' => $this->parent_id,
        ];

        if ($this->new_image) {
            $path = $this->new_image->store('collections', 'public');
            $data['image'] = '/storage/' . $path;
        }

        if ($this->editing_id) {
            $collection = Collection::findOrFail($this->editing_id);
            $collection->update($data);
            AdminLog::log('updated', Collection::class, $collection->id, $data);
            session()->flash('success', "Collection mise à jour.");
        } else {
            $collection = Collection::create($data);
            AdminLog::log('created', Collection::class, $collection->id, $data);
            session()->flash('success', "Collection créée.");
        }

        $this->resetForm();
        $this->loadCollections();
    }

    public function delete($id)
    {
        $collection = Collection::findOrFail($id);
        $collection->delete();
        AdminLog::log('deleted', Collection::class, $id);
        $this->loadCollections();
    }

    public function togglePublish($id)
    {
        $collection = Collection::findOrFail($id);
        $collection->is_published = !$collection->is_published;
        $collection->save();
        AdminLog::log('updated', Collection::class, $id, ['is_published' => $collection->is_published]);
        $this->loadCollections();
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.admin.catalogue-manager', [
            'allCollections' => Collection::where('id', '!=', $this->editing_id)->get(),
        ]);
    }
}
