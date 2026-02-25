<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityHistory extends Component
{
    use WithPagination;

    public $search = '';
    public $action = '';

    public function render()
    {
        $query = ActivityLog::with('user')
            ->when($this->search, function ($q) {
                $q->whereHas('user', fn($uq) => $uq->where('name', 'like', '%' . $this->search . '%'))
                    ->orWhere('model_type', 'like', '%' . $this->search . '%');
            })
            ->when($this->action, fn($q) => $q->where('action', $this->action))
            ->orderBy('created_at', 'desc');

        return view('livewire.admin.activity-history', [
            'logs' => $query->paginate(30),
        ])->layout('layouts.admin');
    }
}
