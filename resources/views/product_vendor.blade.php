@extends('vendor_frame')

@push('styles')
    <style>
        .__item-product {
            padding: 10px 8px;
        }

        .btn-operation {
            width: 20%;
        }

        /* p.__item-product__child.__item-product__status {
                                width: 17%;
                            } */

        p.__item-product__child.__item-product__classification {
            width: 14%;
            text-align: center;
        }

        p.__item-product__child.__item-product__price {
            text-align: center;
            width: 18%;
        }

        p.__item-product__child.__item-product__quantity {
            width: 11%;
            text-align: center;
        }

        .__navbar-item__info li:nth-child(2) {
            width: 34%;
        }
    </style>
@endpush

@php
    $user = Auth::user();
@endphp

@push('body-vendor')
    <div class="page-container">
        <ul class="navbar-order">
            <li class="navbar-order__item">
                <a class="{{ isset($type_child) ? ($type_child == 'all_product' ? 'active' : '') : '' }}" href="?type=">
                    All
                </a>
            </li>
            <li class="navbar-order__item">
                <a class="{{ isset($type_child) ? ($type_child == 'confirmed' ? 'active' : '') : '' }}"
                    href="?type=confirmed">
                    Confirmed
                </a>
            </li>
            <li class="navbar-order__item">
                <a class="{{ isset($type_child) ? ($type_child == 'sold_out' ? 'active' : '') : '' }}" href="?type=sold_out">
                    Sold out
                </a>
            </li>
            <li class="navbar-order__item">
                <a class="{{ isset($type_child) ? ($type_child == 'awaiting_approval' ? 'active' : '') : '' }}"
                    href="?type=awaiting_approval">
                    Awaiting approval
                </a>
            </li>
            <li class="navbar-order__item">
                <a class="{{ isset($type_child) ? ($type_child == 'violation' ? 'active' : '') : '' }}"
                    href="?type=violation">
                    Violation
                </a>
            </li>
            <li class="navbar-order__item">
                <a class="{{ isset($type_child) ? ($type_child == 'hide' ? 'active' : '') : '' }}" href="?type=hide">
                    Hidden
                </a>
            </li>
        </ul>

        <div style="font-size: 22px;width: 80%;margin: 30px 0;" class="total-product">
            Total: {{ count($products) }} products.
        </div>

        <div class="body-order__search">
            <div class="__search-info">
                <div class="__search-info__type">
                    <p class="__type__name-type">All</p>
                    <ul class="__type__list-type">
                        <li class="__list-type__item">All</li>
                        <li class="__list-type__item">Product ID</li>
                        <li class="__list-type__item">Product</li>
                    </ul>
                </div>

                <div class="__search-info__input">
                    <input type="text" name="search-order" id="search-order" placeholder="All ...">
                </div>
            </div>

            <div class="__search-btn">
                <button class="btn btn-search">Search</button>
                <button class="btn btn-reset">Reset</button>
            </div>
        </div>

        <div class="body-order__info-products">
            <div class="__info-products__navbar">
                <div class="__navbar-item__product">
                    Product
                </div>
                <ul class="__navbar-item__info list-nav">
                    <li class="__navbar-item__info-item nav-item">
                        Classify
                    </li>
                    <li class="__navbar-item__info-item nav-item">
                        Price
                    </li>
                    <li class="__navbar-item__info-item nav-item">
                        Quantity
                    </li>
                    <li class="__navbar-item__info-item nav-item">
                        Status
                    </li>
                    <li class="__navbar-item__info-item nav-item">
                        Operation
                    </li>
                </ul>
            </div>
        </div>

        <ul class="list-products">
            @if (!empty($products))
                @foreach ($products as $product)
                    <li class="__item-product">
                        <div class="box-left-product">
                            <a href="{{$url_web}}/vendor/product/edit?product={{$product['product_id']}}">
                                <p class="__item-product__child __item-product__img"
                                    style="background-image: url({{ $product['image'] }});">
                                </p>
                                <div class="__item-product__info __item-product__child">
                                    <p class="name">{{ $product['name'] }}</p>
                                    <p class="address" style="font-size: 12px;">{{ $user->address }}</p>
                                </div>
                            </a>
                        </div>
                        <div class="box-right-product">
                            <p class="__item-product__child __item-product__classification">
                                {{ ucwords($product['flavor_type']) }}</p>
                            <p class="__item-product__child __item-product__price">${{ $product['prices'][0] }}
                                {{ count($product['prices']) == 1 ? '' : ' - ' . $product['prices'][count($product['prices']) - 1] }}
                            </p>
                            <p class="__item-product__child __item-product__quantity">{{ $product['quantity'] }}</p>
                            <p class="__item-product__child __item-product__status">{{ ucwords($product['status']) }}</p>
                            <div class="btn-operation">
                                <button class="btn-operation__btn btn-delete-product"
                                    data-product="{{ $product['product_id'] }}">
                                    <a href="#">Delete</a>
                                </button>
                                <button class="btn-operation__btn btn-edit-product"
                                    data-product="{{ $product['product_id'] }}">
                                    <a href="{{$url_web}}/vendor/product/edit?product={{$product['product_id']}}">Edit</a>
                                </button>
                            </div>
                        </div>
                    </li>
                @endforeach
            @else
                <li class="__item-product">There are no products.</li>
            @endif
        </ul>
    </div>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        console.log(@json($products));
        handleEventChangeTypeSearch({
            selectorSearch: '.__list-type__item',
            display: '.__type__name-type',
            placeholder: '#search-order',
        })

        function handleStatusProduct(options) {
            const btnCancel = document.querySelectorAll(options.btnCancel)
            const btnAccept = document.querySelectorAll(options.btnAccept)

            btnCancel.forEach(function(item) {
                item.addEventListener('click', function(event) {
                    let isConfirm = false;
                    switch (event.target.getAttribute(options.data_type)) {
                        case 'Waiting confirmation':
                        case 'Await delivery':
                        case 'Awaiting pickup ':
                            isConfirm = confirm('Are you sure you want to cancel the order?')
                            if (isConfirm) {
                                handleApiMethodPut({
                                    urlApi: `/api/shipping/status/order/${event.target.getAttribute(options.order_id)}`,
                                    data: {
                                        status: 'Cancelled'
                                    },
                                    handle: function(data, options) {
                                        window.location.reload()
                                        // ??????????????????????
                                    }
                                })
                            }
                            break;
                        case 'Cancellation request':
                            isConfirm = confirm(
                                'Are you sure you want to cancel the cancellation request for the order?'
                            )
                            if (isConfirm) {
                                handleApiMethodPut({
                                    urlApi: `/api/shipping/status/order/${event.target.getAttribute(options.order_id)}`,
                                    data: {
                                        status: 'check',
                                    },
                                    handle: function(data, options) {
                                        window.location.reload()
                                        // ??????????????????????
                                    }
                                })
                            }
                            break;
                    }
                })
            })

            btnAccept.forEach(function(item) {
                item.addEventListener('click', function(event) {
                    let isConfirm = false;
                    switch (event.target.getAttribute(options.data_type)) {
                        case 'Waiting confirmation':
                            isConfirm = confirm('Are you sure you want to confirm the order?')
                            if (isConfirm) {
                                handleApiMethodPut({
                                    urlApi: `/api/shipping/status/order/${event.target.getAttribute(options.order_id)}`,
                                    data: {
                                        status: 'check',
                                    },
                                    handle: function(data, options) {
                                        window.location.reload()
                                        // ??????????????????????
                                    }
                                })
                            }
                            break;
                        case 'Cancellation request':
                            isConfirm = confirm(
                                'Are you sure you want to accept the request to cancel the order?')
                            if (isConfirm) {
                                handleApiMethodPut({
                                    urlApi: `/api/shipping/status/order/${event.target.getAttribute(options.order_id)}`,
                                    data: {
                                        status: 'Cancelled',
                                    },
                                    handle: function(data, options) {
                                        window.location.reload()
                                        // ??????????????????????
                                    }
                                })
                            }
                            break;
                    }

                })
            })
        }

        function updateInfoByDate(options) {
            const parentList = document.querySelector(options.parentList)

            const data = {}

            if (@json($type_child) !== 'all_product')
                data.type = @json($type_child)

            if (options.type_search) {
                data.type_search = options.type_search.trim()
                data.data_search = options.data_search.trim()
            }
            axios.put(URLWeb + options.urlApi, data)
                .then(data => {

                    function capitalizeAllWords(str) {
                        return str.replace(/\b\w/g, char => char.toUpperCase());
                    }
                    const htmls = data.data.map(function(product, index) {
                        return `
                             <li class="__item-product">
                                <div class="box-left-product">
                                    <a href="${URLWeb}/vendor/product/edit?product=${ product.product_id }">
                                        <p class="__item-product__child __item-product__img"
                                            style="background-image: url(${ product.image });">
                                        </p>
                                        <div class="__item-product__info __item-product__child">
                                            <p class="name">${ product.name }</p>
                                            <p class="address" style="font-size: 12px;">${ @json($user->address) }</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="box-right-product">
                                    <p class="__item-product__child __item-product__classification">
                                        ${ capitalizeAllWords(product.flavor_type) }</p>
                                    <p class="__item-product__child __item-product__price">${ product.prices[0] }
                                        ${ product.prices.length == 1 ? '' : ' - ' + product.prices[product.prices.length - 1] }
                                    </p>
                                    <p class="__item-product__child __item-product__quantity">${ product.quantity }</p>
                                    <p class="__item-product__child __item-product__status">${ capitalizeAllWords(product.status) }</p>
                                    <div class="btn-operation">
                                        <button class="btn-operation__btn btn-delete-product" data-product="${product.product_id}">
                                            Delete
                                        </button>
                                        <button class="btn-operation__btn btn-edit-product" data-product="${product.product_id}">
                                            <a href="${URLWeb}/vendor/product/edit?product=${ product.product_id }">Edit</a>
                                        </button>
                                    </div>
                                </div>
                            </li>
                        `
                    }).join('')

                    parentList.innerHTML = htmls
                })
                .then(() => {
                    handleClickButton({
                        btnDelete: '.btn-delete-product',
                        btnEdit: '.btn-edit-product',
                        attribute_product: 'data-product',
                        urlApi: '/api/vendor/product/'
                    })
                })
        }

        handleSearch({
            type_search: '.__type__name-type',
            data_search: 'input[name="search-order"]',
            btnSearch: '.btn-search',
            btnReset: '.btn-reset',
            user_id: @json(Auth::user()->id),
            urlApi: `/api/vendor/product/search/${@json($user->id)}`,
            type_status: @json($type_child),
            // ??????????????????????
        })

        handleClickButton({
            btnDelete: '.btn-delete-product',
            btnEdit: '.btn-edit-product',
            attribute_product: 'data-product',
            urlApi: `/api/vendor/product/`
        })
    </script>
@endpush
