<?php

namespace App\Livewire\Dashboard\Admin;

use App\Models\Admin;
use App\Models\Agent;
use App\Models\City;
use App\Models\Departament;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    // Filters & pagination
    public string $search_query = '';
    public string $order_dir = 'desc';
    public int $per_page = 10;
    public bool $withTrashed = false;
    public ?int $role_id = null;

    // Regions
    public $cities;
    public $selectedDepartaments = [];
    public $selectedCities = [];
    public $selectedDistricts = [];
    public $selectedSubdistricts = [];

    // Selection
    public array $selectedAdmins = [];
    public bool $selectAll = false;
    public array $currentPageIds = [];

    // View/Edit admin
    public ?Admin $viewAdmin = null;
    public ?int $selectedAdminId = null;
    public ?string $ss_user_id = null;

    protected $queryString = [
        'search_query' => ['except' => ''],
        'order_dir'    => ['except' => 'desc'],
        'per_page'     => ['except' => 10],
        'withTrashed'  => ['except' => false],
        'role_id'      => ['except' => null],
    ];

    protected $listeners = [
        'deleteSelectedConfirmed',
        'restoreSelectedConfirmed',
        'delete',
        'restore',
        'admin-refresh' => '$refresh',
        'confirmDelete',
        'confirmRestore'
    ];

    public function mount()
    {
        $this->cities = City::with('districts.subdistricts')->get();
    }

    // Load admin into modal
    public function loadAdmin($id)
    {
        $this->viewAdmin = Admin::withTrashed()->with('agent')->findOrFail($id);
        $this->selectedAdminId = $id;

        $this->selectedCities       = $this->viewAdmin->agent->cities ?? [];
        $this->selectedDepartaments = $this->viewAdmin->agent->departament ?? [];
        $this->selectedDistricts    = $this->viewAdmin->agent->districts ?? [];
        $this->selectedSubdistricts = $this->viewAdmin->agent->subdistricts ?? [];
        $this->ss_user_id           = $this->viewAdmin->agent->ss_user_id ?? ' ';

        $this->dispatch('openViewAgentModal');
    }

    // --- Bulk Delete ---
    public function deleteSelected()
    {
        $this->dispatch('swal:delete-multiple', [
            'ids' => $this->selectedAdmins,
            'name' => 'ნამდვილად გინდა წაშლა?',
            'icon' => 'warning',
            'confirmButtonText' => 'დიახ, წაშალე!',
            'cancelButtonText'  => 'გაუქმება',
        ]);
    }

    public function deleteSelectedConfirmed($ids)
    {
        Admin::whereIn('id', $ids)->update(['active' => 0]);
        Admin::whereIn('id', $ids)->delete();
        $this->selectedAdmins = [];
        $this->selectAll = false;
        $this->dispatch('toastr-message', message: 'მონიშნული ჩანაწერები წაიშალა!');
    }

    // --- Bulk Restore ---
    public function restoreSelected()
    {
        $this->dispatch('swal:restore-multiple', [
            'ids' => $this->selectedAdmins,
            'name' => 'ნამდვილად გინდა აღდგენა?',
            'icon' => 'warning',
            'confirmButtonText' => 'დიახ, აღადგინე!',
            'cancelButtonText'  => 'გაუქმება',
        ]);
    }

    public function restoreSelectedConfirmed($ids)
    {
        Admin::whereIn('id', $ids)->withTrashed()->restore();
        $this->selectedAdmins = [];
        $this->selectAll = false;

        $this->dispatch('toastr-message', message: 'მონიშნული ჩანაწერები აღდგა!');
    }

    // --- Delete / Restore single ---
    public function confirmDelete($id)
    {
        $this->dispatch('swal:deleteConfirm', [
            'id' => $id,
            'title' => 'ფაააააფუ?',
            'icon' => 'warning',
            'confirmButtonText' => 'კი, ფააააფუ!',
            'cancelButtonText' => 'დახურვა!',
            'type' => 'delete'
        ]);
    }

    public function delete($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->update(['active' => 0]);
        $admin->delete();
        $this->dispatch('toastr-message', message: 'ადმინისტრატორი წაიშალა!');
    }

    public function confirmRestore($id)
    {
        $this->dispatch('swal:restoreConfirm', [
            'id' => $id,
            'title' => 'ადმინისტრატორის აღდგენა?',
            'icon' => 'warning',
            'confirmButtonText' => 'აღდგენა!',
            'cancelButtonText' => 'დახურვა!',
            'type' => 'restore'
        ]);
    }

    public function restore($id)
    {
        Admin::withTrashed()->findOrFail($id)->restore();
        $this->dispatch('toastr-message', message: 'ადმინისტრატორი აღდგა!');
    }

    // --- Toggle Active ---
    public function toggleActive($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->active = !$admin->active;
        $admin->save();

        $status = $admin->active ? 'აქტიური' : 'გაუქმებული';
        $this->dispatch('toastr-message', message: "ადმინი {$status}!");
    }

    // --- Regions Save ---
    public function saveAgentRegions()
    {
        $agent = Agent::where('admin_id', $this->selectedAdminId)->first();

        $agent->departament  = $this->selectedDepartaments;
        $agent->cities       = $this->selectedCities;
        $agent->districts    = $this->selectedDistricts;
        $agent->subdistricts = $this->selectedSubdistricts;
        $agent->ss_user_id   = $this->ss_user_id;

        $agent->save();

        $this->dispatch('toastr-message', message: 'მონაცემები წარმატებით შენახულია!');
    }

    // --- Select all checkboxes ---
    public function updatedSelectAll($value)
    {
        $this->selectedAdmins = $value ? $this->currentPageIds : [];
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

    // -- Login As ---
    public function loginAs($id)
    {
        $admin = Admin::findOrFail($id);
        Auth::login($admin);
        $url = route('dashboard.index');
        $this->dispatch('open-new-tab', $url);
    }


    public function applyFilters()
    {
        $this->resetPage();
        $this->dispatch('filters-applied');
        $this->dispatch('closeModal');
    }

    public function resetFilters()
    {
        $this->filters = [
            'search_query' => '',
            'status' => '',
        ];
        $this->resetPage();
    }

    // --- Rendering ---
    public function render()
    {
        $admins = Admin::query()
            ->when($this->search_query, function ($q) {
                $terms = explode(' ', $this->search_query);
                $q->where(function ($subQuery) use ($terms) {
                    foreach ($terms as $term) {
                        $subQuery->where(function ($inner) use ($term) {
                            $inner->where('name', 'like', "%{$term}%")
                                ->orWhere('lastname', 'like', "%{$term}%")
                                ->orWhere('email', 'like', "%{$term}%");
                        });
                    }
                });
            })
            ->when($this->role_id, fn($q) => $q->where('role_id', $this->role_id))
            ->when($this->withTrashed, fn($q) => $q->withTrashed())
            ->orderBy('id', $this->order_dir)
            ->paginate($this->per_page);

        $this->currentPageIds = $admins->where('role_id', '!=', 2)
            ->pluck('id')->map(fn($id) => (string) $id)->toArray();

        $roles       = Role::where('active', 1)->get();
        $cities      = City::where('active', 1)->get();
        $departaments = Departament::where('active', 1)->get();

        return view('livewire.dashboard.admin.table', compact('admins', 'roles', 'cities', 'departaments'));
    }
}
