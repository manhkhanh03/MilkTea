@extends('frame_login')

@section('title', 'Login')

@push('styles')
    <link rel="stylesheet" href="/css/login.css">
@endpush

@push('body')
    <div class="main">
        <div class="back-img"></div>
        <form action="" id="login" method="POST">

            <h1
                style="margin: 30px 0 70px; text-align: center; font-family: var(--font-family-title); color: var(--color-title); font-size: 40px;">
                Login</h1>

            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input class="form-input" type="text" id="username" placeholder="Username">
                <p class="form-message"></p>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input class="form-input" type="password" id="password" placeholder="Password">
                <span class="form-forgot-pass">Forgot password?</span>
                <p class="form-message"></p>
            </div>

            <div class="form-remember-me">
                <input type="checkbox" name="checkbox-remember-me" id="">
                <span style="font-size: 15px;">Remember me</span>
            </div>

            <button id="form-submit">Login</button>

        </form>

        <div class="register">
            <p>Not a member? <a href="register">Register now</a></p>
        </div>
    </div>
@endpush

@section('scripts')
@push('js')
    <script src="/js/login.js"></script>
    <script>
        handleInput({
            form: '#login',
            inputs: '.form-input',
            labels: '.form-label',
            css: {
                fontSize: "16px",
                top: '-10px',
                color: 'var(--color-title)',
            }
        })

        handleImport({
            form: '#login',
            formInput: '.form-input',
            formMessage: '.form-message',
            rules: [
                handleImport.isFocus('#username', 'Please enter your Username'),
                handleImport.isFocus('#password', 'Please enter your Password'),
                handleImport.isPassword('#password', 'Password must be at least 8 characters long'),
            ],
            isSuccess: function (data) {
                const newData = {
                    login_name: data.username,
                    password: data.password
                }
                handleApiMethodPost({
                    data: newData,
                    urlApi: '/milk/tea/login',
                    urlWeb: '/home',
                    formMessage: '.form-message',
                    cases: [
                        handleApiMethodPost.isSelectorFail('#username', 'Username already exists'),
                        handleApiMethodPost.isSelectorFail('#password', 'Passwords do not match'),
                    ],
                    handle: function(data, options) {
                        console.log(data)
                        if (data.status) {
                            options.cases.forEach((myCase) => {
                                const caseElement = document.querySelector(myCase.selector)
                                const messageElement = caseElement.parentElement.querySelector(options.formMessage)
                                const error = myCase.test(data.status)
                                if (error) {
                                    messageElement.innerText = error
                                } else messageElement.innerText = ''
                            })
                        } else {
                            window.location.href = URLWeb + options.urlWeb;
                        }
                    }
                })
            }
        })

    </script>
@endpush
@parent
@endsection
