{{-- @extends('frame_user') --}}
@extends($extend)

@php
    use Carbon\Carbon;
    $user = Auth::user();
@endphp

@push('styles')
    <style>
        .btn-operation {
            display: block
        }
        .btn-operation .btn-operation__btn {
            padding: 0;
        }
    </style>
@endpush

{{-- @push('body-products') --}}
@push($body)
    <div class="info-profile detail-shipping">
        <h3 style="color: #e0e0e0;">Delivery Address</h3>
        <div class="__detail-shipping">
            <div class="__detail-shipping__info-user">
                <p class="name">{{ $location[0]['user']['user_name'] }}</p>
                <p class="phone-number">{{ $location[0]['user']['phone'] }}</p>
                <p class="address">{{ $location[0]['user']['address'] }}</p>
            </div>
            <div class="__detail-shipping__shipping">
                @foreach ($location[0]['location'] as $lc)
                    <div class="__shipping-box">
                        <p class="datetime">{{ \Carbon\Carbon::parse($lc['updated_at'])->format('d-m-Y H:i') }}</p>
                        <p class="description">{{ $lc['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <ul class="list-products__order">
            @foreach ($location as $sp)
                <li class="__order-item">
                    <div class="__order-item__status">
                        {{ $sp['status'] }}
                    </div>
                    <div class="__status__info-order">
                        <div class="__info-order__decription">
                            <div class="__decription__img"
                                style="background-image: 
                                                                        url({{ $sp['image'] }});">
                            </div>
                            <div class="__decription__decription">
                                <h3 class="name">{{ $sp['product'] }}</h3>
                                <p class="flavor">Flavor: {{ $sp['flavor'] }}</p>
                                <p class="size">Size: {{ $sp['size'] }}</p>
                                <p class="quantity">Quantity: {{ $sp['quantity'] }}</p>
                            </div>
                        </div>
                        <div class="__info-order__price">
                            ${{ $sp['price'] }}
                        </div>
                    </div>
                    <div class="__detail__total">
                        <div class="__total">
                            Total: <span style="color: var(--color-title);">${{ $sp['total'] }}</span>
                        </div>
                        @if (isset($role) && $role == 'customer')
                            <div class="__btn_order">
                                @if ($sp['status'] == 'Await delivery')
                                    <button class="cancel-order">Cancel an order</button>
                                    <button class="contact-seller">Contact Seller</button>
                                @elseif ($sp['status'] == 'In delivery')
                                    <button class="contact-seller">Contact Seller</button>
                                @elseif ($sp['status'] == 'Delivered')
                                    <button class="buy-again">By Again</button>
                                    <button class="contact-seller">Contact Seller</button>
                                @elseif ($sp['status'] == 'Cancelled')
                                    <button class="buy-again">By Again</button>
                                    <button class="view-cancellation-details">View Cancellation Details</button>
                                    <button class="contact-seller">Contact Seller</button>
                                @endif
                            </div>
                        @elseif(isset($role) && $role == 'vendor')
                            <div class="btn-operation" style="width: 100%;">
                                @if ($sp['status'] == 'Await delivery' || $sp['status'] == 'Awaiting pickup')
                                    <button class="btn-operation__btn btn-cancel" order_id="{{ $sp['order_id'] }}"
                                        data-type="{{ $sp['status'] }}">
                                        Cancel
                                    </button>
                                @elseif($sp['status'] == 'Waiting confirmation' || $sp['status'] == 'Cancellation request')
                                    <button class="btn-operation__btn btn-cancel" order_id="{{ $sp['order_id'] }}"
                                        data-type="{{ $sp['status'] }}">
                                        Cancel
                                    </button>
                                    <button class="btn-operation__btn btn-accept" order_id="{{ $sp['order_id'] }}"
                                        data-type="{{ $sp['status'] }}">
                                        Accept
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>

    </div>
@endpush
