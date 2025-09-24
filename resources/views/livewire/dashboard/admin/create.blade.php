<div wire:ignore.self class="modal fade" id="createAdminModal" tabindex="-1" aria-labelledby="createAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header text-white">
                <h5 class="modal-title" id="createAdminModalLabel">ადმინისტრატორის დამატება</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-1 col-6">
                            <label class="form-label" for="name">სახელი</label>
                            <input type="text" id="name" wire:model="name" class="form-control @error('name') border-danger is-invalid @enderror" autocomplete="off">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-1 col-6">
                            <label class="form-label" for="lastname">გვარი</label>
                            <input type="text" id="lastname" wire:model="lastname" class="form-control @error('lastname') border-danger is-invalid @enderror">
                            @error('lastname') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-1 col-6">
                            <label class="form-label" for="email">ელ-ფოსტა</label>
                            <input type="email" id="email" wire:model="email" class="form-control @error('email') border-danger is-invalid @enderror" autocomplete="off">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-1 col-6">
                            <label class="form-label" for="phone">ტელეფონის ნომერი</label>
                            <input type="text" id="phone" wire:model="phone" class="form-control @error('phone') border-danger is-invalid @enderror" autocomplete="off">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-1 col-12">
                            <label class="form-label" for="role_id">წვდომის ჯგუფი</label>
                            <select class="form-select @error('role_id') border-danger is-invalid @enderror" id="role_id" wire:model="role_id">
                                <option></option>
                                @foreach($role_list as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" wire:loading.attr="disabled">შენახვა</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">დახურვა</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('page_scripts')
<script>
    document.addEventListener("livewire:initialized", () => {
        Livewire.on('admin-created', (event) => {
            toastr.success(event.message, "შეტყობინება", {
                closeButton: true,
                progressBar: true,
            });
            let modal = bootstrap.Modal.getInstance(document.getElementById('createAdminModal'));
            modal.hide();
        });
    });
</script>
@endsection
