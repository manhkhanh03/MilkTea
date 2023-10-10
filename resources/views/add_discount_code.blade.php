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
    <link rel="stylesheet" href="/css/add_discount_code.css">
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
            <div class="body__add-product" id="basic-form">
                <h2 class="header">Basic information</h2>
                <div class="body__add-product-form">
                    <div class="__add-product__left">Type code</div>
                    <div class="__add-product__right">
                        <div class="__right-img">
                            <input class="input-basic" disabled type="text" name="type-code" id="type-code"
                                value="{{ ucwords($usecase) }}" maxlength="100" placeholder="Enter the Type code...">
                            <p class="form-message"></p>
                        </div>
                    </div>
                </div>
                <div class="body__add-product-form">
                    <div class="__add-product__left">Discount program name</div>
                    <div class="__add-product__right">
                        <div class="__right-img">
                            <input class="input-basic" type="text" name="discount-name" id="discount-name"
                                value="" maxlength="100" placeholder="Enter the Discount program name...">
                            <p class="form-message"></p>
                            <p class="form-noti">The name of the voucher will not be displayed to the buyer.</p>
                        </div>
                    </div>
                </div>
                <div class="body__add-product-form">
                    <div class="__add-product__left">Voucher code</div>
                    <div class="__add-product__right">
                        <div class="__right-img">


                            {{-- viết lại name cho mã voucher --}}


                            @php
                                $name = 'MANH';
                            @endphp
                            <span class="name-voucher-code">{{ $name }}</span>
                            <input class="input-basic" type="text" name="voucher-code" id="voucher-code"
                                value="" maxlength="5" placeholder="Enter the Voucher code (max length 5)...">
                            <p class="form-message"></p>
                            <p class="form-noti">Please enter only letters (A-Z), numbers (0-9); maximum of 5
                                characters. The full discount code is: {{ $name }}.</p>
                        </div>
                    </div>
                </div>
                <div class="body__add-product-form">
                    <div class="__add-product__left ">The voucher's usage period</div>
                    <div class="__add-product__right product_category">
                        <input type="text" id="'datetimes" name="datetimes" class="__right__box-select input-basic">
                        <p class="form-message"></p>
                        <p class="form-noti"></p>
                    </div>
                </div>
            </div>
            <div class="body__add-product" id="basic-form">


                @php
                    $set = 'Set';
                    
                    // Nếu bạn sử dụng từ “set”, câu của bạn sẽ được dịch thành “Set up a discount code”, nghĩa là bạn đang tạo ra một mã giảm giá mới.
                    // Nếu bạn sử dụng từ “setting”, câu của bạn sẽ được dịch thành “Setting a discount code”, nghĩa là bạn đang điều chỉnh hoặc cấu hình một mã giảm giá đã tồn tại.
                    
                @endphp

                <h2 class="header">{{ $set }} up a discount code</h2>
                <div class="body__add-product-form">
                    <div class="__add-product__left">Type of discount | Discount rate</div>
                    <div class="__add-product__right">
                        <div class="__right-img">
                            <div class="type-left">
                                <span class="header-item" data="$">By price($)</span>
                                <ul class="list-type-left">
                                    <li class="item-type-left" data="$">By price($)</li>
                                    <li class="item-type-left" data="%">By percentage(%)</li>
                                </ul>
                            </div>
                            <div class="type-right">
                                <span class="type-dis">$</span>
                                <input class="input-basic" type="number" name="type-code" id="type-dis-code"
                                    value="" maxlength="100" placeholder="">
                                <p class="form-message"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="body__add-product-form">
                    <div class="__add-product__left">Minimum order value</div>
                    <div class="__add-product__right">
                        <div class="__right-img">
                            <span class="name-voucher-code">$</span>
                            <input class="input-basic" type="number" name="min-price" id="min-price"
                                value="" maxlength="5" placeholder="">
                            <p class="form-message"></p>
                            <p class="form-noti"></p>
                        </div>
                    </div>
                </div>
                <div class="body__add-product-form">
                    <div class="__add-product__left">Maximum total usage</div>
                    <div class="__add-product__right">
                        <div class="__right-img">
                            <input class="input-basic" type="number" name="total-discount" id="total-discount"
                                value="" maxlength="5" placeholder="">
                            <p class="form-message"></p>
                            <p class="form-noti">Total number of discount codes that can be used</p>
                        </div>
                    </div>
                </div>
                <div class="body__add-product-form">
                    <div class="__add-product__left">Maximum usage per buyer</div>
                    <div class="__add-product__right">
                        <div class="__right-img">
                            <input class="input-basic" type="number" name="max-used" id="max-used"
                                maxlength="5" value="1">
                            <p class="form-message"></p>
                            <p class="form-noti"></p>
                        </div>
                    </div>
                </div>
            </div>

             <div class="body__add-product" id="basic-form">
                <h2 class="header">Applicable products</h2>
                <div class="body__add-product-form">
                    <div class="__add-product__left">Applicable products</div>
                    <div class="__add-product__right">
                        <div class="__right-img">
                            @php
                                $appliProduct = 'All products'
                                // sử dụng switch case để lấy type rồi gán
                            @endphp
                            <span class="item-appicable">{{ $appliProduct }}</span>
                        </div>
                    </div>
                </div>
             </div>

            <div class="form-button">
                <button class="form-button__btn" id="form-button__cancel">Cancel</button>
                <button class="form-button__btn is-hidden" id="form-button__hidden">Hidden</button>
                @if (isset($product_edit) && $product_edit != '')
                    <button class="form-button__btn is-edit" id="form-button__edit">Edit</button>
                @else
                    <button class="form-button__btn is-upload" id="form-button__upload">Upload</button>
                @endif
            </div>
        </div>
    </section>

    <a class="sig" target="_blank">Manh Khanh</a>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropbox.js/10.11.0/Dropbox-sdk.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/date-fns/1.30.1/date_fns.min.js" referrerpolicy="no-referrer">
    </script>
    <script src="/js/main.js"></script>
    <script src="/js/frame_vendor.js"></script>
    <script src="/js/login.js"></script>
    <script src="/js/add_discount_code.js"></script>

    <script>
        $(function() {
            $('input[name="datetimes"]').daterangepicker({
                timePicker: true,
                startDate: moment().startOf('hour'),
                endDate: moment().startOf('hour').add(1, 'hour'),
                locale: {
                    format: 'DD/MM/YYYY hh:mm A'
                }
            });
        });

        addEventClick({
            elementClick: '.header-item',
            elementSelector: '.list-type-left',
            classList: 'active',
            attribute: 'data',
            typeDis: '.type-dis'
        })

        handleImport({
            form: '.container-add-product',
            formInput: '.input-basic',
            formMessage: '.form-message',
            rules: [
                handleImport.isFocus('#discount-name', 'Please enter your Discount name.'),
                handleImport.isFocus('#voucher-code', 'Please enter your code.'),
                handleImport.isFocus('#type-dis-code', 'You must not leave this box empty.'),
                handleImport.isFocus('#min-price', 'You must not leave this box empty.'),
                handleImport.isFocus('#total-discount', 'You must not leave this box empty.'),
                handleImport.isFocus('#max-used', 'You must not leave this box empty.'),
                handleImport.isMaximumBuyer('#max-used', `The maximum usage per buyer must not exceed the total maximum usage of the voucher`,
                    `The minimum usage count for each buyer is 1.`, '#total-discount'),
                handleImport.isMaximumBuyer('#total-discount', '', `The total minimum usage count is 1, and the maximum is 200,000.`, '#total-discount'),
                handleImport.isTypeDiscount('#type-dis-code', 'The value must be from 1,000 to 120,000,000.', `The percentage must be from 1 to 100.`, '.type-dis', [1, 200000, 1, 100]),
            ],
            isSuccess: function (data) {
                
            }
        })

        function addEventClick(options) {
            const elementClick = document.querySelector(options.elementClick)
            const elementSelector = document.querySelector(options.elementSelector)

            function childClickHandler(event, e) {
                const typeDis = document.querySelector(options.typeDis)
                event.target.innerText = e.target.innerText;
                event.target.setAttribute(options.attribute, e.target.getAttribute(options.attribute))
                typeDis.innerText = e.target.getAttribute(options.attribute)
                elementSelector.classList.remove(options.classList);
            }

            function addEventToElement(element, event) {
                element.addEventListener('click', function(e) {
                    childClickHandler(event, e);
                });
            }

            elementClick.addEventListener('click', function(event) {
                elementSelector.classList.toggle(options.classList);
                const elementSelectorChild = elementSelector.children;

                Array.from(elementSelectorChild).forEach(function(ele, index) {
                    addEventToElement(ele, event);
                });
            });

            window.addEventListener('click', function(event) {
                if (!event.target.matches(options.elementClick)) {
                    elementSelector.classList.remove(options.classList);
                }
            })
        }
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
