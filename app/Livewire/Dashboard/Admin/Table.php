<?php

namespace App\Livewire\Dashboard\Admin;

use App\Models\Admin;

use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    public string $search_query = '';
    public string $order_dir = 'desc';
    public int $per_page = 10;
    public bool $withTrashed = false;
    public ?int $role_id = null;


    public array $selectedAdmins = [];
    public bool $selectAll = false;
    public array $currentPageIds = [];


    protected $queryString = [
        'search_query' => ['except' => ''],
        'order_dir'    => ['except' => 'desc'],
        'per_page'     => ['except' => 10],
        'withTrashed'  => ['except' => false],
        'role_id'  => ['except' => null],
    ];

    protected $listeners = [
        'deleteSelectedConfirmed',
        'restoreSelectedConfirmed',
        'delete',
        'restore',
        'admin-refresh' => '$refresh',
    ];

    public function deleteSelected()
    {
        $this->dispatch('swal:confirm-multiple', [
            'ids' => $this->selectedAdmins,
            'name' => 'ნამდვილად გინდა წაშლა?',
            'icon' => 'warning',
            'confirmButtonText' => 'დიახ, წაშალე!',
            'cancelButtonText'  => 'გაუქმება',
        ]);
        $this->dispatch('refresh-feather');
    }

    public function deleteSelectedConfirmed($ids)
    {
        Admin::whereIn('id', $ids)->delete();
        $this->selectedAdmins = [];
        $this->selectAll = false;
        $this->dispatch('toastr', ['message' => 'მონიშნული ჩანაწერები წაშლილია']);
    }

    public function restoreSelectedConfirmed($ids)
    {
        Admin::whereIn('id', $ids)->withTrashed()->restore();
        $this->selectedAdmins = [];
        $this->selectAll = false;
        $this->dispatch('toastr', ['message' => 'მონიშნული ჩანაწერები წაშლილია']);
    }

    public function restoreSelected()
    {
        $this->dispatch('swal:restore-multiple', [
            'ids' => $this->selectedAdmins,
            'name' => 'ნამდვილად გინდა წაშლა?',
            'icon' => 'warning',
            'confirmButtonText' => 'დიახ, წაშალე!',
            'cancelButtonText'  => 'გაუქმება',
        ]);
        $this->dispatch('refresh-feather');
    }

    public function render()
    {
        $admins = Admin::query()->when($this->search_query, function ($q) {
            $terms = explode(' ', $this->search_query);
            $q->where(function ($subQuery) use ($terms) {
                foreach ($terms as $term) {
                    $subQuery->where(function ($inner) use ($term) {
                        $inner->where('name', 'like', '%' . $term . '%')
                            ->orWhere('lastname', 'like', '%' . $term . '%')
                            ->orWhere('email', 'like', '%' . $term . '%');
                    });
                }
            });
        })
            ->when($this->role_id, function ($q) {
                $q->where('role_id', $this->role_id);
            })
            ->when($this->withTrashed, fn($q) => $q->withTrashed())
            ->orderBy('id', $this->order_dir)
            ->paginate($this->per_page);

        $this->currentPageIds = $admins->where('role_id', '!=', 2)->pluck('id')->map(fn($id) => (string) $id)->toArray();

        $roles = Role::where('active', 1)->get();
        return view('livewire.dashboard.admin.table', compact('admins', 'roles'));
    }

    public function applyFilters()
    {
        $this->resetPage();
        $this->dispatch('filters-applied');
        $this->dispatch('closeModal');
    }

    public function updating($field)
    {
        if (in_array($field, ['search_query', 'order_dir', 'per_page', 'withTrashed'])) {
            $this->resetPage();
        }
    }

    public function resetFilters()
    {
        $this->search_query = '';
        $this->order_dir    = 'desc';
        $this->per_page     = 10;
        $this->withTrashed  = false;

        $this->resetPage();
        $this->dispatch('closeModal');
    }

    public function confirmDelete($id)
    {
        $this->dispatch('swal:confirm', [
            'id' => $id,
            'title' => 'ნამდვილად გინდა წაშლა?',
            'icon' => 'warning',
            'confirmButtonText' => 'დიახ, წაშალე!',
            'cancelButtonText'  => 'გაუქმება',
        ]);
        $this->dispatch('refresh-feather');
    }

    public function confirmRestore($id)
    {
        $this->dispatch('swal:restore', [
            'id' => $id,
            'title' => 'ნამდვილად გინდა აღდგენა?',
            'icon' => 'warning',
            'confirmButtonText' => 'დიახ, აღადგინე!',
            'cancelButtonText'  => 'გაუქმება',
        ]);
        $this->dispatch('refresh-feather');
    }

    public function delete($id)
    {
        Admin::findOrFail($id)->delete();

        $this->dispatch('refresh-feather');
        $this->dispatch('toastr-message', message: 'ადმინისტრატორი წარმატებით წაიშალა!');
    }

    public function restore($id)
    {
        Admin::withTrashed()->findOrFail($id)->restore();

        $this->dispatch('refresh-feather');
        $this->dispatch('toastr-message', message: 'ადმინისტრატორი წარმატებით აღდგენილია!');
    }

    public function toggleActive($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->active = !$admin->active;
        $admin->save();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedAdmins = $this->currentPageIds;
        } else {
            $this->selectedAdmins = [];
        }
    }

    public function updatedSelectedAdmins()
    {
        $this->selectAll = !empty($this->currentPageIds)
            && count($this->selectedAdmins) === count($this->currentPageIds);
    }

    public function updatingPage()
    {
        $this->selectAll = false;
        $this->selectedAdmins = [];
    }
}
