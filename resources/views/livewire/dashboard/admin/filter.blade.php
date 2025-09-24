<div class="modal modal-slide-in new-user-modal fade" wire:ignore.self id="filterAdminModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content pt-0" wire:submit.prevent="applyFilters" wire:keydown.enter="applyFilters">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h5 class="modal-title">ფილტრი</h5>
            </div>
            <div class="modal-body flex-grow-1">
                <div class="mb-1">
                    <label class="form-label">საძიებო სიტყვა</label>
                    <input type="text" class="form-control" placeholder="სახელი, გვარი, ელ-ფოსტა, ტელეფონის ნომერი"
                           wire:model.debounce.500ms="search_query" />
                </div>
                <div class="mb-1">
                    <label class="form-label">თარიღის სორტირება</label>
                    <select class="form-select" wire:model="order_dir">
                        <option value="desc">ახალ დამატებული</option>
                        <option value="asc">ძველ დამატებული</option>
                    </select>
                </div>
                <div class="mb-1">
                    <label class="form-label">წვდომის ჯგუფები</label>
                    <select class="form-select" wire:model="role_id">
                        <option value="">ყველა ჯგუფი</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-1">
                    <label class="form-label">ჩვენება</label>
                    <select class="form-select" wire:model="per_page">
                        <option value="10">10 ჩანაწერი</option>
                        <option value="25">25 ჩანაწერი</option>
                        <option value="50">50 ჩანაწერი</option>
                        <option value="100">100 ჩანაწერი</option>
                    </select>
                </div>
                <div class="mb-1 form-check form-check-primary">
                    <input type="checkbox" class="form-check-input" id="withTrashed" wire:model="withTrashed">
                    <label class="form-check-label" for="withTrashed">წაშლილი ჩანაწერების ჩვენება</label>
                </div>
                <div class="d-flex justify-content-end mt-2">
                    <button type="submit" class="btn btn-primary me-1">გაფილტრე</button>
                    <button type="button" class="btn btn-outline-secondary" wire:click="resetFilters">გასუფთავება</button>
                </div>
            </div>
        </form>
    </div>
</div>
