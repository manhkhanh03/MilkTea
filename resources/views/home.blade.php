@extends('frame')

@section('title', 'Milk Tea')

@push('style')  
    <link rel="stylesheet" href="/css/index.css">
@endpush

@section('menu')
    @parent
@endsection

@section('slider')
    @parent
@endsection

@push('body')
    <section>
        <div class="container">
            <div class="container__child">
                <div class="container__child-introduce">
                    <h1 style="font-family: var(--font-family-title); font-size: 40px; color: var(--color-title);">Milk
                        Tea</h1>
                    <h1 style="font-size: 40px;padding: 6px 0 50px; line-height: 0;">OUR MENU</h1>
                    <p style="color: #838383;" class="introduce">
                        Milk tea - with diverse flavors, the combination of fresh tea and smooth milk, along with
                        enticing toppings, creates a
                        delicious and enjoyable beverage, offering relaxation and satisfaction to the taste buds.
                    </p>
                    <button>
                        <a href="menu/products">View Full Menu</a>
                    </button>
                </div>
            </div>
            <div class="container__child">
                <div class="container__child-img-demo">
                    <div class="container__child-img-demo__img img-modify">
                        <img src="/img/chup-anh-tra-sua-concept-dep-2020_0002 (1).JPG" alt="">
                    </div>
                    <div class="container__child-img-demo__img">
                        <img src="/img/pexels-avichal-lodhi-copy.jpg" alt="">
                    </div>
                    <div class="container__child-img-demo__img" style="    transform: translate(0px, 15%);">
                        <img src="/img/pexels-blank-space-2907301.jpg" alt="">
                    </div>
                    <div class="container__child-img-demo__img img-modify">
                        <img src="/img/pexels-horizon-content-1.jpg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section id="section__info"
        style=" position: relative;
        background-repeat: no-repeat;height: 420px; background-image: url(https://preview.colorlib.com/theme/coffeeblend/images/bg_2.jpg.webp);">
        <div style="opacity: 0.5; width: 100%; height: 100%; background-color: var(--background-black);"></div>
        <div class="container">
            <ul class="container__informations">
                <li class="container__information-item">
                    <div class="container__information-item__box-icon">
                        <img src="/img/coffee-White-64px.png" alt="">
                    </div>
                    <h2 class="container__information-item__number" style="color: var(--color-title); margin: 40px 0 20px"
                        data-number="100"></h2>
                    <p class="container__information-item__text-info" style="color: #838383;">Drinks</p>
                </li>
                <li class="container__information-item">
                    <div class="container__information-item__box-icon">
                        <img src="/img/coffee-White-64px.png" alt="">
                    </div>
                    <h2 class="container__information-item__number" style="color: var(--color-title); margin: 40px 0 20px"
                        data-number="200"></h2>
                    <p class="container__information-item__text-info" style="color: #838383;">Vendors</p>
                </li>
                <li class="container__information-item">
                    <div class="container__information-item__box-icon">
                        <img src="/img/coffee-White-64px.png" alt="">
                    </div>
                    <h2 class="container__information-item__number" style="color: var(--color-title); margin: 40px 0 20px"
                        data-number="1000"></h2>
                    <p class="container__information-item__text-info" style="color: #838383;">Ordered</p>
                </li>
                <li class="container__information-item">
                    <div class="container__information-item__box-icon">
                        <img src="/img/coffee-White-64px.png" alt="">
                    </div>
                    <h2 class="container__information-item__number" style="color: var(--color-title); margin: 40px 0 20px"
                        data-number="50"></h2>
                    <p class="container__information-item__text-info" style="color: #838383;">Review</p>
                </li>
            </ul>
        </div>
    </section>
    
    <section>
        <div class="container">
            <div class="container__info">
                <div style="width: 100%; margin-bottom: 50px;">
                    <h1 style="font-family: var(--font-family-title); font-size: 40px; color: var(--color-title);">Milk
                        Tea</h1>
                    <h1 style="font-size: 40px;padding: 6px 0 50px; line-height: 0;">OUR MENU</h1>
                    <p style="color: #838383; text-align: center;" class="introduce">
                        Milk tea - with diverse flavors, the combination of fresh tea and smooth milk, along with
                        enticing toppings, creates a
                        delicious and enjoyable beverage, offering relaxation and satisfaction to the taste buds.
                    </p>
                </div>
    
                <ul class="list-type">
                    <li class="list-type__item active">Milk Tea</li>
                    <li class="list-type__item">Cream</li>
                    <li class="list-type__item">Coffee</li>
                    <li class="list-type__item">Drinks</li>
                </ul>
    
                <ul class="list-type-img">
                    <li class="list-type-img__child active"><img src="/img/milk-tea-1.jpg" alt="">
                    </li>
                    <li class="list-type-img__child active"><img src="/img/milk-tea-2.jpg" alt="">
                    </li>
                    <li class="list-type-img__child active"><img src="/img/milk-tea-3.jpg" alt="">
                    </li>
                    <li class="list-type-img__child"><img src="/img/cream-1.jpg" alt=""></li>
                    <li class="list-type-img__child"><img src="/img/cream-2.jpg" alt=""></li>
                    <li class="list-type-img__child"><img src="/img/cream-3.jpg" alt=""></li>
                    <li class="list-type-img__child"><img src="/img/cf-1.jpg" alt=""></li>
                    <li class="list-type-img__child"><img src="/img/cf-2.jpg" alt=""></li>
                    <li class="list-type-img__child"><img src="/img/cf-3.webp" alt=""></li>
                    <li class="list-type-img__child"><img src="/img/drinks-1.jpg" alt=""></li>
                    <li class="list-type-img__child"><img src="/img/drinks-2.jpg" alt=""></li>
                    <li class="list-type-img__child"><img src="/img/drinks-3.webp" alt=""></li>
                </ul>
                <button id="btn-view-menu">
                    <a href="menu/products">View This Menu</a>
                </button>
            </div>
        </div>
    </section>
    
    <section style=" position: relative;
        height: 420px; background-color: #c49b63;">
        <ul class="container" style="align-items: center; justify-content: space-around; width: 70%; margin: auto;">
            <li class="container-item" style="text-align: center; position: relative; margin: 0 30px;">
                <img src="/img/checklist.png" style="margin: 0 0 40px ;" alt="">
                <p class="container-item__text" style="color: var(--background-black); margin: 0 0 30px;">EASY TO ORDER
                </p>
                <p style="color: #323232; font-size: 16px;">Placing orders with us is incredibly easy and hassle-free,
                    ensuring a seamless shopping experience for you.</p>
            </li>
            <li class="container-item" style="text-align: center; position: relative; margin: 0 30px;">
                <img src="/img/fast-delivery.png" style="margin: 0 0 40px ;" alt="">
                <p class="container-item__text" style="color: var(--background-black); margin: 0 0 30px;">FASTEST
                    DELIVERY</p>
                <p style="color: #323232; font-size: 16px;">Our delivery service is known for its remarkable speed,
                    ensuring prompt and swift shipping of your orders to your
                    doorstep.</p>
            </li>
            <li class="container-item" style="text-align: center; position: relative; margin: 0 30px;">
                <img src="/img/shield.png" style="margin: 0 0 40px ;" alt="">
                <p class="container-item__text" style="color: var(--background-black); margin: 0 0 30px;">QUALITY
                    PRODUCTS</p>
                <p style="color: #323232; font-size: 16px;">Our products are synonymous with exceptional quality,
                    crafted with meticulous attention to detail and designed to
                    surpass your expectations.</p>
            </li>
        </ul>
    </section>
@endpush

@section('footer')
    @parent
@endsection

@section('scripts')
    @push('js')
        <script>
            handleScroll({
                menu: document.querySelector('menu'),
                background: document.querySelector('#section__info'),
            })
    
            const listNumbers = document.querySelectorAll('.container__information-item__number')
    
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting && entry.target.id === 'section__info') {
                        listNumbers.forEach(function (item) {
                            handleNumbers(item, 'data-number');
                        });
                        observer.disconnect();
                    }
                });
            });
    
            var targetElement = document.getElementById('section__info');
            observer.observe(targetElement);
    
    
            handlePagination({
                card_type: '.container__info .list-type .list-type__item',
                card_type_img: '.container__info .list-type-img .list-type-img__child',
                quantity: 3,
            })
        </script>
    @endpush
    @parent
@endsection