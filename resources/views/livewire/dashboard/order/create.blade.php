<div wire:ignore.self class="modal fade" id="createOrderModal" tabindex="-1" aria-labelledby="createOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header text-white">
                <h5 class="modal-title" id="createOrderModalLabel">შეკვეთის დამატება</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit.prevent="save">
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-2 col-md-6 col-sm-12">
                            <label for="agent_id" class="form-label">აგენტი</label>
                            <select wire:model.live="agent_id" id="agent_id" class="form-select @error('agent_id') border-danger is-invalid @enderror">
                                <option value="">აირჩიე აგენტი</option>
                                @foreach($agents as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->name }} {{ $agent->lastname }}</option>
                                @endforeach
                            </select>
                            @error('agent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-2 col-md-6 col-sm-12">
                            <label for="type_id" class="form-label">შეკვეთის ტიპი</label>
                            <select wire:model="type_id" id="type_id" class="form-select @error('type_id') border-danger is-invalid @enderror">
                                <option value="">აირჩიეთ შეკვეთის ტიპი</option>
                                @foreach($order_types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            @error('type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-2 col-md-4 col-sm-12">
                            <label for="city_id" class="form-label">ქალაქი</label>
                            <select wire:model.live="selectedCity" id="city_id" class="form-select @error('selectedCity') border-danger is-invalid @enderror" @if(empty($cities)) disabled @endif>
                                <option value="">აირჩიე ქალაქი</option>
                                @foreach($cities as $city)
                                <option value="{{ $city->id }}">{{ json_decode($city->name)->ge }}</option>
                                @endforeach
                            </select>
                            @error('selectedCity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-2 col-md-4 col-sm-12">
                            <label for="district_id" class="form-label">უბანი</label>
                            <select wire:model.live="selectedDistrict" id="district_id" class="form-select" @if(empty($districts)) disabled @endif>
                                <option value="">აირჩიე უბანი</option>
                                @foreach($districts as $district)
                                <option value="{{ $district->id }}">{{ json_decode($district->name)->ge }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2 col-md-4 col-sm-12">
                            <label for="subdistrict_id" class="form-label">ქვე უბანი</label>
                            <select wire:model.live="selectedSubdistrict" id="subdistrict_id" class="form-select" @if(empty($subdistricts)) disabled @endif>
                                <option value="">აირჩიე ქვე უბანი</option>
                                @foreach($subdistricts as $subdistrict)
                                <option value="{{ $subdistrict->id }}">{{ json_decode($subdistrict->name)->ge }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2 col-md-10 col-sm-12">
                            <label class="form-label">ღირებულება</label>
                            <input type="text" wire:model="amount" class="form-control @error('amount') border-danger is-invalid @enderror">
                            @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-2 col-md-2 col-sm-12">
                            <label class="form-label">ვალუტა</label>
                            <select wire:model="currency_id" class="form-select @error('currency_id') border-danger is-invalid @enderror">
                                <option value="">აირჩიეთ ვალუტა</option>
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2 col-md-4 col-sm-12">
                            <label class="form-label">მომხმარებლის სახელი</label>
                            <input type="text" wire:model="customer_name" class="form-control @error('customer_name') border-danger is-invalid @enderror">
                            @error('customer_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-2 col-md-4 col-sm-12">
                            <label class="form-label">მომხმარებლის გვარი</label>
                            <input type="text" wire:model="customer_lastname" class="form-control @error('customer_lastname') border-danger is-invalid @enderror">
                            @error('customer_lastname') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-2 col-md-4 col-sm-12">
                            <label class="form-label">ტელეფონის ნომერი</label>
                            <input type="text" wire:model="customer_phone" class="form-control @error('customer_phone') border-danger is-invalid @enderror">
                            @error('customer_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-2 col-md-6 col-sm-12">
                            <label class="form-label">განცხადების ID</label>
                            <input type="text" wire:model="custom_id" class="form-control">
                        </div>
                        <div class="mb-2 col-md-6 col-sm-12">
                            <label class="form-label">განცხადების ბმული</label>
                            <input type="text" wire:model="url" class="form-control">
                        </div>
                        <div class="mb-2 col-12">
                            <label class="form-label">ოპერატორის კომენტარი</label>
                            <textarea wire:model="comment" class="form-control"></textarea>
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
