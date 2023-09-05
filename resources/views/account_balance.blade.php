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

        .__navbar-item__info {
            width: 100%;
        }

        .nav-item__date {
            width: 22%;
        }

        .nav-item__amount {}

        .nav-item__status {}


        .__navbar-item__info li:nth-child(2) {
            width: 87%;
            text-align: left;
        }

        .navbar-order {
            width: 30%;
            margin: 30px 0;
            border: 1px solid var(--color-title);
            height: auto;
        }

        .box-right-product {
            width: 100%;
        }

        .__item-product__payment-date {
            width: 16%;
        }

        .__item-product__description {
            width: 63%;
            text-align: left;
        }

        .__item-product__amount {}

        .__item-product__status {}
    </style>
@endpush

@php
    use Carbon\Carbon;
    $user = Auth::user();
@endphp

@push('body-vendor')
    <div class="page-container">
        <div class="overview">
            <h2 class="heading">Balance</h2>
            <ul class="list_time">
                <li class="__time-item">
                    <p>Total</p>
                    @php
                        $total = 0;
                        foreach ($finance as $item) {
                            if ($item['type'] == 'revenue')
                                $total += $item['amount'];
                            else if ($item['type'] == 'withdraw' || $item['type'] == 'refund')
                                $total -= $item['amount'];
                        }
                    @endphp
                    <span>$</span>
                    <span id="this_total">{{number_format($total, 2)}}</span>
                    <button class="balance" id="balance">Withdraw money</button>
                </li>
            </ul>
        </div>

        <div class="detail">
            <h2 class="heading">Recent activities</h2>
            <div class="body-order__time-interval __time-interval-income">
                <p>Time interval</p>
                <input type="text" name="dates" value="2 hours left">
            </div>

            <ul class="navbar-order">
                <li class="navbar-order__item {{ $transaction == '' ? 'active' : ''}}">
                    <a href="{{ $url_web }}/vendor/finance/account/balance">
                        All
                    </a>
                </li>
                <li class="navbar-order__item {{ $transaction == 'withdraw' ? 'active' : ''}}">
                    <a href="{{ $url_web }}/vendor/finance/account/balance?type=withdraw">
                        Withdraw
                    </a>
                </li>
                <li class="navbar-order__item {{ $transaction == 'refund' ? 'active' : ''}}">
                    <a href="{{ $url_web }}/vendor/finance/account/balance?type=refund">
                        Refund
                    </a>
                </li>
            </ul>

            <div class="__info-products__navbar">
                <ul class="__navbar-item__info list-nav">
                    <li class="__navbar-item__info-item nav-item__date">
                        Date
                    </li>
                    <li class="__navbar-item__info-item nav-item__description">
                        Description
                    </li>
                    <li class="__navbar-item__info-item nav-item__amount">
                        Amount
                    </li>
                    <li class="__navbar-item__info-item nav-item__status">
                        Status
                    </li>
                </ul>
            </div>

            <ul class="list-products list-product__account-balance">
                @if (count($finance) != 0)
                    @foreach ($finance as $item)
                        <li class="__item-product">
                            <div class="box-right-product">
                                <p class="__item-product__child __item-product__payment-date">
                                    {{ Carbon::parse($item['created_at'])->toDateString() }}</p>
                                <p class="__item-product__child __item-product__description">{{ $item['description'] }}</p>
                                <p class="__item-product__child __item-product__amount {{$item['type'] == 'revenue' ? 'green' : 'yellow'}}">{{ $item['amount'] }}</p>
                                <p class="__item-product__child __item-product__status">{{ $item['status'] }}</p>
                            </div>
                        </li>
                    @endforeach
                @else
                    <li class="__item-product">No transaction history.</li>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/date-fns/1.30.1/date_fns.min.js" referrerpolicy="no-referrer">
    </script>
    <script>
        console.log(@json($finance))
        const formattedDate = dateFns.parse(@json($startDate).replaceAll('-', '/'), 'yyyy/MM/dd', new Date());
        $('input[name="dates"]').daterangepicker({
            startDate: dateFns.format(formattedDate, 'yyyy-MM-dd'),
        });
        $(function() {
            $('input[name="dates"]').daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                updateInfoByDate({
                    parentList: '.list-product__account-balance',
                    startDate: start.format('YYYY-MM-DD'),
                    endDate: end.format('YYYY-MM-DD'),
                    urlApi: `/api/vendor/finance/account/balance`,
                })
            });
        });

        function updateInfoByDate(options) {
            const parentList = document.querySelector(options.parentList)

            const data = {
                executor_id: @json($user->id),
                startDate: options.startDate,
                endDate: options.endDate,
            }

            if (@json($transaction)) 
                data.type = @json($transaction);
            axios.post(URLWeb + options.urlApi, data)
                .then(data => {
                    console.log(data)
                    const htmls = data.data.map(function(product, index) {
                        return `
                            <li class="__item-product">
                                <div class="box-right-product">
                                    <p class="__item-product__child __item-product__payment-date">
                                        ${dateFns.format(new Date(product.created_at), 'yyyy-MM-dd')}</p>
                                    <p class="__item-product__child __item-product__description">${ item.description }</p>
                                    <p class="__item-product__child __item-product__amount ${item.type == 'revenue' ? 'green' : 'yellow'}">${ item.amount }</p>
                                    <p class="__item-product__child __item-product__status">${ item.status }</p>
                                </div>
                            </li>
                        `
                    }).join('')

                    parentList.innerHTML = htmls
                })
        }
    </script>
@endpush
