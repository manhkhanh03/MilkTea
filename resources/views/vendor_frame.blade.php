<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor - Milk Tea</title>
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/user_profile.css">
    <link rel="stylesheet" href="/css/slide.css">
    <link rel="stylesheet" href="/css/frame_vendor.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Great+Vibes" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .login__user .icon {
            margin: 14px;
        }
    </style>
    @stack('styles')
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


    <section style="margin-bottom: 138px;"></section>
    <section>
        <div class="container">
            <div class="left-sidebar">
                <div class="sidebar-item {{ $type == 'discount' ? 'active' : null }}">
                    <div class="sidebar-item__title">
                        <div class="sidebar-item__title-left">
                            <div class="__title-left__img" style="background-image: url(/img/coupon.png);"></div>
                            <div class="__title-left__name">Discount</div>
                        </div>
                        <i class="fa-solid fa-chevron-down btn-action"></i>
                    </div>
                    <ul class="sidebar-item__list-function">
                        <li class="__list-function-item">
                            All product
                        </li>
                        <li class="__list-function-item">
                            A product
                        </li>
                    </ul>
                </div>

                <div class="sidebar-item {{ $type == 'order' ? 'active' : null }}">
                    <div class="sidebar-item__title">
                        <div class="sidebar-item__title-left">
                            <div class="__title-left__img" style="background-image: url(/img/check-list.png);"></div>
                            <div class="__title-left__name">Order Management</div>
                        </div>
                        <i class="fa-solid fa-chevron-down btn-action"></i>
                    </div>
                    <ul class="sidebar-item__list-function">
                        <li class="__list-function-item">
                            <a class="{{isset($type_child) ? ($type_child == "all" ? "active" : "") : ""}}" href="{{$url_web}}/vendor/order">All</a>
                        </li>
                        <li class="__list-function-item">
                            <a class="{{isset($type_child) ? ($type_child == "waiting_confirmation" ? "active" : "") : ""}}" href="{{$url_web}}/vendor/order?type=waiting_confirmation">Waiting confirmation</a>
                        </li>
                        <li class="__list-function-item">
                            <a class="{{isset($type_child) ? ($type_child == "awaiting_pickup" ? "active" : "") : ""}}" href="{{$url_web}}/vendor/order?type=awaiting_pickup">Awaiting pickup</a>
                        </li>
                        <li class="__list-function-item">
                            <a class="{{isset($type_child) ? ($type_child == "await_delivery" ? "active" : "") : ""}}" href="{{$url_web}}/vendor/order?type=await_delivery">Await delivery</a>
                        </li>
                        <li class="__list-function-item">
                            <a class="{{isset($type_child) ? ($type_child == "in_delivery" ? "active" : "") : ""}}" href="{{$url_web}}/vendor/order?type=in_delivery">In delivery</a>
                        </li>
                        <li class="__list-function-item">
                            <a class="{{isset($type_child) ? ($type_child == "delivered" ? "active" : "") : ""}}" href="{{$url_web}}/vendor/order?type=delivered">Delivered</a>
                        </li>
                        <li class="__list-function-item">
                            <a class="{{isset($type_child) ? ($type_child == "cancel" ? "active" : "") : ""}}" href="{{$url_web}}/vendor/order?type=cancel">Cancel</a>
                        </li>
                    </ul>
                </div>
                <div class="sidebar-item  {{ $type == 'product' ? 'active' : null }}">
                    <div class="sidebar-item__title">
                        <div class="sidebar-item__title-left">
                            <div class="__title-left__img" style="background-image: url(/img/product.png);"></div>
                            <div class="__title-left__name">Product Management</div>
                        </div>
                        <i class="fa-solid fa-chevron-down btn-action"></i>
                    </div>
                    <ul class="sidebar-item__list-function">
                        <li class="__list-function-item">
                            <a class="{{isset($type_child) ? ($type == "product" ? "active" : "") : ""}}" href="{{$url_web}}/vendor/product">All products</a>
                        </li>
                        <li class="__list-function-item">
                            <a class="{{isset($type_child) ? ($type_child == "add_product" ? "active" : "") : ""}}" href="{{$url_web}}/vendor/product/add/new">Add product</a>
                        </li>
                    </ul>
                </div>
                <div class="sidebar-item  {{ $type == 'finance' ? 'active' : null }}">
                    <div class="sidebar-item__title">
                        <div class="sidebar-item__title-left">
                            <div class="__title-left__img" style="background-image: url(/img/stats.png);"></div>
                            <div class="__title-left__name">Finance</div>
                        </div>
                        <i class="fa-solid fa-chevron-down btn-action"></i>
                    </div>
                    <ul class="sidebar-item__list-function">
                        <li class="__list-function-item">
                            <a class="{{isset($type_child) ? ($type_child == "income" ? "active" : "") : ""}}" href="{{$url_web}}/vendor/finance/income">Income</a>
                        </li>
                        <li class="__list-function-item">
                            <a class="{{isset($type_child) ? ($type_child == "account_balance" ? "active" : "") : ""}}" href="{{$url_web}}/vendor/finance/account/balance">Account Balance</a>
                        </li>
                    </ul>
                </div>
                <div class="sidebar-item  {{ $type == 'data' ? 'active' : null }}">
                    <div class="sidebar-item__title">
                        <div class="sidebar-item__title-left">
                            <div class="__title-left__img" style="background-image: url(/img/stocks.png);"></div>
                            <div class="__title-left__name">Data</div>
                        </div>
                        <i class="fa-solid fa-chevron-down btn-action"></i>
                    </div>
                    <ul class="sidebar-item__list-function">
                        <li class="__list-function-item">
                            <a class="{{isset($type_child) ? ($type_child == "income" ? "active" : "") : ""}}" href="{{$url_web}}/vendor/data/sales/analysis">Sales Analysis</a>
                        </li>
                    </ul>
                </div>
                <div class="sidebar-item  {{ $type == 'chatbot' ? 'active' : null }}">
                    <div class="sidebar-item__title">
                        <div class="sidebar-item__title-left">
                            <div class="__title-left__img" style="background-image: url(/img/message.png);"></div>
                            <div class="__title-left__name">Customer Service</div>
                        </div>
                        <i class="fa-solid fa-chevron-down btn-action"></i>
                    </div>
                    <ul class="sidebar-item__list-function">
                        <li class="__list-function-item">
                            <a class="{{isset($type) ? ($type == "chatbot" ? "active" : "") : ""}}" href="{{$url_web}}/vendor/customer/service/chatbot?type=auto_chat">Chatbot</a>
                        </li>
                    </ul>
                </div>
            </div>

            @stack('body-vendor')
        </div>
    </section>

    <a class="sig" target="_blank">Manh Khanh</a>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

    <script src="/js/slide.js"></script>
    <script src="/js/main.js"></script>
    <script src="/js/frame_vendor.js"></script>
    @stack('js')
    <script>
        handleAction({
            action: '.sidebar-item__title',
            iconAction: '.btn-action',
            classUp: 'fa-chevron-up',
            classDown: 'fa-chevron-down',
            selector: '.sidebar-item__list-function',
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
