@extends('frame')

@section('title', 'Milk Tea User Profile')

@push('style')
    <link rel="stylesheet" href="/css/user_profile.css">
    <link rel="stylesheet" href="/css/login.css">
    @stack('style-css')
@endpush

@section('menu')
    @parent
@endsection

@section('slider')
@endsection

<section>
    <div class="container">
        <div class="container__profile">
            <div class="nav-profile">
                <div class="__header img-user">
                    <p class="img-user__img" id="user-image" style="background-image: url({{ $user->img_user }});">
                        <label for="file-upload" class="label-input"></label>
                        <input type="file" name="file-img" id="file-upload" placeholder="Choose images">
                    </p>
                    <p class="__header__name">{{ $user->user_name }}</p>
                </div>
                <ul class="list-nav__profile">
                    <li class="nav__profile-item">
                        <a
                            href="{{ $url_web }}/user/account/profile?type=profile">
                            <div class="__profile-item__name">
                                <i class="fa-regular fa-user"></i>
                                <p class="name">My account</p>
                            </div>
                        </a>
                        @if ($type == 'profile')
                            <ul class="list__item my-account">
                                <li class="list__item-item {{ $type_child == 'profile' ? 'active' : '' }}">
                                    <a href="{{ $url_web }}/user/account/profile?type=profile">
                                        <button class="__item-item__btn">
                                            Profile
                                        </button>
                                    </a>
                                </li>
                                <li class="list__item-item {{ $type_child == 'bank' ? 'active' : '' }}">
                                    <a href="{{ $url_web }}/user/account/profile?type=bank">
                                        <button class="__item-item__btn">
                                            Bank
                                        </button>
                                    </a>
                                </li>
                                <li class="list__item-item {{ $type_child == 'change_password' ? 'active' : '' }}">
                                    <a href="{{ $url_web }}/user/account/profile?type=change_password">
                                        <button class="__item-item__btn">
                                            Change password
                                        </button></a>
                                </li>
                            </ul>
                        @endif
                    </li>
                    <li class="nav__profile-item purchase-order">
                        <a
                            href="{{ $url_web }}/user/purchase/order?status=waiting_confirmation">
                            <div class="__profile-item__name">
                                <i class="fa-solid fa-list-check"></i>
                                <p class="name">Purchase order</p>
                            </div>
                        </a>
                    </li>
                    <li class="nav__profile-item">
                        <a href="{{ $url_web }}/user/account/notification?type=update_order">
                            <div class="__profile-item__name">
                                <i class="fa-regular fa-bell"></i>
                                <p class="name">Notification</p>
                            </div>
                        </a>

                        @if ($type == 'notification')
                            <ul class="list__item my-account">
                                <li class="list__item-item {{ $type_child == 'update_order' ? 'active' : '' }}">
                                    <a>
                                        <button class="__item-item__btn">
                                            Update order
                                        </button>
                                    </a>
                                </li>
                                <li class="list__item-item {{ $type_child == 'shop' ? 'active' : '' }}">
                                    <a>
                                        <button class="__item-item__btn">
                                            Shop
                                        </button>
                                    </a>
                                </li>
                            </ul>
                        @endif
                    </li>
                    <li class="nav__profile-item">
                        <a href="{{ $url_web }}/user/account/register_account">
                            <div class="__profile-item__name">
                                <i class="fa-regular fa-bell"></i>
                                <p class="name">Register account</p>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
            @stack('body-products')
        </div>
    </div>
</section>

{{-- @push('body')
    @stack('body_frame')
@endpush --}}

@section('footer')
@endsection

@section('scripts')
    @push('js')
        <script src="/js/user_profile.js"></script>
        <script src="/js/login.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
            integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        @stack('js_frame')
        <script>
        </script>
    @endpush
    @parent
@endsection
