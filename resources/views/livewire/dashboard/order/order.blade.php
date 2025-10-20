<div class="modal fade" wire:ignore.self id="viewOrderModal" tabindex="-1" aria-labelledby="viewAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewAdminModalLabel">შეკვეთის დეტალები</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <section class="invoice-preview-wrapper">
                    <div class="row invoice-preview">
                        <div class="card invoice-preview-card">
                            @if($viewOrder)
                                <div class="card-body p-0">
                                    <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                                        <div>
                                            <p class="card-text mb-25 font-neue">შეკვეთის ნომერი: {{ $viewOrder->id }} <span class="font-helvetica badge badge-light-{{ $viewOrder->status->color }}">{{ $viewOrder->status->name }}</span></p>
                                            <p class="card-text mb-25 font-neue">აგენტი: {{ $viewOrder->agent->name }} {{ $viewOrder->agent->lastname }}</p>
                                        </div>
                                        <div>
                                            <p class="card-text mb-0 font-neue">შეკვეთა შექმნა: {{ $viewOrder->createdBy->name }} {{ $viewOrder->createdBy->lastname }}</p>
                                            <p class="card-text mb-25 font-neue">თარიღი: {{ \Carbon\Carbon::parse($viewOrder->created_at)->format('d-m-Y g:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <hr class="invoice-spacing" />
                                <div class="card-body p-0">
                                    <h6 class="mb-1 font-neue">ინფორმაცია შეკვეთაზე:</h6>
                                    <p class="card-text mb-25">მომხმარებელი: {{ $viewOrder->customer->name }} {{ $viewOrder->customer->lastname }} - {{ $viewOrder->customer->phone }}</p>
                                    <p class="card-text mb-25">ტიპი: {{ $viewOrder->type->name }}</p>
                                    <p class="card-text mb-25">ლოკაცია: {{ json_decode($viewOrder->city->name)->ge }} @if(!empty($viewOrder->district)) / {{ json_decode($viewOrder->district->name)->ge }} @endif @if(!empty($viewOrder->subdistrict))/ {{ json_decode($viewOrder->subdistrict->name)->ge }} @endif</p>
                                    <p class="card-text mb-25">ღირებულება: {{ $viewOrder->amount / 100}} {{ $viewOrder->currency->value }}</p>
                                    @if(!empty($viewOrder->custom_id))
                                    <p class="card-text mb-25">განცხადების ID: {{ $viewOrder->custom_id }}</p>
                                    @endif
                                    @if(!empty($viewOrder->url))
                                    <p class="card-text mb-25">განცხადების ბმული: <a href="{{ $viewOrder->url }}" target="_blank">{{ $viewOrder->url }}</a></p>
                                    @endif
                                </div>
                                @if(!empty($viewOrder->comment))
                                <hr class="invoice-spacing" />
                                <div class="card-body p-0">
                                    <h6 class="mb-1 font-neue">ოპერატორის კომენტარი:</h6>
                                    {!! $viewOrder->comment !!}
                                </div>
                                @endif
                                @if(count($viewOrder->comments) > 0)
                                <hr class="invoice-spacing" />
                                <div class="card-body p-0">
                                    <h6 class="mb-1 font-neue">აგენტის კომენტარი:</h6>
                                    @foreach($viewOrder->comments as $comment)
                                    <div>
                                        <div class="divider divider-start">
                                            <div class="divider-text">
                                                <i data-feather="user"></i> {{ $comment->agent->name }} {{ $comment->agent->lastname }} - <small>{{ \Carbon\Carbon::parse($comment->created_at)->format('d-m-Y g:i') }}</small></div>
                                            </div>
                                        <div>
                                            <span class="mx-2">{{ $comment->comment }}</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                                @if(count($viewOrder->logs) > 0)
                                <hr class="invoice-spacing" />
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>თარიღი</th>
                                                    <th>მოქმედება</th>
                                                    <th>მომხმარებელი</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($viewOrder->logs as $log)
                                            <tr class="text-center">
                                                <td>
                                                    <span class="fw-bold">{{ \Carbon\Carbon::parse($log->created_at)->format('d-m-Y h:i:s') }}</span>
                                                </td>
                                                <td>
                                                    <span class="fw-bold">{{ $log->type }}</span>
                                                </td>
                                                <td>
                                                    <span class="fw-bold">{{ $log->creator->name }} {{ $log->creator->lastname }}</span>
                                                </td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </section>
            </div>
            <div class="modal-footer">
                @if($viewOrder && $viewOrder->status_id !== 3)
                    <button type="button" class="btn btn-success" wire:click="confirmOrder({{ $viewOrder->id }})">
                        შეკვეთის დასრულება
                    </button>
                @endif
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">დახურვა</button>
            </div>
        </div>
    </div>
</div>
