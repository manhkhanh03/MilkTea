@extends('vendor_frame')
@php
    $user = Auth::user();
@endphp
@push('body-vendor')

    <div class="page-container">
        <div class="notification">
            The delivery time must not exceed 2 hours, <br> if you do not deliver on time your account will be banned from
            selling.
        </div>
        <ul class="navbar-order">
            <li class="navbar-order__item">
                <a class="{{ isset($type_child) ? ($type_child == 'all' ? 'active' : '') : '' }}"
                    href="{{ $url_web }}/vendor/order">
                    All
                </a>
            </li>
            <li class="navbar-order__item">
                <a class="{{ isset($type_child) ? ($type_child == 'waiting_confirmation' ? 'active' : '') : '' }}"
                    href="{{ $url_web }}/vendor/order?type=waiting_confirmation">
                    Waiting confirmation
                </a>
            </li>
            <li class="navbar-order__item">
                <a class="{{ isset($type_child) ? ($type_child == 'awaiting_pickup' ? 'active' : '') : '' }}"
                    href="{{ $url_web }}/vendor/order?type=awaiting_pickup">
                    Awaiting pickup
                </a>
            </li>
            <li class="navbar-order__item">
                <a class="{{ isset($type_child) ? ($type_child == 'await_delivery' ? 'active' : '') : '' }}"
                    href="{{ $url_web }}/vendor/order?type=await_delivery">
                    Await delivery
                </a>
            </li>
            <li class="navbar-order__item">
                <a class="{{ isset($type_child) ? ($type_child == 'in_delivery' ? 'active' : '') : '' }}"
                    href="{{ $url_web }}/vendor/order?type=in_delivery">
                    In delivery
                </a>
            </li>
            <li class="navbar-order__item">
                <a class="{{ isset($type_child) ? ($type_child == 'delivered' ? 'active' : '') : '' }}"
                    href="{{ $url_web }}/vendor/order?type=delivered">
                    Delivered
                </a>
            </li>
            <li class="navbar-order__item">
                <a class="{{ isset($type_child) ? ($type_child == 'cancel' ? 'active' : '') : '' }}"
                    href="{{ $url_web }}/vendor/order?type=cancel">
                    Cancel
                </a>
            </li>
        </ul>
        <div class="body-order__time-interval">
            <p>Time interval</p>
            <input type="text" name="dates" value="2 hours left">
        </div>
        <div class="body-order__search">
            <div class="__search-info">
                <div class="__search-info__type">
                    <p class="__type__name-type">All</p>
                    <ul class="__type__list-type">
                        <li class="__list-type__item">All</li>
                        <li class="__list-type__item">Order ID</li>
                        <li class="__list-type__item">Customer name</li>
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
                        Quantity
                    </li>
                    <li class="__navbar-item__info-item nav-item">
                        Status
                    </li>
                    <li class="__navbar-item__info-item nav-item">
                        Countdown
                    </li>
                    <li class="__navbar-item__info-item nav-item">
                        Operation
                    </li>
                </ul>
            </div>
        </div>
        <ul class="list-products" id="list-products">
            @if (!empty($products))
                @foreach ($products as $product)
                    @php
                        $orderDateTime = strtotime($product['order_date']);
                        $currentDateTime = time();
                        $currentDateTime = $currentDateTime + 7 * 60 * 60;
                        $timeDiffFormatted;
                        
                        if (date('Y-m-d', $orderDateTime) == date('Y-m-d', $currentDateTime)) {
                            $countdownDateTime = $orderDateTime + 2 * 60 * 60;
                            $interval = $countdownDateTime - $currentDateTime;
                        
                            if ($interval <= 0) {
                                $timeDiffFormatted = null;
                            } else {
                                $timeDiffFormatted = gmdate('H:i:s', $interval);
                            }
                        } else {
                            $timeDiffFormatted = null;
                        }
                        
                    @endphp
                    <li class="__item-product">
                        <div class="box-left-product">
                            <a href="{{$url_web}}/vendor/order/detail?shipping={{$product['shipping_tracking_id']}}&type={{str_replace(' ', '_', strtoLower($product['status']))}}">
                                <p class="__item-product__child __item-product__img"
                                    style="background-image: url({{ $product['image'] }});">
                                </p>
                                <div class="__item-product__info __item-product__child">
                                    <p class="name">{{ $product['name'] }}</p>
                                    <p class="flavor">Classify: {{ $product['flavor_type'] }}</p>
                                    <p class="size">Size: {{ $product['size'] }}</p>
                                </div>
                            </a>
                        </div>
                        <div class="box-right-product">
                            <p class="__item-product__child __item-product__quantity">{{ $product['quantity'] }}</p>
                            <p class="__item-product__child __item-product__status">{{ $product['status'] }}</p>
                            @if ($product['status'] == 'Delivered')
                                <p class="__item-product__child __item-product__count-down">
                                    Complete</p>
                                <div class="btn-operation">
                                    <div class="btn-operation__btn"
                                        style="background-image: url(/img/checkmark.png);width: 22px;height: 20px;"></div>
                                </div>
                            @elseif($product['status'] == 'In delivery')
                                <p
                                    class="__item-product__child __item-product__count-down {{ $timeDiffFormatted ? '' : 'overtime' }}">
                                    {{ $timeDiffFormatted ? $timeDiffFormatted : 'Overtime' }}</p>
                                <div class="btn-operation">
                                    <div class="btn-operation__btn"
                                        style="background-image: url(/img/delivery.png);width: 30px;height: 30px;"></div>
                                </div>
                            @elseif($product['status'] == 'Cancelled')
                                <p
                                    class="__item-product__child __item-product__count-down ">
                                    Complete</p>
                                <div class="btn-operation">
                                    <div class="btn-operation__btn"
                                        style="background-image: url(/img/close.png);width: 24px;height: 24px;"></div>
                                </div>
                            @elseif($product['status'] == 'Await delivery' || $product['status'] == 'Awaiting pickup')
                                <p
                                    class="__item-product__child __item-product__count-down {{ $timeDiffFormatted ? '' : 'overtime' }}">
                                    {{ $timeDiffFormatted ? $timeDiffFormatted : 'Overtime' }}</p>
                                <div class="btn-operation">
                                    <button class="btn-operation__btn btn-cancel" order_id="{{ $product['order_id'] }}"
                                        data-type="{{ $product['status'] }}">
                                        Cancel
                                    </button>
                                </div>
                            @else
                                <p
                                    class="__item-product__child __item-product__count-down {{ $timeDiffFormatted ? '' : 'overtime' }}">
                                    {{ $timeDiffFormatted ? $timeDiffFormatted : 'Overtime' }}</p>
                                <div class="btn-operation">
                                    <button class="btn-operation__btn btn-cancel" order_id="{{ $product['order_id'] }}"
                                        data-type="{{ $product['status'] }}">
                                        Cancel
                                    </button>
                                    <button class="btn-operation__btn btn-accept" order_id="{{ $product['order_id'] }}"
                                        data-type="{{ $product['status'] }}">
                                        Accept
                                    </button>
                                </div>
                            @endif
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $('input[name="dates"]').daterangepicker();
        $(function() {
            $('input[name="dates"]').daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                updateInfoByDate({
                    parentList: '#list-products',
                    startDate: start.format('YYYY-MM-DD'),
                    endDate: end.format('YYYY-MM-DD'),
                    urlApi: `/api/vendor/order/search/${@json(Auth::user()->id)}`
                })
            });
        });

        handleEventChangeTypeSearch({
            selectorSearch: '.__list-type__item',
            display: '.__type__name-type',
            placeholder: '#search-order',
        })

        function updateInfoByDate(options) {
            const parentList = document.querySelector(options.parentList)
            const startDate = options.startDate
            const endDate = options.endDate

            const data = {
                startDate: startDate,
                endDate: endDate,
            }

            if (@json($type_child) !== 'all')
                data.type = @json($type_child)

            if (options.type_search) {
                data.type_search = options.type_search
                data.data_search = options.data_search
            }
            axios.put(URLWeb + options.urlApi, data)
                .then(data => {
                    console.log(data.data)
                    const htmls = data.data.map(function(product, index) {
                        var orderDateTime = moment(product.order_date);
                        let timeDiffFormatted;
                        let currentDateTime = moment();

                        if (orderDateTime.format('YYYY-MM-DD') === currentDateTime.format('YYYY-MM-DD')) {
                            let countdownDateTime = moment(orderDateTime).add(2, 'hours');
                            let interval = countdownDateTime.diff(currentDateTime);

                            if (interval <= 0) {
                                timeDiffFormatted = null;
                            } else {
                                timeDiffFormatted = moment.utc(interval).format('HH:mm:ss');
                            }
                        }
                        return `
                            <li class="__item-product">
                                <div class="box-left-product">
                                    <a href="${URLWeb}/vendor/order/detail?shipping=${product.shipping_tracking_id}&type=${(product.status).toLowerCase().replace(' ', '_')}">
                                        <p class="__item-product__child __item-product__img"
                                            style="background-image: url(${ product.image });">
                                        </p>
                                        <div class="__item-product__info __item-product__child">
                                            <p class="name">${ product.name }</p>
                                            <p class="flavor">Classify: ${ product.flavor_type }</p>
                                            <p class="size">Size: ${ product.size }</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="box-right-product">
                                    <p class="__item-product__child __item-product__quantity">${ product.quantity }</p>
                                    <p class="__item-product__child __item-product__status">${ product.status }</p>
                                    ${ product.status == 'Delivered' ?
                                        `<p class="__item-product__child __item-product__count-down">Complete</p>
                                            <div class="btn-operation">
                                                <div class="btn-operation__btn" style="background-image: url(/img/checkmark.png);
                                                width: 22px;height: 20px;"></div>
                                            </div>`
                                    : (product.status == 'In delivery' ? `
                                            <p  class="__item-product__child __item-product__count-down ${ timeDiffFormatted ? '' : 'overtime' }">
                                                ${ timeDiffFormatted ? $timeDiffFormatted : 'Overtime' }</p>
                                            <div class="btn-operation">
                                                <div class="btn-operation__btn" style="background-image: url(/img/delivery.png);width: 30px;height: 30px;"></div>
                                            </div>` 
                                    : (product.status == 'Cancelled' ? `
                                            <p
                                                class="__item-product__child __item-product__count-down">
                                                Complete</p>
                                            <div class="btn-operation">
                                                <div class="btn-operation__btn"
                                                    style="background-image: url(/img/close.png);width: 24px;height: 24px;"></div>
                                            </div>`
                                    : (product.status == 'Awaiting pickup' || product.status == 'Await delivery' ? `
                                            <p
                                                class="__item-product__child __item-product__count-down ${ timeDiffFormatted ? '' : 'overtime' }">
                                                ${ timeDiffFormatted ? timeDiffFormatted : 'Overtime' }</p>
                                            <div class="btn-operation">
                                                <button class="btn-operation__btn btn-cancel" order_id="${ product.order_id }"
                                                    data-type="${ product.status }">
                                                    Cancel
                                                </button>
                                            </div>
                                        ` : `<p class="__item-product__child __item-product__count-down ${ timeDiffFormatted ? '' : 'overtime' }">
                                            ${ timeDiffFormatted ? timeDiffFormatted : 'Overtime' }</p>
                                            <div class="btn-operation">
                                                <button class="btn-operation__btn btn-cancel" order_id="${ product.order_id }" data-type="${product.status}">
                                                    Cancel
                                                </button>
                                                <button class="btn-operation__btn btn-accept" order_id="${ product.order_id }" data-type="${product.status}">
                                                    Accept
                                                </button>
                                            </div>`

                                            )
                                        )
                                    )
                                }
                                    
                                </div>
                            </li>
                        `
                    }).join('')

                    parentList.innerHTML = htmls
                })
                .then(() => {
                    handleStatusProduct({
                        btnCancel: '.btn-operation .btn-cancel',
                        btnAccept: '.btn-operation .btn-accept',
                        order_id: 'order_id',
                        data_type: 'data-type',
                    })
                })
        }

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
                            isConfirm = confirm('Are you sure you want to accept the request to cancel the order?')
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

        handleStatusProduct({
            btnCancel: '.btn-operation .btn-cancel',
            btnAccept: '.btn-operation .btn-accept',
            order_id: 'order_id',
            data_type: 'data-type',
        })

        handleSearch({
            type_search: '.__type__name-type',
            data_search: 'input[name="search-order"]',
            btnSearch: '.btn-search',
            btnReset: '.btn-reset',
            date: 'input[name="dates"]',
            user_id: @json(Auth::user()->id),
            urlApi: `/api/vendor/order/search/${@json(Auth::user()->id)}`,
            type_status: @json($type_child),
            // ??????????????????????
        })
    </script>
@endpush
