@extends('frame')

@section('title', 'Menu')

@push('style')
    <link rel="stylesheet" href="/css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('menu')
@parent
@endsection

@section('slider')
@endsection
@push('banner')
    <div class="slider">
        <div class="slider__slide slider__slide--active" data-slide="1">
            <div class="slider__wrap">
                <div class="slider__back"></div>
            </div>
            <div class="slider__inner">
                <div class="slider__content">
                    <h1 style="font-family: var(--font-family-title); color: var(--color-title);">Menu
                    </h1><a href="#order" class="go-to-next">Order Now</a>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('body')
    <section id="order">
        <div class="container" style="justify-items: center; display: grid; justify-content: inherit;">
            <ul class="container__child-product">
                @if (!empty($products) && isset($products[0]))
                    @foreach ($products[0] as $key => $product)
                        <li class="container__child-product__item">
                            <a href="{{$url_web}}/menu/products/product/{{str_replace(' ', '-', $product['name'])}}?product={{$product['id']}}" style="display: block; height: 100%;">
                                <div style="height: 270px; overflow: hidden;">
                                    <p class="container__child-product__item-img" style="background-image: url({{$product['image']}})"></p>
                                </div>
                                <div class="container__child-product__item-description">
                                <h2
                                    style="margin: 18px 0; font-size: 18px; text-overflow: ellipsis; overflow: hidden; -webkit-box-orient: vertical; display: -webkit-box; -webkit-line-clamp: 2;">
                                {{$product['name']}}</h2>
                                <p
                                    style="color: var(--color-light); text-overflow: ellipsis; overflow: hidden; -webkit-box-orient: vertical; display: -webkit-box; -webkit-line-clamp: 2;">
                                {{$product['shop']['address']}}</p>
                                <div class="description__price-total" style="color: var(--color-light);">
                                    <p class="price" style="color: var(--color-light);">{{ $product['prices'][count($product['prices']) - 1]['price'] }}$
                                        </p>
                                    <p class="total" style="color: var(--color-light);">{{$product['sold'][0]['total']}} Sold</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
            <!-- Pagination -->
            <ul class="container__child-pagination">
                <li class="container__child-pagination__item">
                   <i class="fa-solid fa-chevron-left btn-prev"></i>
               </li>
                @php
                    foreach ($products[1] as $key => $product) {
                        $total_pages = ceil($product['count(id)'] / 12);
                    }

                    $current_page = $products[2];
                @endphp
                @for ($i = 1; $i < $total_pages; $i++)
                    @if ($current_page == $i)
                        <li data-active-page="{{$i}}" class="container__child-pagination__item active">{{$i}}</li>
                    @elseif ($current_page <= 4)
                        <li data-active-page="{{$i}}" class="container__child-pagination__item">{{$i}}</li>
                        @if ($i >= 4)
                            <li data-active-page="{{$i}}" class="container__child-pagination__item __other"></li>
                            @break
                        @endif
                    @elseif ($current_page > 4)
                        @if ($i >= 3 && $i < $current_page)
                            @if ($i == 3)
                                <li data-active-page="" class="container__child-pagination__item __other"></li>
                            @endif
                            @continue
                        @elseif ($i <= $total_pages && ($i > ($current_page - 3) && $i < $current_page))
                            <li data-active-page="" class="container__child-pagination__item __other"></li>
                        @elseif ($i <= $total_pages && $i == $current_page)
                            <li data-active-page="{{$i}}" class="container__child-pagination__item active">{{$i}}</li>
                        @elseif ($i <= $total_pages && ($i < ($current_page + 3)))
                            <li data-active-page="{{$i}}" class="container__child-pagination__item">{{$i}}</li>
                        @else
                            @if ($current_page != $total_pages)
                                <li data-active-page="" class="container__child-pagination__item __other"></li>
                            @endif
                            @break
                        @endif
                    @endif
                @endfor
                <li class="container__child-pagination__item">
                    <i class="fa-solid fa-chevron-right btn-next"></i>
                </li>
            </ul>
        </div>
    </section>
@endpush

@section('footer')
@parent
@endsection

@section('scripts')
@push('js')
<script src="/js/menu.js"></script>
<script>
    handleScroll({
        menu: document.querySelector('menu'),
        background: document.querySelector('#section__info'),
    })

    handlePagination({
        cardTypeParent: '.container__child-pagination',
        card_type: '.container__child-pagination__item',
        card_type_img: '.container__child-product__item',
        quantity: 12,
        attributes: 'data-active-page',
        totalPage: @json($total_pages),
        event: {
            next: '.container__child-pagination__item .btn-next',
            prev: '.container__child-pagination__item .btn-prev',
        },
        handleMovePage: function(data) {
            window.location.href = `/menu/products?page=${data.nextPage}&per_page=${data.perPage}`;
        }
    })
</script>
@endpush
@parent
@endsection
