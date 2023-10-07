{{-- @extends('purchase_order') --}}
@extends('frame_user')

@php
    $user = Auth::user();
@endphp


@push('body-products')
    <div class="info-profile purchase-order__products">
        <ul class="__header purchase-order__header">
            <li class="__header-item await-shipping {{ $type == 'waiting confirmation' ? 'active' : '' }}">
                <a href="{{ $url_web }}/user/purchase/order?status=waiting_confirmation">
                    Waiting confirmation
                </a>
            </li>
            <li class="__header-item await-shipping  {{ $type == 'awaiting delivery' ? 'active' : '' }}">
                <a href="{{ $url_web }}/user/purchase/order?status=awaiting_delivery">
                    Await delivery
                </a>
            </li>
            <li class="__header-item in-delivery {{ $type == 'in delivery' ? 'active' : '' }}">
                <a href="{{ $url_web }}/user/purchase/order?status=in_delivery">
                    In delivery
                </a>
            </li>
            <li class="__header-item delivered {{ $type == 'delivered' ? 'active' : '' }}">
                <a href="{{ $url_web }}/user/purchase/order?status=delivered">
                    Delivered
                </a>
            </li>
            <li class="__header-item cancelled {{ $type == 'cancelled' ? 'active' : '' }}">
                <a href="{{ $url_web }}/user/purchase/order?status=cancelled">
                    Cancelled
                </a>
            </li>
        </ul>

        <ul class="list-products__order">
            @if (!empty($shipping))
                @foreach ($shipping as $sp)
                    <li class="__order-item">
                        <div class="__order-item__status">
                            {{ $sp['status'] }}
                        </div>
                        <a
                            href="{{ $url_web }}/user/purchase/order/detail_shipping?shipping={{ $sp['shipping_tracking_id'] }}">
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
                        </a>
                        <div class="__detail__total">
                            <div class="__total">
                                Total: <span style="color: var(--color-title);">${{ $sp['total'] }}</span>
                            </div>
                            @if($type == 'Cancelled')
                                <div class="__btn_order">
                                    <button class="buy-again">By Again</button>
                                    <button class="view-cancellation-details">View Cancellation Details</button>
                                    <button class="contact-seller">Contact Seller</button>
                                </div>
                            @elseif($type == 'Delivered')
                                <div class="__btn_order">
                                    <button class="buy-again">By Again</button>
                                    <button class="contact-seller">Contact Seller</button>
                                </div>
                            @else 
                                <div class="__btn_order">
                                    <button class="contact-seller">Contact Seller</button>
                                </div>
                            @endif

                        </div>
                    </li>
                @endforeach
            @else
                <li class="__order-item">No products available</li>
            @endif

        </ul>

    </div>
    <script>
        console.log(@json($type))
    </script>
@endpush
