@extends('frame_login')

@section('title', 'Register')

@push('styles')
<link rel="stylesheet" href="/css/login.css">
<link rel="stylesheet" href="/css/register.css">
@endpush

@push('body')
<div class="main">
    <div class="back-img"></div>
    <form action="" id="login" method="POST">

        <h1
            style="margin: 0px 0 40px; text-align: center; font-family: var(--font-family-title); color: var(--color-title); font-size: 40px;">
            Register</h1>

        <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <input class="form-input" type="text" id="username" placeholder="Username">
            <p class="form-message"></p>
        </div>
        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input class="form-input" type="email" id="email" placeholder="Email">
            <p class="form-message"></p>
        </div>
        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input class="form-input" type="password" id="password" placeholder="Password">
            <p class="form-message"></p>
        </div>
        <div class="form-group">
            <label for="confirm-password" class="form-label">Confirm Password</label>
            <input class="form-input" type="password" id="confirm-password" placeholder="Confirm Password">
            <p class="form-message"></p>
        </div>

        <button id="form-submit">Register</button>

    </form>

    <div class="register">
        <p>Already have an account? <a href="login">Login now.</a></p>
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
        handleImport.isFocus('#email', 'Please enter your Email'),
        handleImport.isFocus('#confirm-password', 'Please re-enter your password'),
        handleImport.isPassword('#password', 'Password must be at least 8 characters'),
        handleImport.isEmail('#email', 'Please enter a valid email address'),
        handleImport.isConfirmPassword('#confirm-password', 'Re-enter your password', '#password'),
        ],
        isSuccess: function (data) {
            const newData = {
                login_name: data.username,
                email: data.email,
                password: data.password
            }
            handleApiMethodPost({
                data: newData,
                urlApi: '/api/user',
                urlWeb: '/login',
                formMessage: '.form-message',
                cases: [
                    handleApiMethodPost.isSelectorFail('#username', 'Username already exists'),
                    handleApiMethodPost.isSelectorFail('#email', 'Email already exists'),
                ],
                handle: function (data, options) {
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