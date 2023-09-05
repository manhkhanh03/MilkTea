<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/slide.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Great+Vibes" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @stack('style')
</head>

@php
    $user = Auth::user();
@endphp

<body>
    @section('menu')
        <menu>
            <div class="menu__logo">
                <img src="/img/Initial Fashion Logo Caffe.png" alt="">
            </div>
            <ul class="menu__navbar">
                <li class="menu__navbar-item">
                    <a href="{{ $url_web }}/home">
                        Home
                    </a>
                </li>
                <li class="menu__navbar-item">
                    <a href="{{ $url_web }}/menu/products">
                        Menu
                    </a>
                </li>
                <li class="menu__navbar-item">
                    <a href="#footer">
                        About
                    </a>
                </li>
                <li class="menu__navbar-item">
                    <a href="">
                        Contact
                    </a>
                </li>
                <li class="menu__navbar-item">
                    <a href="{{ $url_web }}/cart/user">
                        <img src="/img/bag.png" alt="" class="icon">
                    </a>
                </li>
                <li class="menu__navbar-item parent-login-user">
                    <div class="login__user">
                        @if (Auth::check())
                            <img style="margin-right: 6px" src="{{ $user->img_user }}" alt="" class="icon">
                            <span>{{ $user->user_name }}</span>
                        @else
                            <a href="{{ $url_web }}/login" class="menu__navbar-item__icon">
                                <img style="margin-right: 6px" src="/img/user.png" alt="" class="icon">
                                <span></span>
                            </a>
                        @endif
                    </div>
                    @if (Auth::check())
                        <ul class="menu__navbar__logout-and-info-user">
                            <li class="__information-user" id="information">
                                <a href="{{ $url_web }}/user/account/profile?type=profile">Information user</a>
                            </li>
                            @can('is-vendor', Auth::user())
                                <li class="__information-user" id="vendor">
                                    <a href="{{ $url_web }}/vendor/home">Sales Management</a>
                                </li>
                            @endcan
                            @can('is-delivery_staff', Auth::user())
                                <li class="__information-user" id="delivery_staff">
                                    Delivery Management
                                </li>
                            @endcan
                            @can('is-admin', Auth::user())
                                <li class="__information-user" id="vendor">
                                    <a href="{{ $url_web }}/vendor/home">Sales Management</a>
                                </li>
                                <li class="__information-user" id="delivery_staff">
                                    Delivery Management
                                </li>
                                <li class="__information-user" id="admin">
                                    Administrator
                                </li>
                            @endcan
                            <li class="__information-user logout" id="logout">
                                Log out
                            </li>
                        </ul>
                    @endif
                </li>
            </ul>
        </menu>
    @show

    @stack('banner')
    @section('slider')
        <div class="slider">
            <div class="slider__slide slider__slide--active" data-slide="1">
                <div class="slider__wrap">
                    <div class="slider__back"></div>
                </div>
                <div class="slider__inner">
                    <div class="slider__content">
                        <h1 style="font-family: var(--font-family-title); color: var(--color-title);">Welcome</h1><a
                            href="menu/products" class="go-to-next">Order Now</a>
                    </div>
                </div>
            </div>
            <div class="slider__slide" data-slide="2">
                <div class="slider__wrap">
                    <div class="slider__back"></div>
                </div>
                <div class="slider__inner">
                    <div class="slider__content">
                        <h1 style="font-family: var(--font-family-title); color: var(--color-title);">Welcome</h1><a
                            href="menu/products" class="go-to-next">Order Now</a>
                    </div>
                </div>
            </div>
            <div class="slider__slide" data-slide="3">
                <div class="slider__wrap">
                    <div class="slider__back"></div>
                </div>
                <div class="slider__inner">
                    <div class="slider__content">
                        <h1 style="font-family: var(--font-family-title); color: var(--color-title);">Welcome</h1><a
                            href="menu/products" class="go-to-next">Order Now</a>
                    </div>
                </div>
            </div>
            <div class="slider__indicators"></div>
        @show
    </div><a class="sig" target="_blank">Manh Khanh</a>

    @stack('body')

    @section('footer')
        <footer id="footer" style="margin: auto; text-align: center;"> About </footer>
    @show

    @section('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
            integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="/js/slide.js"></script>
        <script src="/js/main.js"></script>
        @stack('js')
        <script>
            handleLogout({
                urlApi: '/logout',
                btnLogout: '#logout',
                handle: function(data, options) {
                    window.location.href = URLWeb + '/login'
                }
            })

            handleProfile({
                urlApi: '/user/account/profile',
                btn: '#information',
                handle: function(options) {
                    window.location.href = URLWeb + options.urlApi
                }
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
    @show

</body>

</html>
