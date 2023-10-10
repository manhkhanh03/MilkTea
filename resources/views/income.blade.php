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
            width: 14%;
            text-align: center;
        }

        p.__item-product__child.__item-product__payment-date {
            width: 20%;
            text-align: center;
        }

        p.__item-product__child.__item-product__payment-method {
            width: 30%;
            text-align: center;
        }

        p.__item-product__child.__item-product__total {
            width: 6%;
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
        <div class="notification">
            We will collect 5% profit on each product sold.
        </div>

        <div class="overview">
            <h2 class="heading">Overview</h2>
            <ul class="list_time">
                <li class="__time-item">
                    <p>Total</p>
                    <span>$</span>
                    <span id="this_total">{{ number_format($finance['overview']['total'], 2) }}</span>
                </li>
                <li class="__time-item">
                    <p>This mouth</p>
                    <span>$</span>
                    <span id="this_mouth">{{ number_format($finance['overview']['mouth'], 2) }}</span>
                </li>
                <li class="__time-item">
                    <p>This week</p>
                    <span>$</span>
                    <span id="this_week">{{ number_format($finance['overview']['week'], 2) }}</span>
                </li>
            </ul>
        </div>

        <div class="detail">
            <h2 class="heading">Detail</h2>
            <div class="body-order__time-interval __time-interval-income">
                <p>Time interval</p>
                <input type="text" name="dates" value="2 hours left">
            </div>

            <div class="__info-products__navbar">
                <div class="__navbar-item__product">
                    Product
                </div>
                <ul class="__navbar-item__info list-nav">
                    <li class="__navbar-item__info-item nav-item income">
                        Quantity
                    </li>
                    <li class="__navbar-item__info-item nav-item income">
                        Payment date
                    </li>
                    <li class="__navbar-item__info-item nav-item income">
                        Payment method
                    </li>
                    <li class="__navbar-item__info-item nav-item income">
                        Total
                    </li>
                </ul>
            </div>

            <ul class="list-products list-product__income">
                @if (count($finance['detail']) != 0)
                    @foreach ($finance['detail'] as $item)
                        <li class="__item-product">
                            <div class="box-left-product">
                                <p class="__item-product__child __item-product__img"
                                    style="background-image: url({{ $item['url'] }});">
                                </p>
                                <div class="__item-product__info __item-product__child">
                                    <p class="name">{{ $item['product_name'] }}</p>
                                </div>
                            </div>
                            <div class="box-right-product">
                                <p class="__item-product__child __item-product__quantity">{{ $item['quantity'] }}</p>
                                <p class="__item-product__child __item-product__payment-date">{{ $item['order_date'] }}</p>
                                <p class="__item-product__child __item-product__payment-method">
                                    {{ $item['payment_method'] }}</p>
                                <p class="__item-product__child __item-product__total">{{ $item['total'] }}</p>
                            </div>
                        </li>
                    @endforeach
                @else
                    <li class="__item-product">There are no products.</li>
                @endif
            </ul>
        </div>
    </div>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-adapter-date-fns/3.0.0/chartjs-adapter-date-fns.min.js"
        integrity="sha512-rwTcVAtpAmT3KnwlKHOqeV7ETOTUdf0uYbR4YGf3149X+X+Rx3tgJOOhqFVsyNl0oMgJSPqAOoFuf57WNN0RYA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/date-fns/1.30.1/date_fns.min.js" referrerpolicy="no-referrer"></script>
    <script>
        const formattedDate = dateFns.parse(@json($startDate).replaceAll('-', '/'), 'yyyy/MM/dd', new Date());
        $('input[name="dates"]').daterangepicker({
            startDate: dateFns.format(formattedDate, 'yyyy-MM-dd'),
        });
        $(function() {
            $('input[name="dates"]').daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                updateInfoByDate({
                    parentList: '.list-product__income',
                    startDate: start.format('YYYY-MM-DD'),
                    endDate: end.format('YYYY-MM-DD'),
                    urlApi: `/api/vendor/finance/income`,
                })
            });
        });

        function updateInfoByDate(options) {
            const parentList = document.querySelector(options.parentList)

            const data = {
                shop_id: @json($user->id),
                startDate: options.startDate,
                endDate: options.endDate,
            }
            axios.post(URLWeb + options.urlApi, data)
                .then(data => {
                    console.log(data)
                    const htmls = data.data.detail.map(function(product, index) {
                        return `
                            <li class="__item-product">
                                <div class="box-left-product">
                                    <p class="__item-product__child __item-product__img"
                                        style="background-image: url(${product.url});">
                                    </p>
                                    <div class="__item-product__info __item-product__child">
                                        <p class="name">${product.product_name}</p>
                                    </div>
                                </div>
                                <div class="box-right-product">
                                    <p class="__item-product__child __item-product__quantity">${product.quantity}</p>
                                    <p class="__item-product__child __item-product__payment-date">${product.order_date}</p>
                                    <p class="__item-product__child __item-product__payment-method">
                                        ${product.payment_method}</p>
                                    <p class="__item-product__child __item-product__total">${product.total}</p>
                                </div>
                            </li>
                        `
                    }).join('')

                    parentList.innerHTML = htmls
                })
        }
    </script>
@endpush
