<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Milk Tea</title>
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/slide.css">
    <link rel="stylesheet" href="/css/frame_vendor.css">
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

    <meta name="google-signin-client_id"
        content="599670662336-h8sduddcjisjheata6c9a9c0oj7jo3f5.apps.googleusercontent.com">
    <section style="margin-bottom: 138px;"></section>
    <section>
        <div class="container container-add-product">
            <div class="body__add-product" id="basic-form">
                <h2 class="header">Basic information</h2>
                <div class="body__add-product-form">
                    <div class="__add-product__left">Product photos</div>
                    <div class="__add-product__right">
                        <ul class="__right-img">
                            @if (!empty($product_edit) && $product_edit != '')
                                @foreach ($product_edit['images'] as $image)
                                    <li class="__right-img__item">
                                        <img src="{{ $image['url'] }}" data-id="{{ $image['id'] }}"
                                            class="__item-img img-product-add-new">
                                        <div class="box-handle-delete">
                                            <p id="crop-image" style="background-image: url(/img/crop.png)"></p>
                                            <p id="delete-image" style="background-image: url(/img/delete.png)"></p>
                                        </div>
                                    </li>
                                @endforeach
                            @endif
                            <li class="__right-img__item">
                                <div class="__item-add">
                                    <img src="/img/add-image.png" alt="" class="__item-add__img">
                                    <p>Add photos (<span
                                            id="quantity-images">{{ $product_edit != '' ? count($product_edit['images']) : 0 }}</span>/6)
                                    </p>
                                </div>
                                <input type="file" name="add-image" id="add-image">
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="body__add-product-form">
                    <div class="__add-product__left">Product name</div>
                    <div class="__add-product__right">
                        <div class="__right-img">
                            <input class="input-basic" type="text" name="product-name" id="product-name"
                                value="{{ $product_edit != '' ? trim($product_edit['product_name']) : '' }}"
                                maxlength="120" placeholder="Enter the product name...">
                            <p class="characters"><span
                                    id="quantity-characters">{{ $product_edit != '' ? strlen($product_edit['product_name']) : 0 }}</span>/120
                            </p>
                            <p class="form-message"></p>
                        </div>
                    </div>
                </div>
                <div class="body__add-product-form">
                    <div class="__add-product__left">Quantity</div>
                    <div class="__add-product__right">
                        <div class="__right-img">
                            <input class="input-basic" type="number" name="quantity-product" id="quantity-product"
                                value="{{ $product_edit != '' ? $product_edit['product_quantity'] : '' }}"
                                maxlength="120" placeholder="Enter the quantity...">
                            <p class="form-message"></p>
                        </div>
                    </div>
                </div>
                <div class="body__add-product-form">
                    <div class="__add-product__left ">Product category</div>
                    <div class="__add-product__right product_category">
                        <input disabled class="__right__box-select" id="product_category"
                            value="{{ $product_edit != '' ? ucwords($product_edit['psf'][0]['type']) : '' }}"
                            placeholder="Select: ">
                        <ul class="__right__list-category">
                            <li class="list-category__item" data-type="1">Milk Tea</li>
                            <li class="list-category__item" data-type="2">Coffee</li>
                            <li class="list-category__item" data-type="3">Juice</li>
                            <li class="list-category__item" data-type="4">Ice Cream</li>
                        </ul>
                        <p class="form-message"></p>
                    </div>
                </div>
            </div>

            <div class="body__add-product" id="group-form">
                <h2 class="header">Sales information</h2>
                @if (!empty($product_edit) && $product_edit != '')
                    @foreach ($product_edit['psf'] as $key => $product)
                        <div class="box-group" id="box-group">
                            <div class="body__add-product-form">
                                <div class="__add-product__left">Flavor</div>
                                <div class="__add-product__right product_flavor">
                                    <div class="__right-img">
                                        <input type="text" name="categorization" id="categorization"
                                            data-type="{{ $product['type'] }}" maxlength="120"
                                            value="{{ $product['flavor_name'] }}"
                                            placeholder="Please enter or select flavor...">
                                        <p class="characters"><span
                                                id="quantity-characters-categorization">{{ strlen($product['flavor_name']) }}</span>/120
                                        </p>
                                        <ul class="__right__list-category select-flavor">
                                            @foreach ($flavors as $flavor)
                                                <li class="list-category__item" data-type="{{ $flavor->type }}"
                                                    data-id="{{ $flavor->id }}">
                                                    {{ $flavor->name }}</li>
                                            @endforeach
                                        </ul>
                                        <p class="form-message"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="body__add-product-form">
                                <div class="__add-product__left">Size</div>
                                <div class="__add-product__right product_size">
                                    <div class="__right-img">
                                        <input type="text" name="size-product" id="size-product" maxlength="120"
                                            value="{{ $product['size_name'] }}"
                                            data-type="{{ $product['size_id'] }}"
                                            placeholder="Please choose a size..." disabled>
                                        <ul class="__right__list-category ">
                                            <li class="list-category__item" data-type="1">Small</li>
                                            <li class="list-category__item" data-type="2">Medium</li>
                                            <li class="list-category__item" data-type="3">Large</li>
                                        </ul>
                                        <p class="form-message"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="body__add-product-form">
                                <div class="__add-product__left">Price</div>
                                <div class="__add-product__right">
                                    <div class="__right-img   icon-price">
                                        <p class="characters">$</p>
                                        <input type="number" name="price-product" id="price-product"
                                            maxlength="120" value="{{ $product['price'] }}"
                                            placeholder="Please enter the price... Example: 2.00">
                                        <p class="form-message"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="body__add-product-form">
                                <div class="__add-product__left"></div>
                                <div class="__add-product__right">
                                    <button class="btn-delete-group">Delete</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="box-group" id="box-group">
                        <div class="body__add-product-form">
                            <div class="__add-product__left">Flavor</div>
                            <div class="__add-product__right product_flavor">
                                <div class="__right-img">
                                    <input type="text" name="categorization" id="categorization" maxlength="120"
                                        placeholder="Please enter or select flavor...">
                                    <p class="characters"><span id="quantity-characters-categorization">0</span>/120
                                    </p>
                                    <ul class="__right__list-category select-flavor">
                                        @foreach ($flavors as $flavor)
                                            <li class="list-category__item" data-type="{{ $flavor->type }}"
                                                data-id="{{ $flavor->id }}">
                                                {{ $flavor->name }}</li>
                                        @endforeach
                                    </ul>
                                    <p class="form-message"></p>
                                </div>
                            </div>
                        </div>
                        <div class="body__add-product-form">
                            <div class="__add-product__left">Size</div>
                            <div class="__add-product__right product_size">
                                <div class="__right-img">
                                    <input type="text" name="size-product" id="size-product" maxlength="120"
                                        placeholder="Please choose a size..." disabled>
                                    <ul class="__right__list-category ">
                                        <li class="list-category__item" data-type="1">Small</li>
                                        <li class="list-category__item" data-type="2">Medium</li>
                                        <li class="list-category__item" data-type="3">Large</li>
                                    </ul>
                                    <p class="form-message"></p>
                                </div>
                            </div>
                        </div>
                        <div class="body__add-product-form">
                            <div class="__add-product__left">Price</div>
                            <div class="__add-product__right">
                                <div class="__right-img   icon-price">
                                    <p class="characters">$</p>
                                    <input type="number" name="price-product" id="price-product" maxlength="120"
                                        placeholder="Please enter the price... Example: 2.00">
                                    <p class="form-message"></p>
                                </div>
                            </div>
                        </div>
                        <div class="body__add-product-form">
                            <div class="__add-product__left"></div>
                            <div class="__add-product__right">
                                <button class="btn-delete-group">Delete</button>
                            </div>
                        </div>
                    </div>
                @endif

                <button class="btn-add-group">Add a group</button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="/js/main.js"></script>
    <script src="/js/frame_vendor.js"></script>
    <script src="/js/add_product.js"></script>
    <script src="/js/login.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropbox.js/10.11.0/Dropbox-sdk.min.js"></script>

    <script>
        console.log(@json($product_edit));

        function handleAddNewGroup(options) {
            const btn = document.querySelector(options.btn)
            const parent = document.querySelector(options.parent)
            btn.addEventListener('click', function(event) {
                const newGroup = document.createElement('div')
                newGroup.className = 'box-group'
                newGroup.innerHTML = `
            <div class="body__add-product-form">
                        <div class="__add-product__left">Flavor</div>
                        <div class="__add-product__right product_flavor">
                            <div class="__right-img">
                                <input type="text" name="categorization" id="categorization" maxlength="120"
                                    placeholder="Please enter or select flavor...">
                                <p class="characters"><span id="quantity-characters-categorization">0</span>/120</p>
                                <ul class="__right__list-category select-flavor">
                                    @foreach ($flavors as $flavor)
                                        <li class="list-category__item" data-type="{{ $flavor->type }}" data-id="{{ $flavor->id }}">{{ $flavor->name }}</li>
                                    @endforeach
                                </ul>
                                <p class="form-message"></p>
                            </div>
                        </div>
                    </div>
                    <div class="body__add-product-form">
                        <div class="__add-product__left">Size</div>
                        <div class="__add-product__right product_size">
                            <div class="__right-img">
                                <input type="text" name="size-product" id="size-product" maxlength="120"
                                    placeholder="Please choose a size..." disabled>
                                <ul class="__right__list-category">
                                    <li class="list-category__item" data-type="1">Small</li>
                                    <li class="list-category__item" data-type="2">Medium</li>
                                    <li class="list-category__item" data-type="3">Large</li>
                                </ul>
                                <p class="form-message"></p>
                            </div>
                        </div>
                    </div>
                    <div class="body__add-product-form">
                        <div class="__add-product__left">Price</div>
                        <div class="__add-product__right">
                            <div class="__right-img   icon-price">
                                <p class="characters">$</p>
                                <input type="number" name="price-product" id="price-product" maxlength="120"
                                    placeholder="Please enter the price... Example: 2.00">
                                <p class="form-message"></p>
                            </div>
                        </div>
                    </div>
                    <div class="body__add-product-form">
                        <div class="__add-product__left"></div>
                        <div class="__add-product__right">
                            <button class="btn-delete-group">Delete</button>
                        </div>
                    </div>
        `
                parent.insertBefore(newGroup, parent.lastElementChild);
                handleDeleteGroup({
                    btn: '.btn-delete-group',
                })
                handleEventSelectedProduct({
                    parent: '.__add-product__right.product_flavor',
                    selector: 'input[name="categorization"]',
                    children: '.list-category__item',
                    attribute: 'data-type',
                })

                handleEventSelectedProduct({
                    parent: '.__add-product__right.product_size',
                    selector: 'input[name="size-product"]',
                    children: '.list-category__item',
                    attribute: 'data-type',
                })

                handleEventSelectedProduct({
                    parent: '.__add-product__right.product_category',
                    selector: '.__right__box-select',
                    children: '.list-category__item',
                    attribute: 'data-type',
                    urlApi: '/api/vendor/product/category/',
                    elementCategory: '.select-flavor',
                    notClick: true,
                })

                // handleSalesInfo({
                //     parent: '.__add-product__right.product_category',
                //     selector: '.__right__box-select',
                //     listItem: '.__add-product__right.product_flavor .list-category__item',
                //     listTypeItem: '.list-category__item',
                //     attribute: 'data-type',
                // })

                handleChangeType({
                    parent: '.__add-product__right.product_category',
                    selector: '.__right__box-select',
                    listItem: '.__add-product__right.product_flavor .list-category__item',
                    listTypeItem: '.list-category__item',
                    attribute: 'data-type',
                })

                handleEventAddCharacter({
                    input: 'input[name="categorization"]',
                    quantity: '#quantity-characters-categorization',
                })

                eventCheckInput()
            })
        }

        handleDeleteGroup({
            btn: '.btn-delete-group',
        })

        // handleSalesInfo({
        //     parent: '.__add-product__right.product_category',
        //     selector: '.__right__box-select',
        //     listItem: '.__add-product__right.product_flavor .list-category__item',
        //     listTypeItem: '.list-category__item',
        //     attribute: 'data-type',
        // })

        handleEventAddImage({
            input: 'input[name="add-image"]',
            parent: '.__right-img',
            quantity: '#quantity-images',
        })

        handleEventAddCharacter({
            input: '#product-name',
            quantity: '#quantity-characters',
        })

        handleEventAddCharacter({
            input: 'input[name="categorization"]',
            quantity: '#quantity-characters-categorization',
        })

        handleAddNewGroup({
            parent: '#group-form',
            btn: '.btn-add-group',
        })

        handleEventSelectedProduct({
            parent: '.__add-product__right.product_category',
            selector: '.__right__box-select',
            children: '.list-category__item',
            attribute: 'data-type',
            urlApi: '/api/vendor/product/category/',
            elementCategory: '.select-flavor',
        })

        handleEventSelectedProduct({
            parent: '.__add-product__right.product_flavor',
            selector: 'input[name="categorization"]',
            children: '.list-category__item',
            attribute: 'data-type',
        })

        handleEventSelectedProduct({
            parent: '.__add-product__right.product_size',
            selector: 'input[name="size-product"]',
            children: '.list-category__item',
            attribute: 'data-type',
        })

        handleEventDeleteImage({
            parent: '.__right-img__item',
            btn: '#delete-image',
            quantity: document.querySelector('#quantity-images'),
            urlApi: `/api/product/image/`,
        })

        // xử lý đẩy dữ liệu lên database
        function handleClickButton(options) {
            const btnUpload = document.querySelector(options.btnUpload)
            const btnHidden = document.querySelector(options.btnHidden)
            const btnCancel = document.querySelector(options.btnCancel)
            const btnEdit = document.querySelector(options.btnEdit)

            const optionsBasicInfo = {
                name: 'input[name="product-name"]',
                quantity: 'input[name="quantity-product"]',
                shop_id: @json($user->id),
                listImg: '.img-product-add-new',
                urlApi: '/api/product',
                urlApiProductImage: '/api/product/image',
            }

            const optionsSalesInfo = {
                flavor: 'input[name="categorization"]',
                size: 'input[name="size-product"]',
                price: 'input[name="price-product"]',
                flavors: @json($flavors),
                type: '.product_category .__right__box-select',
                urlApi: '/api/product/size/flavor',
            }
            console.log(options)
            let result = false;
            // ???????????????? checkkkkkkk edit tránh lỗi, thêm mới thay vì sửa bản ghi
            if (btnUpload) {
                result = confirm('Are you sure you want to post this product?')
                if (result) {
                    optionsBasicInfo.typeClick = 'upload'
                    const isTrue = callFunction(optionsBasicInfo, optionsSalesInfo)
                    if (isTrue) {
                        const result = confirm("Product added successfully");
                        if (result) {
                            window.removeEventListener('beforeunload', beforeUnloadHandler);
                            window.location.href = URLWeb + '/vendor/product?type=awaiting_approval';
                        }
                    }
                }
            } else if (btnHidden && !btnEdit) {
                result = confirm('Are you sure you want to hide this product?')
                if (result) {
                    optionsBasicInfo.typeClick = 'hide'
                    const isTrue = callFunction(optionsBasicInfo, optionsSalesInfo)
                    if (isTrue) {
                        const result = confirm("Product added successfully");
                        if (result) {
                            window.removeEventListener('beforeunload', beforeUnloadHandler);
                            window.location.href = URLWeb + '/vendor/product?type=hide';
                        }
                    }
                }
            } else if (btnCancel) {
                optionsBasicInfo.typeClick = 'cancel'
                callFunction(optionsBasicInfo, optionsSalesInfo)
            } else if (btnHidden && btnEdit) {
                result = confirm('Are you sure you want to edit this product?')
                if (result) {
                    optionsBasicInfo.typeClick = 'hide'
                    optionsSalesInfo.product_id = options.product_id;
                    const isTrue = callFunction(optionsBasicInfo, optionsSalesInfo)
                    if (isTrue) {
                        const result = confirm("Product added successfully");
                        if (result) {
                            window.removeEventListener('beforeunload', beforeUnloadHandler);
                            window.location.href = URLWeb + '/vendor/product?type=hide';
                        }
                    }
                }
            } else if (btnEdit) {
                result = confirm('Are you sure you want to edit this product?')
                if (result) {
                    optionsBasicInfo.typeClick = 'edit'
                    optionsSalesInfo.product_id = options.product_id;
                    optionsSalesInfo.urlApiDelete = `/api/product/size/flavor/${options.product_id}`
                    optionsBasicInfo.urlApi = `/api/product/${options.product_id}`
                    optionsSalesInfo.listImg = @json(isset($product_edit['images']) && $product_edit != '' ? $product_edit['images'] : '');
                    optionsBasicInfo.urlApiDeleteImage = '/api/product/image/delete'
                    const isTrue = callFunction(optionsBasicInfo, optionsSalesInfo)
                    if (isTrue) {
                        const result = confirm("Product edited successfully");
                        if (result) {
                            window.removeEventListener('beforeunload', beforeUnloadHandler);
                            // window.location.href = URLWeb + '/vendor/product?type=confirmed ';
                        }
                    }
                }
            }

            async function callFunction(optionsBasicInfo, optionsSalesInfo) {
                const response = await handleInfoBasic(optionsBasicInfo, optionsSalesInfo);
                if (response) {
                    console.error(response);
                    return false;
                }
                return true
            }
        }

        function eventCheckInput() {
            handleImport({
                form: '.container-add-product',
                formInput: '.input-basic',
                formMessage: '.form-message',
                btnOther: '#form-button__upload',
                rules: [
                    handleImport.isFocus('input[name="product-name"]', 'Please enter your name product'),
                    handleImport.isFocus('input[name="quantity-product"]', 'Please enter your quantity'),
                    handleImport.isFocus('.product_category .__right__box-select',
                        'Please select a product category', 'Select:'),
                    handleImport.isFocus('input[name="categorization"]', 'Please enter your flavor'),
                    handleImport.isFocus('input[name="size-product"]', 'Please enter your size'),
                    handleImport.isFocus('input[name="price-product"]', 'Please enter your price'),
                ],
                isSuccess: function(data) {
                    if (checkQuantityImage({
                            img: '.img-product-add-new',
                        })) {
                        handleClickButton({
                            btnUpload: '.is-upload',
                            btnEdit: '#form-button__edit',
                        })
                    } else {
                        alert('Please add an image.')
                    }
                }
            })

            handleImport({
                form: '.container-add-product',
                formInput: '.input-basic',
                formMessage: '.form-message',
                btnOther: '#form-button__edit',
                rules: [
                    handleImport.isFocus('input[name="product-name"]', 'Please enter your name product'),
                    handleImport.isFocus('input[name="quantity-product"]', 'Please enter your quantity'),
                    handleImport.isFocus('.product_category .__right__box-select',
                        'Please select a product category', 'Select:'),
                    handleImport.isFocus('input[name="categorization"]', 'Please enter your flavor'),
                    handleImport.isFocus('input[name="size-product"]', 'Please enter your size'),
                    handleImport.isFocus('input[name="price-product"]', 'Please enter your price'),
                ],
                isSuccess: function(data) {
                    if (checkQuantityImage({
                            img: '.img-product-add-new',
                        })) {
                        handleClickButton({
                            product_id: @json(isset($product_edit) && $product_edit != '' ? $product_edit->product_id : ''),
                            btnEdit: '#form-button__edit',
                        })
                    } else {
                        alert('Please add an image.')
                    }
                }
            })

            handleImport({
                form: '.container-add-product',
                formInput: '.input-basic',
                formMessage: '.form-message',
                btnOther: '#form-button__hidden',
                rules: [
                    handleImport.isFocus('input[name="product-name"]', 'Please enter your name product'),
                    handleImport.isFocus('input[name="quantity-product"]', 'Please enter your quantity'),
                    handleImport.isFocus('.product_category .__right__box-select',
                        'Please select a product category', 'Select:'),
                    handleImport.isFocus('input[name="categorization"]', 'Please enter your flavor'),
                    handleImport.isFocus('input[name="size-product"]', 'Please enter your size'),
                    handleImport.isFocus('input[name="price-product"]', 'Please enter your price'),
                ],
                isSuccess: function(data) {
                    if (checkQuantityImage({
                            img: '.img-product-add-new',
                        })) {
                        handleClickButton({
                            product_id: @json(isset($product_edit) && $product_edit != '' ? $product_edit->product_id : ''),
                            btnHidden: '.is-hidden',
                            btnEdit: '#form-button__edit',
                        })
                    } else {
                        alert('Please add an image.')
                    }
                }
            })

            handleImport({
                form: '.container-add-product',
                formInput: '.input-basic',
                formMessage: '.form-message',
                btnOther: '#form-button__cancel',
                rules: [],
                isSuccess: function(data) {
                    handleClickButton({
                        btnCancel: '#form-button__cancel',
                    })
                }
            })
        }

        eventCheckInput()

        const beforeUnloadHandler = function(event) {
            event.returnValue = 'Are you sure you want to leave the page?';
        };

        window.addEventListener('beforeunload', beforeUnloadHandler);
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
