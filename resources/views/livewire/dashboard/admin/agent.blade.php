<div class="modal fade" wire:ignore.self id="viewAdminModal" tabindex="-1" aria-labelledby="viewAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewAdminModalLabel">აგენტის დეტალები</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="divider divider-start">
                        <div class="divider-text">დეპარტამენტი</div>
                    </div>
                    <div class="col-12 mb-1">
                        <div class="row">
                            <div class="col-12 mb-1">
                                <div class="demo-inline-spacing">
                                    @foreach($departaments as $department)
                                        <div class="form-check mb-1">
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   id="departament_{{ $department->id }}"
                                                   value="{{ $department->id }}"
                                                   wire:model="selectedDepartaments">
                                            <label class="form-check-label" for="departament_{{ $department->id }}">
                                                {{ $department->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="divider divider-start">
                        <div class="divider-text">ქალაქები</div>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            @foreach($cities as $city)
                                <div class="col-12 mt-1">
                                    <div class="form-check">
                                        <input type="checkbox"
                                               class="form-check-input"
                                               id="city_{{ $city->id }}"
                                               value="{{ $city->id }}"
                                               wire:model.live="selectedCities">
                                        <label class="form-check-label" for="city_{{ $city->id }}">
                                            {{ json_decode($city->name)->ge }}
                                        </label>
                                    </div>
                                    @if(in_array($city->id, $selectedCities))
                                        <div class="ms-1">
                                            <div class="row">
                                                @foreach($city->districts as $district)
                                                    <div class="col-4">
                                                        <div class="form-check mt-1">
                                                            <input type="checkbox"
                                                                   class="form-check-input"
                                                                   id="district_{{ $district->id }}"
                                                                   value="{{ $district->id }}"
                                                                   wire:model.live="selectedDistricts">
                                                            <label class="form-check-label" for="district_{{ $district->id }}">
                                                                {{ json_decode($district->name)->ge }}
                                                            </label>
                                                        </div>

                                                        {{-- ქვეუბნები --}}
                                                        @if(in_array($district->id, $selectedDistricts))
                                                            <div class="ms-3">
                                                                @foreach($district->subdistricts as $subdistrict)
                                                                    <div class="form-check mt-1">
                                                                        <input type="checkbox"
                                                                               class="form-check-input"
                                                                               id="subdistrict-{{ $subdistrict->id }}"
                                                                               value="{{ $subdistrict->id }}"
                                                                               wire:model="selectedSubdistricts">
                                                                        <label class="form-check-label" for="subdistrict-{{ $subdistrict->id }}">
                                                                            {{ json_decode($subdistrict->name)->ge }}
                                                                        </label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="divider divider-start mt-2">
                        <div class="divider-text">დამატებითი პარამეტრები</div>
                    </div>
                    <div class="col-4 mb-1">
                        <div class="mb-2">
                            <label for="ss_user_id" class="form-label">SS User ID</label>
                            <input type="text"
                                   id="ss_user_id"
                                   class="form-control"
                                   wire:model="ss_user_id"
                                   placeholder="შეიყვანე SS User ID">
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button"
                        class="btn btn-primary"
                        wire:click="saveAgentRegions"
                        data-bs-dismiss="modal">
                    შენახვა
                </button>
                <button type="button"
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                    დახურვა
                </button>
            </div>
        </div>
    </div>
</div>
