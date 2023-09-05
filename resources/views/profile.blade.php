@extends('frame_user')

@php
    $user = Auth::user();
@endphp
@push('style-css')
    <style>
        .form-message {
            bottom: -4px;
        }
    </style>
@endpush

@push('body-products')
    @if ($type_child == 'profile')
        <div class="info-profile">
            <div class="__header ">
                <h3
                    style="font-size: 20px; height: 50px; display: flex;
                                                                align-items: center;">
                    My Profile</h3>
            </div>
            <form action="" class="form-information" id="form-information">
                <div class="form-group">
                    <label for="login-name" class="form-label">Login name</label>
                    <input class="form-input" type="text" id="login-name" value="{{ $user->login_name }}"
                        placeholder="Login name">
                </div>
                <div class="form-group">
                    <label for="user-name" class="form-label">User name</label>
                    <input class="form-input" type="text" id="user-name" value="{{ $user->user_name }}"
                        placeholder="User name">
                </div>
                <div class="form-group">
                    <label for="phone-number" class="form-label">Phone number</label>
                    <input class="form-input" type="number" id="phone-number" value="{{ $user->phone }}"
                        placeholder="Phone number">
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input class="form-input" type="text" id="email" value="{{ $user->email }}" placeholder="Email">
                </div>
                <div class="form-group">
                    <label for="address" class="form-label">Address</label>
                    <input class="form-input" type="text" id="address" value="{{ $user->address }}"
                        placeholder="Address">
                </div>
            </form>
            <button id="btn-save-profile">Save</button>
        </div>
    @elseif($type_child == 'bank')
        <div class="info-profile">
            <div class="__header bank">
                <h3
                    style="font-size: 20px; height: 50px; display: flex;
                                                                align-items: center;">
                    Credit card/ Debit card/ Visa card</h3>
                <button class="bank-btn">
                    <p class="img" style="background-image: url(/img/plus.png);"></p>
                    <p>Add card</p>
                </button>
            </div>
            <div style="margin: 100px 0">You have not linked your card.</div>
            <div class="__header bank">
                <h3
                    style="font-size: 20px; height: 50px; display: flex;
                                                                align-items: center;">
                    Bank account</h3>
                <button class="bank-btn">
                    <p class="img" style="background-image: url(/img/plus.png);"></p>
                    <p>Add a bank account</p>
                </button>
            </div>
            <div style="margin: 100px 0">You do not have a bank account.</div>

        </div>
    @else
        <div class="info-profile">
            <div class="__header bank">
                <h3
                    style="font-size: 20px; height: 50px; display: flex;
                                                                align-items: center;">
                    Password</h3>
                <button class="bank-btn" id="change-password-btn">
                    <p>Change password</p>
                </button>
            </div>

            <form class="info-address" id="info-change-password">
                <div class="form-group">
                    <label class="form-label">Old password</label>
                    <input class="form-input" type="password" name="password" id="password" placeholder="Password">
                    <p class="form-message"></p>
                </div>
                <div class="form-group">
                    <label class="form-label">New password</label>
                    <input class="form-input" type="password" name="new_password" id="new_password"
                        placeholder="New password">
                    <p class="form-message"></p>
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm new password</label>
                    <input class="form-input" type="password" name="confirm_new_password" id="confirm_new_password"
                        placeholder="Confirm new password">
                    <p class="form-message"></p>
                </div>

                {{-- <button></button> --}}
            </form>

        </div>
    @endif
@endpush


@push('js_frame')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropbox.js/10.11.0/Dropbox-sdk.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="/js/add_product.js"></script>
    <script>
        handleImageUser({
            selectorImage: '.img-user__img',
            btnChange: '#file-upload',
        })
        handleAlterInformationUser({
            btn: '#btn-save-profile',
            rules: [
                handleAlterInformationUser.isSelector('#login-name', true),
                handleAlterInformationUser.isSelector('#user-name', true),
                handleAlterInformationUser.isSelector('#phone-number', true),
                handleAlterInformationUser.isSelector('#email', true),
                handleAlterInformationUser.isChangeImage('#user-image', true,
                    `url("${@json($user->img_user)}")`),
            ],
            handle: function(data, options) {
                const newData = {
                    login_name: data['#login-name'],
                    user_name: data['#user-name'],
                    email: data['#email'],
                    phone: data['#phone-number'],
                    img_user: data['#user-image'],
                }

                const dataUser = Object.keys(newData).reduce((acc, key) => {
                    const value = newData[key];
                    if (value !== undefined) {
                        acc[key] = value;
                    }
                    return acc;
                }, {});
                console.log(dataUser)

                handleApiMethodPut({
                    urlApi: `/api/user/${@json($user->id)}`,
                    data: dataUser,
                    handle: function(data, options) {
                        window.location.reload()
                    }
                })
            },
        }, @json($user));
    </script>
    <script>
        handleInput({
            form: '#info-change-password',
            inputs: '.form-input',
            labels: '.form-label',
            css: {
                fontSize: "16px",
                top: '-10px',
                color: 'var(--color-title)',
            }
        })

        handleImport({
            form: '#info-change-password',
            formInput: '.form-input',
            formMessage: '.form-message',
            btnOther: '#change-password-btn',
            rules: [
                handleImport.isFocus('#password', 'Please enter your Password'),
                handleImport.isFocus('#new_password', 'Please enter your new Password'),
                handleImport.isFocus('#confirm_new_password', 'Please confirm your new password'),
                handleImport.isPassword('#password', 'Password must be at least 8 characters long'),
                handleImport.isConfirmPassword('#confirm_new_password', 'Re-enter your new password',
                    '#new_password'),
            ],
            isSuccess: function(data) {
                const newData = {
                    current_password: data.password,
                    new_password: data.new_password,
                }
                handleApiMethodPut({
                    data: newData,
                    urlApi: `/api/user/password/${@json($user->id)}`,
                    formMessage: '.form-message',
                    handle: function(data, options) {
                        if (data.error) {
                            const message = document.querySelector(options.formMessage)
                            message.innerText = data.error
                        } else {
                            alert('Password changed successfully.')
                            window.location.reload();
                        }
                    }
                })
            }
        })
    </script>
@endpush
