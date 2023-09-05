<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Milk Tea</title>
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/slide.css">
    <link rel="stylesheet" href="/css/frame_vendor.css">
    <link rel="stylesheet" href="/css/sales_analysis.css">
    <link rel="stylesheet" href="/css/login.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Great+Vibes" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="manifest" href="/app.webmanifest" crossorigin="use-credentials" />
</head>

@php
    $user = Auth::user();
@endphp

<body>
    <menu>
        <div class="menu__logo">
            <a href="{{ $url_web }}/vendor/home"><img src="/img/Initial Fashion Logo Caffe.png" alt=""></a>
        </div>
        <div class="menu__navbar-item login__user">
            <img src="{{ $user->img_user }}" alt="" class="icon">
            <span>{{ $user->user_name }}</span>
        </div>
    </menu>

    <section>
        <div class="container container-add-product">
            <nav>
                <ul class="nav">
                    <li class="nav-item">Overview</li>
                    <li class="nav-item">Product</li>
                </ul>
            </nav>

            <div class="fixed-time-selector">
                <div id="reportrange" style="">
                    <img src="/img/calendar.png" alt="" class="reportange-img">
                    <span></span> <i class="fa fa-caret-down"></i>
                </div>

                <div class="select-type-product">
                    <span>Order type: </span>
                    <span class="type-select" id="type-select">Order confirmed</span>
                    <ul class="type-product">
                        <li class="type-product__item">Order placed</li>
                        <li class="type-product__item">Order confirmed</li>
                        <li class="type-product__item">Order paid</li>
                    </ul>
                </div>
            </div>


            <div class="overview">
                <h2 class="header">Overview</h2>
                <ul class="list-item__overview">
                    @php
                        $order = [];
                        $orderTotal = 0;
                        $orderBeforeTotal = $salesAnalysis['overview']['order_before'];
                        $revenue = [];
                        $revenueTotal = 0;
                        $revenueBeforeTotal = 0;
                        $visit = [];
                        $visitTotal = 0;
                        $visitBeforeTotal = $salesAnalysis['overview']['visit_before'];
                        $pageView = [];
                        $pageViewTotal = 0;
                        $pageViewBeforeTotal = $salesAnalysis['overview']['page_view_before'];
                        
                        function handleData($arrayCurrent, &$arrNew, &$element, $typeTime, $endDate)
                        {
                            $timeLast = 0;
                            $endDateValue = strtotime($endDate);
                            $timeEndDate = date($typeTime, $endDateValue);
                            $currentMonth = date('m', $endDateValue);
                            if (count($arrayCurrent) == 0) {
                                for ($i = 1; $i <= $timeEndDate; $i++) {
                                    $arrNew[$i . '/' . number_format($currentMonth)] = 0;
                                }
                            } else {
                                foreach ($arrayCurrent as $key => $value) {
                                    if (isset($value['order_date'])) {
                                        $timestamp = strtotime($value['order_date']);
                                    } else {
                                        $timestamp = strtotime($value['created_at']);
                                    }
                                    $current = date($typeTime, $timestamp);
                                    $currentDate = floatval($current);
                                    if ($currentDate - $timeLast > 1) {
                                        for ($i = $timeLast + 1; $i <= $current; $i++) {
                                            $arrNew[$i . '/' . number_format($currentMonth)] = 0;
                                        }
                                    }
                        
                                    if ($key + 1 == count($arrayCurrent) && floatval($timeEndDate) > $currentDate) {
                                        for ($i = $timeLast + 1; $i <= $timeLast + (floatval($timeEndDate) - $currentDate); $i++) {
                                            $arrNew[$i . '/' . number_format($currentMonth)] = 0;
                                        }
                                    }
                        
                                    if (isset($value['total'])) {
                                        $element += $value['total'];
                                        if (isset($arrNew[$currentDate . '/' . number_format($currentMonth)])) {
                                            $arrNew[$currentDate . '/' . number_format($currentMonth)] += $value['total'];
                                        } else {
                                            $arrNew[$currentDate . '/' . number_format($currentMonth)] = $value['total'];
                                        }
                                    } else {
                                        $element++;
                                        if (isset($arrNew[$currentDate . '/' . number_format($currentMonth)])) {
                                            $arrNew[$currentDate . '/' . number_format($currentMonth)] += 1;
                                        } else {
                                            $arrNew[$currentDate . '/' . number_format($currentMonth)] = 1;
                                        }
                                    }
                        
                                    $timeLast = $currentDate;
                                }
                            }
                        
                            # tùy theo ngữ cảnh mà đặt end date cho tùy biến với dữ liệu, không dể dữ liệu ghi bậy xấu biểu đồ
                        }
                        
                        $null = [];
                        handleData($salesAnalysis['overview']['order'], $order, $orderTotal, $typeTime, $endDate);
                        handleData($salesAnalysis['overview']['revenue'], $revenue, $revenueTotal, $typeTime, $endDate);
                        handleData($salesAnalysis['overview']['revenue_before'], $null, $revenueBeforeTotal, $typeTime, $endDate);
                        handleData($salesAnalysis['overview']['visit'], $visit, $visitTotal, $typeTime, $endDate);
                        handleData($salesAnalysis['overview']['page_view'], $pageView, $pageViewTotal, $typeTime, $endDate);
                        
                        $revenue_order_before = $revenueBeforeTotal - floatval($revenueBeforeTotal) * 0.3;
                        $revenue_order = number_format($revenueTotal - floatval($revenueTotal) * 0.3, 2);
                    @endphp
                    <li class="item__overview revenue active">
                        <p class="heading__oveview">Revenue</p>
                        <p>$<span class="quantity-overview"
                                id="overview__revenue">{{ number_format($revenueTotal, 2) }}</span>
                        </p>
                        <p class="description__overview"><span class="description-with__overview">With yesterday</span>
                            <span
                                class="description-item__overview">{{ $revenueTotal == 0 ? 0 : ($revenueBeforeTotal == 0 ? 100 : min((($revenueTotal - $revenueBeforeTotal) / $revenueBeforeTotal) * 100, 100)) }}</span>%
                        </p>
                    </li>
                    <li class="item__overview order active">
                        <p class="heading__oveview ">Orders</p>
                        <p><span class="quantity-overview" id="overview__order">{{ $orderTotal }}</p></span>
                        <p class="description__overview"><span class="description-with__overview">With yesterday</span>
                            <span
                                class="description-item__overview">{{ $orderTotal == 0 ? 0 : ($orderBeforeTotal == 0 ? 100 : min((($orderTotal - $orderBeforeTotal) / $orderBeforeTotal) * 100, 100)) }}</span>%
                        </p>
                    </li>
                    <li class="item__overview conversion-rate">
                        <p class="heading__oveview">Conversion Rate</p>
                        <p><span class="quantity-overview"
                                id="overview__conversion-rate">{{ number_format($orderTotal == 0 ? 0 : ($visitTotal == 0 ? 100 : $orderTotal / $visitTotal), 2) }}</span><span>%</span>
                        </p>
                        <p class="description__overview"><span class="description-with__overview">With yesterday</span>
                            <span
                                class="description-item__overview">{{ number_format($orderBeforeTotal == 0 ? 0 : ($visitBeforeTotal == 0 ? 100 : $orderBeforeTotal / $visitBeforeTotal), 2) }}</span>%
                        </p>
                    </li>
                    <li class="item__overview visit">
                        <p class="heading__oveview">Visits</p>
                        <p><span class="quantity-overview" id="overview__visit">{{ $visitTotal }}</p></span>
                        <p class="description__overview"><span class="description-with__overview">With yesterday</span>
                            <span
                                class="description-item__overview">{{ $visitTotal == 0 ? 0 : ($visitBeforeTotal == 0 ? 100 : min((($visitTotal - $visitBeforeTotal) / $visitBeforeTotal) * 100, 100)) }}</span>%
                        </p>
                    </li>
                    <li class="item__overview page-view">
                        <p class="heading__oveview">Pageviews</p>
                        <p><span class="quantity-overview" id="overview__page-view">{{ $pageViewTotal }}</p></span>
                        <p class="description__overview"><span class="description-with__overview">With yesterday</span>
                            <span
                                class="description-item__overview">{{ $pageViewTotal == 0 ? 0 : ($pageViewBeforeTotal == 0 ? 100 : (($pageViewTotal - $pageViewBeforeTotal) / $pageViewBeforeTotal) * 100) }}</span>%
                        </p>
                    </li>
                    <li class="item__overview revenue-order">
                        <p class="heading__oveview">Revenue per Order</p>
                        <p>$<span class="quantity-overview" id="overview__revenue-order">{{ $revenue_order }}</p>
                        </span>
                        <p class="description__overview"><span class="description-with__overview">With
                                yesterday</span>
                            <span
                                class="description-item__overview">{{ $revenue_order == 0 ? 0 : ($revenue_order_before == 0 ? 100 : min((($revenue_order - $revenue_order_before) / $revenue_order_before) * 100, 100)) }}</span>%
                        </p>
                    </li>
                </ul>

                <div class="chart-line">
                    <canvas id="sales-analysis" style="height: 300px; width: 100%;"></canvas>
                </div>
            </div>

            <div class="dashboard_ranking">
                <div class="dashboard_ranking_product">
                    <h4 class="dashboard_ranking-header">Product ranking</h4>
                    <ul class="dashboard_ranking-nav product-ranking">
                        <li class="dashboard_ranking-nav__item active">By revenue</li>
                        <li class="dashboard_ranking-nav__item">By product</li>
                        <li class="dashboard_ranking-nav__item">By views</li>
                        <li class="dashboard_ranking-nav__item">By conversion rate</li>
                    </ul>

                    <div class="product-rankings-body">
                        <ul class="theader">
                            <li class="rank">Rank</li>
                            <li class="info">Information product</li>
                            <li class="sales" id="type-dashboard">By revenue</li>
                        </ul>

                        <ul class="product-info product-rank">
                            @foreach ($salesAnalysis['ranking']['productRank'] as $index => $item)
                                <li class="product-info__item">
                                    <p class="product-info__item--rank">{{ $index + 1 }}</p>
                                    <a href="#" class="__item-sales__link">
                                        <div class="product-info__item-sales">
                                            <img src="{{ $item->url }}" alt=""
                                                class="product-info__item-sales__img">
                                            <p class="product-info__item-name">{{ $item->name }}</p>
                                        </div>
                                    </a>
                                    <p class="product-info__item-revenue">{{ $item->total }}</p>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="dashboard_ranking_category">
                    <h4 class="dashboard_ranking-header">Product category ranking</h4>
                    <ul class="dashboard_ranking-nav">
                        <li class="dashboard_ranking-nav__item active">By revenue</li>
                    </ul>

                    <div class="product-rankings-body">
                        <ul class="theader">
                            <li class="rank">Rank</li>
                            <li class="info">Product category</li>
                            <li class="sales">By revenue</li>
                        </ul>

                        <ul class="product-info product-category-rank">
                            @foreach ($salesAnalysis['ranking']['productCategoryRank'] as $index => $item)
                                <li class="product-info__item">
                                    <p class="product-info__item--rank">{{$index + 1}}</p>
                                    <p class="product-info__item-name product-category">{{strtoUpper($item['name_type'])}}</p>
                                    <p class="product-info__item-revenue">{{$item['total']}}</p>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <a class="sig" target="_blank">Manh Khanh</a>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    {{-- <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropbox.js/10.11.0/Dropbox-sdk.min.js"></script>
    <script src="/js/main.js"></script>
    <script src="/js/frame_vendor.js"></script>
    <script src="/js/salesAnalysis.js"></script>
    <script src="/js/login.js"></script>

    <script>
        let daterangepicker;
        function pushDataChart(options) {
            const data = [];
            options.data.forEach(function(item) {
                data.push(item)
            })
            let labels = []
            options.label.forEach(function(item) {
                labels.push(item);
            })

            var ctx = document.getElementById("sales-analysis").getContext("2d");

            if (window.myChart) {
                window.myChart.destroy();
            }

            window.myChart = new Chart(ctx, {
                type: "line",
                data: {
                    labels: options.label,
                    datasets: data,
                },
                options: {
                    legend: {
                        display: true,
                        position: 'bottom',
                    },
                    scales: {
                        x: {
                            display: true,
                            color: "white",
                            position: 'bottom',
                        },
                        y: {
                            display: true,
                            position: 'bottom',
                            ticks: {
                                callback: function(value, index, values) {
                                    return "";
                                }
                            },
                            min: 0,
                        },
                        xAxes: [{
                            // position: 'bottom',
                            gridLines: {
                                color: "#b1b1b1",
                                borderDash: [4, 2],
                                drawBorder: true,
                            },
                            ticks: {
                                display: true,
                            }
                        }],
                        yAxes: [{
                            // position: 'bottom',
                            gridLines: {
                                color: "#b1b1b1",
                                borderDash: [4, 2],
                                drawBorder: true,
                            },
                            ticks: {
                                display: false,
                                beginAtZero: true
                            }
                        }],

                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    tooltips: {
                        mode: 'index'
                    },
                    hover: {
                        mode: 'index'
                    }
                }
            });
        }

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            var beforeDate = end.clone().subtract((end.diff(start, 'days') + 1 + end.diff(start, 'days')),
                'days');

            let startDateMoment = moment(start.format('MMMM D, YYYY'), 'MMMM D, YYYY');
            let beforeDateMoment = moment(beforeDate.format('MMMM D, YYYY'), 'MMMM D, YYYY');
            let endDateMoment = moment(end.format('MMMM D, YYYY'), 'MMMM D, YYYY');
            console.log(startDateMoment, beforeDate.format('MMMM D, YYYY'))
            const data = {
                startDate: startDateMoment.format('YYYY-MM-DD'),
                endDate: endDateMoment.format('YYYY-MM-DD'),
                beforeDate: beforeDateMoment.format('YYYY-MM-DD'),
                typeQuery: document.querySelector('#type-select').innerText.toLowerCase(),
            }

            addDataRanking({
                urlApi: `/api/vendor/sales/analysis/get/rank/${@json($shop_id)}`,
                dashboardTypeSelect: document.querySelector('.product-ranking .active').innerText.toLowerCase(),
                productRank: '.product-rank',
                productCategoryRank: '.product-category-rank',
            })

            let daysDifference = startDateMoment.diff(beforeDateMoment, 'days');
            let typeTime = daysDifference == 1 ? 'H' : 'd';

            axios.post(URLWeb + `/api/vendor/sales/analysis/get/by/date/${@json($shop_id)}`, data)
                .then(response => {
                    getDataByDateSelect({
                        order_before: response.data.overview.order_before,
                        visit_before: response.data.overview.visit_before,
                        page_view_before: response.data.overview.page_view_before,
                        typeTime: typeTime,
                        endDate: endDateMoment,
                        startDate: startDateMoment,
                        totalDate: daysDifference,
                        order: response.data.overview.order,
                        revenue: response.data.overview.revenue,
                        revenue_before: response.data.overview.revenue_before,
                        visit: response.data.overview.visit,
                        page_view: response.data.overview.page_view,
                        isSelect: true,
                    })
                })
                .catch(err => {
                    console.log(err)
                })
        }

        $(function() {
            var start = moment().startOf('month');
            var end = moment().endOf('month');

            let unit = 'days';

            daterangepicker = $('#reportrange span').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                },

            }, cb, callDate);

            function callDate(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }

            callDate(start, end);

        });

        eventClickAddDataChart({
            form: '.list-item__overview',
            element: '.item__overview',
            classList: 'active',
            isClass: {
                isOrder: {
                    item: 'order',
                    color: '#ff3939',
                    name: 'Order',
                },
                isRevenue: {
                    item: 'revenue',
                    color: '#c8a16d',
                    name: 'Revenue',
                },
                isPageView: {
                    item: 'page-view',
                    color: '#c74dff',
                    name: 'Page View',
                },
                isRevenueOrder: {
                    item: 'revenue-order',
                    color: '#ffffff',
                    name: 'Revenue Order',
                },
                isVisit: {
                    item: 'visit',
                    color: '#58c01d',
                    name: 'Visit',
                },
                isConversionRate: {
                    item: 'conversion-rate',
                    color: '#54accf',
                    name: 'Conversion Rate',
                },
            },
            data: {
                revenue: @json($revenue),
                order: @json($order),
                visit: @json($visit),
                pageView: @json($pageView),
                typeTime: @json($typeTime),
            },
        })

        addEventClick({
            elementClick: '#type-select',
            elementSelector: '.type-product',
            classList: 'active',
        })

        addDataCanvas({
            isOrder: true,
            isRevenue: true,
            isVisit: false,
            isConvertionRate: false,
            isPageView: false,
            isRevenueOrder: false,
        }, {
            isOrder: {
                item: 'order',
                color: '#ff3939',
                name: 'Order',
            },
            isRevenue: {
                item: 'revenue',
                color: '#c8a16d',
                name: 'Revenue',
            },
            isPageView: {
                item: 'page-view',
                color: '#c74dff',
                name: 'Page View',
            },
            isRevenueOrder: {
                item: 'revenue-order',
                color: '#fff',
                name: 'Revenue Order',
            },
            isVisit: {
                item: 'visit',
                color: '#58c01d',
                name: 'Visit',
            },
            isConversionRate: {
                item: 'conversion-rate',
                color: '#54accf',
                name: 'Conversion Rate',
            },
        }, {
            revenue: @json($revenue),
            order: @json($order),
            visit: @json($visit),
            pageView: @json($pageView),
            typeTime: @json($typeTime),
        })

        dashboardSelect({
            element: '.product-ranking .dashboard_ranking-nav__item',
            classList: 'active',
            urlApi: `/api/vendor/sales/analysis/get/rank/${@json($shop_id)}`,
            productCategoryRank: '.product-category-rank',
            productRank: '.product-rank',
        })
    </script>
    <script>
        $('a[href*="#"]')
            // Remove links that don't actually link to anything
            .not('[href="#"]')
            .not('[href="#0"]')
            .click(function(event) {
                // On-page links
                if (
                    location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') &&
                    location.hostname == this.hostname
                ) {
                    // Figure out element to scroll to
                    var target = $(this.hash);
                    target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                    // Does a scroll target exist?
                    if (target.length) {
                        // Only prevent default if animation is actually gonna happen
                        event.preventDefault();
                        $('html, body').animate({
                            scrollTop: target.offset().top
                        }, 1000, function() {
                            // Callback after animation
                            // Must change focus!
                            var $target = $(target);
                            $target.focus();
                            if ($target.is(":focus")) { // Checking if the target was focused
                                return false;
                            } else {
                                $target.attr('tabindex', '-1');
                                $target.focus(); // Set focus again
                            };
                        });
                    }
                }
            });
    </script>
</body>

</html>
