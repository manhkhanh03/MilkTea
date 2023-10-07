<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Milk Tea</title>
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/slide.css">
    <link rel="stylesheet" href="/css/frame_vendor.css">
    <link rel="stylesheet" href="/css/discount_code.css">
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
        <div class="container">
            <div class="body-discount-code">
                <h1 class="heading-discount-code">
                    Voucher List
                </h1>
                <ul class="list-type__discount">
                    <li class="type__discount-item">
                        <a href="{{ $url_web }}/vendor/">
                            <div class="__discount-item__header">
                                <p class="__header-img" style="background-image: url(/img/shop.png)"></p>
                                <div class="__header-header">Shopwide voucher</div>
                            </div>
                            <p class="__discount-item__subheader">voucher for all products in your shop.</p>
                            <div class="__discount-item__box-btn">
                                <button class="button">Create Voucher</button>
                            </div>
                        </a>
                    </li>
                    <li class="type__discount-item">
                        <a href="{{ $url_web }}/vendor/">
                            <div class="__discount-item__header">
                                <p class="__header-img" style="background-image: url(/img/box.png)"></p>
                                <div class="__header-header">Product voucher</div>
                            </div>
                            <p class="__discount-item__subheader">Voucher for selected products</p>
                            <div class="__discount-item__box-btn">
                                <button class="button">Create Voucher</button>
                            </div>
                        </a>
                    </li>
                    <li class="type__discount-item">
                        <a href="{{ $url_web }}/vendor/">
                            <div class="__discount-item__header">
                                <p class="__header-img" style="background-image: url(/img/avatar.png)"></p>
                                <div class="__header-header">Voucher new customers</div>
                            </div>
                            <p class="__discount-item__subheader">Voucher aimed at attracting new customers and
                                potential customers</p>
                            <div class="__discount-item__box-btn">
                                <button class="button">Create Voucher</button>
                            </div>
                        </a>
                    </li>
                    <li class="type__discount-item">
                        <a href="{{ $url_web }}/vendor/">
                            <div class="__discount-item__header">
                                <p class="__header-img" style="background-image: url(/img/return.png)"></p>
                                <div class="__header-header">Repeat customer voucher</div>
                            </div>
                            <p class="__discount-item__subheader">Voucher aimed at attracting targeted repeat customers
                                to the shop.</p>
                            <div class="__discount-item__box-btn">
                                <button class="button">Create Voucher</button>
                            </div>
                        </a>
                    </li>
                </ul>

                <h1 class="heading-discount-code">List of discount codes</h1>

                <div class="box-list__discount-code">
                    <ul class="__discount-code__list-nav">
                        <li class="__list-nav {{ $status == 'all' || $status == '' ? 'active' : '' }}">
                            <a href="{{ $url_web }}/vendor/discount/code">All</a>
                        </li>
                        <li class="__list-nav {{ $status == 'active' ? 'active' : '' }}">
                            <a href="?status=active">Active</a>
                        </li>
                        <li class="__list-nav {{ $status == 'upcoming' ? 'active' : '' }}">
                            <a href="?status=upcoming">Upcoming</a>
                        </li>
                        <li class="__list-nav {{ $status == 'expired' ? 'active' : '' }}">
                            <a href="?status=expired">Expired</a>
                        </li>
                        <li class="__list-nav {{ $status == 'canceled' ? 'active' : '' }}">
                            <a href="?status=canceled">Canceled</a>
                        </li>
                    </ul>

                    <div class="__discount-code__search">
                        <div class="box-item-search">
                            <p class="this_search">Voucher name</p>
                            <ul class="list-search">
                                <li class="search-item active">Voucher name</li>
                                <li class="search-item">Voucher ID</li>
                            </ul>
                        </div>
                        <input type="text" class="saerch" id="input-search">
                        <div class="box-icon-search">
                            <p class="icon-search" id="icon-search"
                                style="background-image: url(/img/search-interface-symbol.png)"></p>
                        </div>
                    </div>

                    <div class="header-container">
                        <table class="table-list-discount"
                            style="table-layout: fixed; border-collapse: collapse; width: 100%;overflow-x: hidden;">
                            <colgroup>
                                <col style="width: 336px;">
                                <col style="width: 150px;">
                                <col style="width: 130px;">
                                <col style="width: 130px;">
                                <col style="width: 130px;">
                                <col style="width: 130px;">
                                <col style="width: 130px;">
                                <col style="width: 130px;">
                                <col style="width: 116px;">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th class="table-cell table-td__th">Voucher Name | Voucher Code</th>
                                    <th class="table-cell table-td__th">Applicable products</th>
                                    <th class="table-cell table-td__th">Discount</th>
                                    <th class="table-cell table-td__th">Type discount</th>
                                    <th class="table-cell table-td__th">Maximum total usage</th>
                                    <th class="table-cell table-td__th">Used</th>
                                    <th class="table-cell table-td__th">Status</th>
                                    <th class="table-cell table-td__th">Expiry time</th>
                                    <th class="table-cell table-td__th item-header-action" id="item-header-action">
                                        Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="body-container">
                        <table class="table-list-discount"
                            style="table-layout: fixed; border-collapse: collapse; width: 100%;overflow: hidden auto; ">
                            <colgroup>
                                <col style="width: 336px;">
                                <col style="width: 150px;">
                                <col style="width: 130px;">
                                <col style="width: 130px;">
                                <col style="width: 130px;">
                                <col style="width: 130px;">
                                <col style="width: 130px;">
                                <col style="width: 130px;">
                                <col style="width: 116px;">
                            </colgroup>
                            <tbody id="table-body">
                                @if (count($discounts) != 0)
                                    @foreach ($discounts as $key => $discount)
                                        <tr class="tr">
                                            <td class="table-cell-body table-td__th">{{ $discount->code }}</td>
                                            <td class="table-cell-body table-td__th">{{ $discount->type_code }}</td>
                                            <td class="table-cell-body table-td__th">{{ $discount->discount_amount }}
                                            </td>
                                            <td class="table-cell-body table-td__th">
                                                {{ $discount->type_discount_amount }}</td>
                                            <td class="table-cell-body table-td__th">{{ $discount->total }}</td>
                                            <td class="table-cell-body table-td__th">0 ???</td>
                                            <td class="table-cell-body table-td__th">{{ $discount->status }}</td>
                                            <td class="table-cell-body table-td__th">{{ $discount->end_date }}</td>
                                            <td class="table-cell-body table-td__th">
                                                <img src="/img/settings.png" alt="" class="action-discount"
                                                    id="action-discount">
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="discount-none">
                                            <div style="text-align: center;">
                                                <img src="/img/coupon-vendor.png" alt="">
                                                <p>There are no Discount Codes available</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <a class="sig" target="_blank">Manh Khanh</a>
    <ul class="table-action">
        <li class="table-action__item">
            <p style="background-image: url(/img/edit-action.png)"></p>
            <p>Edit</p>
        </li>
        <li class="table-action__item">
            <p style="background-image: url(/img/hide.png)"></p>
            <p>Hide</p>
        </li>
        <li class="table-action__item">
            <p style="background-image: url(/img/delete-action.png)"></p>
            <p>Delete</p>
        </li>
    </ul>
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
    <script src="/js/discount.js"></script>

    <script>
        buttonAction({
            settings: '.action-discount',
            table: '.body-container',
            tableAction: '.table-action',
            header: '.header-container .table-list-discount'
        })

        scrollLeftRight({
            form: '.box-list__discount-code',
            tableHeader: '.header-container .table-list-discount',
            tableBody: '.body-container',
            th: '.table-td__th'
        })

        handleEventChangeTypeSearch({
            selectorSearch: '.search-item ',
            display: '.this_search',
            placeholder: '.c',
        })

        search({
            search: '.box-icon-search',
            input: '#input-search',
            type: '.this_search',
            urlApi: '/api/vendor/discount/code/search',
            display: '#table-body',
            status: @json($status),
            callback: {
                settings: '.action-discount',
                table: '.body-container',
                tableAction: '.table-action',
                header: '.header-container .table-list-discount'
            }
        }, buttonAction)
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
