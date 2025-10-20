<?php

namespace App\Livewire\Dashboard\Order;

use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{

    use WithPagination;

    public string $search_query = '';
    public string $order_dir = 'desc';
    public $filter_agent_id = null;
    public int $per_page = 10;
    public bool $withTrashed = false;

    public array $selectedOrders = [];
    public bool $selectAll = false;
    public array $currentPageIds = [];

    public ?Order $viewOrder = null;
    public ?int $selectedOrderId = null;

    protected $queryString = [
        'search_query' => ['except' => ''],
        'filter_agent_id' => ['except' => ''],
        'order_dir'    => ['except' => 'desc'],
        'per_page'     => ['except' => 10],
        'withTrashed'  => ['except' => false],
    ];

    protected $listeners = [
        'deleteSelectedConfirmed',
        'restoreSelectedConfirmed',
        'delete',
        'restore',
        'order-refresh' => '$refresh',
        'confirmDelete',
        'confirmRestore'
    ];

    public function editOrder($id)
    {
        $this->dispatch('openEditModal', $id);
    }

    public function loadOrder($id)
    {
        $order = Order::find($id);
        if(Auth::user()->id === $order->agent_id && $order->status_id === 1) {
            $order_log = OrderLog::create([
                'order_id' => $id,
                'type' => 'სტატუსის განახლება',
                'old_value' => json_encode($order, JSON_UNESCAPED_UNICODE),
                'created_by' => Auth::user()->id,
            ]);
            $order->update(['status_id' => 2]);
            $order_log->find($order_log->id)->update(['new_value' => json_encode($order, JSON_UNESCAPED_UNICODE),]);
        }
        $this->viewOrder = $order;
        $this->selectedOrderId = $id;
        $this->dispatch('openViewOrderModal');
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
        $order = Order::findOrFail($id);
        OrderLog::create([
            'order_id' => $order->id,
            'type' => 'შეკვეთის წაშლა',
            'created_by' => Auth::user()->id,
        ]);
        $order->delete();
        $this->dispatch('toastr-message', message: 'შეკვეთა წაიშალა!');
    }

    public function deleteSelected()
    {
        $this->dispatch('swal:delete-multiple', [
            'ids' => $this->selectedOrders,
            'title' => 'ნამდვილად გინდა წაშლა?',
            'icon' => 'warning',
            'confirmButtonText' => 'დიახ, წაშალე!',
            'cancelButtonText'  => 'გაუქმება',
        ]);
    }

    public function deleteSelectedConfirmed($ids)
    {
        $orders = Order::whereIn('id', $ids)->get();
        foreach($orders as $order) {
            OrderLog::create([
                'order_id' => $order->id,
                'type' => 'შეკვეთის წაშლა',
                'created_by' => Auth::user()->id,
            ]);
            $order->delete();
        }
        $this->selectedOrders = [];
        $this->selectAll = false;
        $this->dispatch('toastr-message', message: 'მონიშნული ჩანაწერები წაიშალა!');
    }

    public function restoreSelected()
    {
        $this->dispatch('swal:restore-multiple', [
            'ids' => $this->selectedOrders,
            'name' => 'ნამდვილად გინდა აღდგენა?',
            'icon' => 'warning',
            'confirmButtonText' => 'დიახ, აღადგინე!',
            'cancelButtonText'  => 'გაუქმება',
        ]);
    }

    public function restoreSelectedConfirmed($ids)
    {
        $orders = Order::whereIn('id', $ids)->withTrashed()->get();
        foreach($orders as $order) {
            OrderLog::create([
                'order_id' => $order->id,
                'type' => 'შეკვეთის აღდგენა',
                'created_by' => Auth::user()->id,
            ]);
            $order->restore();
        }
        $this->selectedOrders = [];
        $this->selectAll = false;

        $this->dispatch('toastr-message', message: 'მონიშნული ჩანაწერები აღდგა!');
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
        Order::withTrashed()->findOrFail($id)->restore();
        $this->dispatch('toastr-message', message: 'შეკვეთა აღდგენილია!');
    }

    public function updatedSelectAll($value)
    {
        $this->selectedOrders = $value ? $this->currentPageIds : [];
    }

    public function updatedSelectedOrders()
    {
        $this->selectAll = !empty($this->currentPageIds)
            && count($this->selectedOrders) === count($this->currentPageIds);
    }

    public function updatingPage()
    {
        $this->selectAll = false;
        $this->selectedOrders = [];
    }

    public function confirmOrder($id)
    {
        $order = Order::find($id);
        $order_log = OrderLog::create([
            'order_id' => $id,
            'type' => 'სტატუსის განახლება',
            'old_value' => json_encode($order, JSON_UNESCAPED_UNICODE),
            'created_by' => Auth::user()->id,
        ]);
        $order->update(['status_id' => 3]);
        $order_log->find($order_log->id)->update(['new_value' => json_encode($order, JSON_UNESCAPED_UNICODE),]);
        $this->dispatch('order-success', message: 'შეკვეთა დასრულებულია!');
        $this->dispatch('order-refresh');
    }

    public function render()
    {
        $orders = Order::query()
            ->when($this->search_query, function ($q) {
                $terms = explode(' ', $this->search_query);
                $q->where(function ($subQuery) use ($terms) {
                    foreach ($terms as $term) {
                        $subQuery->where(function ($inner) use ($term) {
                            $inner->where('id', 'like', "%{$term}%")
                                ->orWhere('custom_id', 'like', "%{$term}%")
                                ->orWhereHas('customer', function ($customerQuery) use ($term) {
                                    $customerQuery->where('phone', 'like', "%{$term}%");
                                });
                        });
                    }
                });
            })
            ->when($this->filter_agent_id, function ($q) {
                $q->where('agent_id', $this->filter_agent_id);
            })
            ->when($this->withTrashed, fn($q) => $q->withTrashed())
            ->orderBy('id', $this->order_dir)
            ->paginate($this->per_page);
        $agents = Admin::whereIn('role_id', [2,3])->where('active', 1)->get();
        $this->currentPageIds = $orders->pluck('id')->map(fn($id) => (string) $id)->toArray();
        return view('livewire.dashboard.order.table', compact('orders', 'agents'));
    }
}
