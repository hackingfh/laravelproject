<?php

namespace App\Livewire\Admin;

use App\Models\Collection;
use App\Models\Product;
use App\Services\AdminLog;
use App\Services\SyncService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductForm extends Component
{
    use WithFileUploads;

    public $product_id;
    public $product;
    public $name, $slug, $description, $price, $stock, $reference, $sku, $collection_id;
    public $is_visible = true;
    public $new_images = [];
    public $existing_media = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'collection_id' => 'nullable|exists:collections,id',
        'new_images.*' => 'nullable|image|max:15360', // Max 15MB per image
    ];

    protected $messages = [
        'new_images.*.image' => 'Le fichier doit être une image.',
        'new_images.*.max' => 'L\'image est trop lourde (max 15 Mo).',
        'new_images.*.uploaded' => 'Le fichier est trop volumineux pour le serveur.',
        'new_images.uploaded' => 'Erreur lors du transfert : le fichier dépasse la limite du serveur (2 Mo actuellement).',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $this->product_id = $id;
            $this->product = Product::with('media')->findOrFail($id);
            $this->name = $this->product->name;
            $this->slug = $this->product->slug;
            $this->description = $this->product->description;
            $this->price = $this->product->price;
            $this->stock = $this->product->stock;
            $this->reference = $this->product->reference;
            $this->sku = $this->product->sku;
            $this->collection_id = $this->product->collection_id;
            $this->is_visible = $this->product->is_visible;
            $this->existing_media = $this->product->media;
        } else {
            $this->product = new Product();
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => $this->slug ?: \Illuminate\Support\Str::slug($this->name),
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'reference' => $this->reference,
            'sku' => $this->sku,
            'collection_id' => $this->collection_id,
            'is_visible' => (bool) $this->is_visible,
        ];

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
                if ($this->product_id) {
                    $this->product->update($data);
                    AdminLog::log('updated', Product::class, $this->product->id, $data);
                } else {
                    $this->product = Product::create($data);
                    AdminLog::log('created', Product::class, $this->product->id, $data);
                }

                foreach ($this->new_images as $image) {
                    $path = $image->store('products', 'public');
                    $this->product->media()->create([
                        'url' => '/storage/' . $path,
                        'type' => 'image',
                    ]);
                }

                SyncService::touch();
            });

            $msg = $this->product_id ? "Produit mis à jour avec succès." : "Produit créé avec succès.";
            session()->flash('success', $msg);

            $this->new_images = [];
            $this->product->load('media');
            $this->existing_media = $this->product->media;

            return redirect()->route('admin.products');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Erreur upload produit: " . $e->getMessage());
            $this->addError('new_images', "Une erreur est survenue lors de l'enregistrement : " . $e->getMessage());
            return null;
        }
    }

    public function deleteMedia($mediaId)
    {
        $media = $this->product->media()->find($mediaId);
        if ($media) {
            $media->delete();
            $this->product->load('media'); // Refresh the relationship
            $this->existing_media = $this->product->media;
            AdminLog::log('deleted_media', Product::class, $this->product->id, ['id' => $mediaId]);
        }
    }

    public function removeNewImage($index)
    {
        unset($this->new_images[$index]);
        $this->new_images = array_values($this->new_images);
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.admin.product-form', [
            'collections' => Collection::all(),
        ]);
    }
}
