@extends('vendor_frame')

@push('body-vendor')
    <div class="page-container">
        <div class="page-container__container">
            <ul class="__container__thing-to-do">
                <li class="__thing-to-do__item">
                    <a href="{{$url_web}}/vendor/order?type=waiting_confirmation">
                        <p class="__item__quantity">{{ $data['task_list']['waiting_confirmation'] }}</p>
                        <p class="__item__title">Waiting confirmation</p>
                    </a>
                </li>
                <li class="__thing-to-do__item">
                    <a href="{{$url_web}}/vendor/order?type=awaiting_pickup">
                        <p class="__item__quantity">{{ $data['task_list']['awaiting_pickup'] }}</p>
                        <p class="__item__title">Awaiting pickup</p>
                    </a>
                </li>
                <li class="__thing-to-do__item">
                    <a href="{{$url_web}}/vendor/order?type=await_delivery">
                        <p class="__item__quantity">{{ $data['task_list']['await_delivery'] }}</p>
                        <p class="__item__title">Await delivery</p>
                    </a>
                </li>
                <li class="__thing-to-do__item">
                    <a href="{{$url_web}}/vendor/order?type=await_delivery">
                        <p class="__item__quantity">{{ $data['task_list']['resolved'] }}</p>
                        <p class="__item__title">Resolved</p>
                    </a>
                </li>
                <li class="__thing-to-do__item">
                    <a href="{{$url_web}}/vendor/order?type=cancel">
                        <p class="__item__quantity">{{ $data['task_list']['cancel'] }}</p>
                        <p class="__item__title">Cancellation request</p>
                    </a>
                </li>
                <li class="__thing-to-do__item">
                    <a href="{{$url_web}}/vendor/product?type=violation">
                        <p class="__item__quantity">{{ $data['task_list']['locked'] }}</p>
                        <p class="__item__title">Product violates</p>
                    </a>
                </li>
                <li class="__thing-to-do__item">
                    <a href="{{$url_web}}/vendor/product?type=sold_out">
                        <p class="__item__quantity">{{ $data['task_list']['sold_out'] }}</p>
                        <p class="__item__title">Product sold out</p>
                    </a>
                </li>
                <li class="__thing-to-do__item">
                    <a href="{{$url_web}}/vendor/order?type=delivered">
                        <p class="__item__quantity">{{ $data['task_list']['complete'] }}</p>
                        <p class="__item__title">Complete</p>
                    </a>
                </li>
            </ul>

            <div class="__container__sales-analysis">

                <div class="__sales-analysis__header">
                    <h3>Sales analysis</h3>
                    <a href="#">See more</a>
                </div>
                <p>Overview of Confirmed Orders Data for the Shop.</p>

                <div class="__sales-analysis__stock">
                    <div class="__stock-stock">
                        <p>Revenue</p>
                        <p class="__stock-stock-revenue" id="revenue">
                            ${{ number_format($data['sales_analysis']['revenue'], 2) }}</p>
                        <canvas id="sales-analysis"></canvas>
                    </div>

                    <div class="__stock-data">
                        <div class="stock-data__box">
                            <div class="__box-sub">
                                <p>Visit</p>
                                <p class="__box-question"
                                    title="The total number of unique visitors who have viewed the homepage and product page of the Shop within the selected time range. Each visitor viewing a product page multiple times is counted as a unique visitor."
                                    style="background-image: url(/img/question.png);"></p>
                            </div>
                            <div class="__box-quantity">{{ $data['sales_analysis']['visit'] }}</div>
                            <div class="__box-yesterday">
                                <div class="__box-yesterday__description">
                                    With yesterday.</div>
                                <p class="__box-yesterday__percent">{{ $data['sales_analysis']['visit_yesterday'] }}%</p>
                            </div>
                        </div>
                        <div class="stock-data__box">
                            <div class="__box-sub">
                                <p>Views</p>
                                <p class="__box-question"
                                    title="The total number of views on the homepage and product page of the Shop within the selected time range (from Desktop and App)."
                                    style="background-image: url(/img/question.png);"></p>
                            </div>
                            <div class="__box-quantity">{{ $data['sales_analysis']['view'] }}</div>
                            <div class="__box-yesterday">
                                <div class="__box-yesterday__description">
                                    With yesterday.</div>
                                <p class="__box-yesterday__percent">{{ $data['sales_analysis']['view_yesterday'] }}%</p>
                            </div>
                        </div>
                        <div class="stock-data__box">
                            <div class="__box-sub">
                                <p>Orders</p>
                                <p class="__box-question"
                                    title="
                                    The total value of confirmed orders within the selected time range."
                                    style="background-image: url(/img/question.png);">
                                </p>
                            </div>
                            <div class="__box-quantity">{{ $data['sales_analysis']['order'] }}</div>
                            <div class="__box-yesterday">
                                <div class="__box-yesterday__description">
                                    With yesterday.</div>
                                <p class="__box-yesterday__percent">{{ $data['sales_analysis']['order_yesterday'] }}%</p>
                            </div>
                        </div>
                        <div class="stock-data__box">
                            <div class="__box-sub">
                                <p>Conversion rate</p>
                                <p class="__box-question"
                                    title="
                                    The ratio of the total number of customers with confirmed orders to the total number of visitors within the selected time range."
                                    style="background-image: url(/img/question.png);"></p>
                            </div>
                            <div class="__box-quantity">{{ $data['sales_analysis']['conversion_rate'] }}%</div>
                            <div class="__box-yesterday">
                                <div class="__box-yesterday__description">
                                    With yesterday.</div>
                                <p class="__box-yesterday__percent">
                                    {{ $data['sales_analysis']['conversion_rate_yesterday'] }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>
@endpush

@push('js')
    <script>
        function addDataCanvas() {
            const data = @json($data['sales_analysis']['data_set']);
            let dataSetTotal = [];
            let dataSetQuantity = [];
            const date = new Date();
            let currentHour = date.getHours();

            for (let key in data) {
                console.log((data[key].price).toFixed(2))
                dataSetTotal.push((data[key].price).toFixed(2))
                dataSetQuantity.push(data[key].quantity)
                if (currentHour % 2 != 0)
                    currentHour--
                if (currentHour == key)
                    break;
            }

            const myChart = new Chart("sales-analysis", {
                type: "line",
                data: {
                    labels: ["00:00", "02:00", "04:00", "06:00", "08:00", "10:00", "12:00", "14:00", "16:00",
                        "18:00",
                        "20:00", "22:00", "24:00"
                    ],
                    datasets: [{
                            label: "Total",
                            data: dataSetTotal,
                            borderColor: "#c8a16d",
                            borderWidth: 2,
                        },
                        {
                            label: "Quantity",
                            data: dataSetQuantity,
                            borderColor: "#c8c46d",
                            borderWidth: 2,
                        }
                    ],

                },
                options: {
                    legend: {
                        display: false
                    },
                    scales: {
                        xAxes: [{
                            position: 'bottom',
                            gridLines: {
                                display: true,
                                zeroLineColor: "white",
                            },
                            ticks: {
                                display: true,
                                fontColor: "white"
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                display: true,
                                zeroLineColor: "white"
                            },
                            ticks: {
                                display: true,
                                fontColor: "white",
                                beginAtZero: true
                            }
                        }],
                        x: {
                            // position: 'bottom',
                        },
                        y: {
                            ticks: {
                                callback: function(value, index, values) {
                                    return "";
                                }
                            },
                            min: 0,
                        }
                    }
                }
            });
        }

        addDataCanvas()
    </script>
@endpush
