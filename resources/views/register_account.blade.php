@extends('frame_user')

@php
    $user = Auth::user();
@endphp

@push('body-products')
    <div class="info-profile">
        <div class="__header ">
            <h3
                style="font-size: 20px; height: 50px; display: flex;
                                                                                align-items: center;">
                Register an account</h3>
        </div>
        <div class="box-role-user">
            <ul class="list-role-user">
                <li class="role-user__item">
                    <div class="box-role-user__item">
                        <p class="__name">Customer</p>

                        <div class="__update">
                            <button class="__other">Other</button>
                            <button class="__update__update">Update</button>
                        </div>
                    </div>
                    <ul class="permission-user">
                        <li class="permission-user__item">
                            View Products
                        </li>
                        <li class="permission-user__item">
                            Add to Cart
                        </li>
                        <li class="permission-user__item">
                            View Cart
                        </li>
                        <li class="permission-user__item">
                            Edit Cart
                        </li>
                        <li class="permission-user__item">
                            Checkout
                        </li>
                        <li class="permission-user__item">
                            View Order History
                        </li>
                        <li class="permission-user__item">
                            Manage Account
                        </li>
                    </ul>
                </li>
                <li class="role-user__item">
                    <div class="box-role-user__item">
                        <p class="__name">Vendor</p>

                        <div class="__update">
                            <button class="__other">Other</button>
                            <button class="__update__update">Update</button>
                        </div>
                    </div>
                    <ul class="permission-user">
                        <li class="permission-user__item">
                            All rights of the customer
                        </li>
                        <li class="permission-user__item">
                            View the list of posted products
                        </li>
                        <li class="permission-user__item">
                            Manage product images
                        </li>
                        <li class="permission-user__item">
                            Manage discount codes
                        </li>
                        <li class="permission-user__item">
                            Order Management
                        </li>
                        <li class="permission-user__item">
                            Financial Management
                        </li>
                        <li class="permission-user__item">
                            Shipping Management
                        </li>
                        <li class="permission-user__item">
                            Sales History Tracking
                        </li>
                    </ul>
                </li>
                <li class="role-user__item">
                    <div class="box-role-user__item">
                        <p class="__name">Delivery Satff</p>

                        <div class="__update">
                            <button class="__other">Other</button>
                            <button class="__update__update">Update</button>
                        </div>
                    </div>
                    <ul class="permission-user">
                        <li class="permission-user__item">
                            All rights of the customer
                        </li>
                        <li class="permission-user__item">
                            Receive Order Information
                        </li>
                        <li class="permission-user__item">
                            Confirm Delivery Address
                        </li>
                        <li class="permission-user__item">
                            Update Delivery Status
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
@endpush


@push('js_frame')
    <script>
        function handleOther(options) {
            const btnOther = document.querySelectorAll(options.btnOther)
            const selectors = document.querySelectorAll(options.select)

            btnOther.forEach((element, index) => {
                element.addEventListener('click', function(event) {
                    selectors.forEach(function(ele, i) {

                        if (ele.style.display === 'block') {
                            ele.style.display = 'none'
                        } else if (i == index) {
                            ele.style.display = 'block'
                        }
                    })
                })
            });
        }

        function handleUpdate(options) {
            const btnUpdate = document.querySelectorAll(options.btnUpdate)

            const user_role_id = @json($user->role_id);
            btnUpdate.forEach(function(__this, index) {
                if (user_role_id == 2 || user_role_id == 3 || user_role_id == 4) {
                    __this.classList.add(options.classList)
                } else {
                    __this.addEventListener('click', function(item) {
                        const data = {
                            role_id: index + 1,
                        }

                        handleApiMethodPut({
                            urlApi: `/api/user/${@json($user->id)}`,
                            data: data,
                            handle: function(data, options) {
                                windown.location.reload()
                            }
                        })
                    })
                }
            })
        }

        handleOther({
            btnOther: '.__other',
            select: '.permission-user',
        })

        handleUpdate({
            btnUpdate: '.__update__update',
            classList: 'not-active',
        })
    </script>
@endpush
