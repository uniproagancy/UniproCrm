<div class="content-body">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">ადმინისტრატორების ჩამონათვალი</h4>
            <div>
                @if(!empty($selectedAdmins))
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
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
                <button type="button" class="btn btn-icon btn-success mx-50" data-bs-toggle="modal" data-bs-target="#createAdminModal">
                    <i data-feather="user-plus"></i>
                </button>
                <button type="button" class="btn btn-icon btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterAdminModal">
                    <i data-feather="search"></i>
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr class="text-center">
                        <th>
                            <input type="checkbox" wire:model.live="selectAll" id="select-all">
                            <label for="select-all"></label>
                        </th>
                        <th>ID</th>
                        <th>სახელი გვარი</th>
                        <th>წვდომის ჯგუფი</th>
                        <th>ელ-ფოსტა</th>
                        <th>ტელეფონის ნომერი</th>
                        <th>სტატუის</th>
                        <th>მოქმედება</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $admin)
                    <tr class="text-center">
                        <td>
                            @if($admin->role_id != 2)
                            <input type="checkbox" wire:model.live="selectedAdmins" value="{{ $admin->id }}">
                            @endif
                        </td>
                        <td>{{ $admin->id }}</td>
                        <td>{{ $admin->name }} {{ $admin->lastname }}</td>
                        <td><span class="badge badge-light-success">{{ $admin->role->name }}</span></td>
                        <td>{{ $admin->email }}</td>
                        <td>{{ $admin->phone }}</td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <div class="form-check form-switch form-check-success">
                                    <input type="checkbox" class="form-check-input" id="admin_active_{{ $admin->id }}" wire:click="toggleActive({{ $admin->id }})" @checked($admin->active) />
                                    <label class="form-check-label" for="admin_active_{{ $admin->id }}">
                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                    </label>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('dashboard.admin.view', $admin->id) }}" class="text-body">
                                <i data-feather="user"></i>
                            </a>
                            @if($admin->role_id !== 2)
                                @if($admin->trashed())
                                <a href="#" class="text-body" wire:click="confirmRestore({{ $admin->id }})">
                                    <i class="text-success" data-feather="rotate-ccw"></i>
                                </a>
                                @else
                                <a href="#" class="text-body" wire:click="confirmDelete({{ $admin->id }})">
                                    <i class="text-danger" data-feather="trash"></i>
                                </a>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{$admins->links('livewire.dashboard.partials.pagination')}}
    @include('livewire.dashboard.admin.filter')
</div>
@section('page_scripts')
<script>
    document.addEventListener('livewire:initialized', () => {

        Livewire.on('swal:confirm', data => {
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

        Livewire.on('swal:restore', data => {
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

        window.addEventListener('swal:confirm-multiple', data => {
            Swal.fire({
                title: data.detail[0].title,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'დიახ',
                cancelButtonText: 'გაუქმება'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('deleteSelectedConfirmed', { ids: data.detail[0].ids });
                }
            });
        });

        window.addEventListener('swal:restore-multiple', data => {
            Swal.fire({
                title: data.detail[0].title,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'დიახ',
                cancelButtonText: 'გაუქმება'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('restoreSelectedConfirmed', { ids: data.detail[0].ids });
                }
            });
        });

        Livewire.on('closeModal', () => {
            const modalEl = document.getElementById('filterAdminModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if(modal) {
                modal.hide();
            }
        });

    });
    Livewire.on('toastr-message', (event) => {
        toastr.success(event.message, "შეტყობინება", {
            closeButton: true,
            progressBar: true,
        });
    });
</script>
@endsection
