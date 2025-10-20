<div class="content-body">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">შეკვეთების ჩამონათვალი</h4>
            <div>
                <button type="button" class="btn btn-icon btn-outline-warning">
                    <i data-feather="download"></i>
                </button>
                @if(!empty($selectedOrders))
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-info dropdown-toggle mx-50" data-bs-toggle="dropdown" aria-expanded="false">
                        <i data-feather="list"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item text-danger" style="font-size: 12px" href="#" wire:click="deleteSelected">
                            <i data-feather="trash"></i> მონიშნულის წაშლა
                        </a>
                        <a class="dropdown-item text-success" style="font-size: 12px" href="#" wire:click="restoreSelected">
                            <i data-feather="rotate-ccw"></i> მონიშნულის აღდგენა
                        </a>
                    </div>
                </div>
                @endif
                <button type="button" class="btn btn-icon btn-success" id="openOrderBtn">
                    <i data-feather="plus-square"></i>
                </button>
                <button type="button" class="btn btn-icon btn-outline-primary mx-50" data-bs-toggle="modal" data-bs-target="#filterOrderModal">
                    <i data-feather="search"></i>
                </button>
            </div>
        </div>
        @if(count($orders) > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr class="text-center">
                    <th>
                        <input type="checkbox" wire:model.live="selectAll" id="select-all">
                        <label for="select-all"></label>
                    </th>
                    <th>ID</th>
                    <th>აგენტი</th>
                    <th>ტიპი</th>
                    <th>ღირებულება</th>
                    <th>ლოკაცია</th>
                    <th>სტატუსი</th>
                    <th>მოქმედება</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr class="text-center">
                            <td>
                                <input type="checkbox" wire:model.live="selectedOrders" value="{{ $order->id }}">
                            </td>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->agent->name }} {{ $order->agent->lastname }}</td>
                            <td>{{ $order->type->name }}</td>
                            <td>{{ $order->amount / 100 }} {{ $order->currency->value }}</td>
                            <td>{{ json_decode($order->city->name)->ge }} @if(!empty($order->district)) / {{ json_decode($order->district->name)->ge }} @endif @if(!empty($order->subdistrict)) / {{ json_decode($order->subdistrict->name)->ge }} @endif</td>
                            <td>
                                <span class="badge badge-light-{{ $order->status->color }}">{{ $order->status->name }}</span>
                            </td>
                            <td>
                                @if($order->trashed())
                                    <a href="#" class="text-body" wire:click="confirmRestore({{ $order->id }})">
                                        <i class="text-success" data-feather="rotate-ccw"></i>
                                    </a>
                                @else
                                    <a href="#" class="text-body" wire:click="loadOrder({{ $order->id }})">
                                        <i class="text-warning" data-feather="external-link"></i>
                                    </a>
                                    <a href="#" class="text-body" wire:click="editOrder({{ $order->id }})">
                                        <i class="text-white" data-feather="edit"></i>
                                    </a>
                                    <a href="#" class="text-body" wire:click="confirmDelete({{ $order->id }})">
                                        <i class="text-danger" data-feather="trash"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="px-2">
            <div class="alert alert-warning" role="alert">
                <div class="alert-body d-flex align-items-center">
                    <i data-feather="alert-circle" class="me-50"></i>
                    <span> შეკვეთები ვერ მოიძებნა!</span>
                </div>
            </div>
        </div>
        @endif
    </div>
    {{$orders->links('livewire.dashboard.partials.pagination')}}
    @include('livewire.dashboard.order.filter')
    @include('livewire.dashboard.order.order')
</div>
@section('page_scripts')
    <script>
        document.getElementById('openOrderBtn').addEventListener('click', function () {
            Livewire.dispatch('openOrderModal');
            let modal = new bootstrap.Modal(document.getElementById('createOrderModal'));
            modal.show();
        });

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('openViewOrderModal', () => {
                const modal = new bootstrap.Modal(document.getElementById('viewOrderModal'))
                modal.show();
            });

            Livewire.on('order-created', (data) => {
                toastr.success(data.message, "შეტყობინება", {
                    closeButton: true,
                    progressBar: true,
                });
                let modal = bootstrap.Modal.getInstance(document.getElementById('createOrderModal'));
                modal.hide();
            });

            Livewire.on('closeModal', () => {
                const modalEl = document.getElementById('filterOrderModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                if(modal) {
                    modal.hide();
                }
            });

            window.addEventListener('swal:delete-multiple', data => {
                Swal.fire({
                    title: data.detail[0].title,
                    icon: data.detail[0].icon,
                    showCancelButton: true,
                    confirmButtonText: 'დიახ',
                    cancelButtonText: 'გაუქმება',
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('deleteSelectedConfirmed', { ids: data.detail[0].ids });
                    }
                });
            });

            window.addEventListener('swal:restore-multiple', data => {
                Swal.fire({
                    title: 'ნამდვილად გსურთ მონიშნულების აღდგენა?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'დიახ',
                    cancelButtonText: 'გაუქმება',
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('restoreSelectedConfirmed', { ids: data.detail[0].ids });
                    }
                });
            });

            Livewire.on('swal:deleteConfirm', data => {
                Swal.fire({
                    title: data[0].title,
                    icon: data[0].icon,
                    showCancelButton: true,
                    confirmButtonText: data[0].confirmButtonText,
                    cancelButtonText: data[0].cancelButtonText,
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('delete', { id: data[0].id });
                    }
                });
            });

            Livewire.on('swal:restoreConfirm', data => {
                Swal.fire({
                    title: data[0].title,
                    icon: data[0].icon,
                    showCancelButton: true,
                    confirmButtonText: data[0].confirmButtonText,
                    cancelButtonText: data[0].cancelButtonText,
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('restore', { id: data[0].id });
                    }
                });
            });

            Livewire.on('show-edit-modal', () => {
                const modal = new bootstrap.Modal(document.getElementById('editOrderModal'));
                modal.show();
            });

            Livewire.on('hide-edit-modal', () => {
                const modalEl = document.getElementById('editOrderModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            });

            Livewire.on('order-success', (data) => {
                toastr.success(data.message, "შეტყობინება", {
                    closeButton: true,
                    progressBar: true,
                });
                bootstrap.Modal.getInstance(document.getElementById('viewOrderModal')).hide();
            });
        });
    </script>
@endsection
