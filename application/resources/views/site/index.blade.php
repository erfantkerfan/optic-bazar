@extends('site.layouts.app')

@section('content')

    <?php use \App\Http\Controllers\SettingController; ?>

    <div class="zm_slider">
        @if($slider)
            @foreach($slider as $item)
                <div class="item">

                    @if($item['video'])
                        <div class="main_slider_video">
                            <video autoplay loop>
                                <source src="{{ $item['video'] }}" type="video/mp4">
                            </video>
                        </div>
                    @else
                        <img src="{{ $item['image'] }}">
                    @endif

                    <div class="box_slide hb-inner">
                        <div class="container">
                            <h1>{{ $item['name'] }}</h1>
                            <p>{{ $item['description'] }}</p>
                            @if($item['link'])
                                <a href="{{ $item['link'] }}">اطلاعات بیشتر</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <div class="category-section">
        <div class="container">
            <h2 class="title">برند ها</h2>
            @if($brands)
                <div class="tab_brand_controll">
                    <ul>
                        @if($brands['optical'])
                            <li class="active"><a href="#optical_brand">عدسی ها</a></li>
                        @endif
                        @if($brands['lens'])
                            <li class="{{ ($brands['optical']) ? '' : 'active' }}"><a href="#lens_brand">لنز ها</a></li>
                        @endif
                    </ul>
                </div>
                <div class="tab_brand_main">
                    <div class="tab_brand {{ ($brands['optical']) ? '' : 'active' }}" id="lens_brand">

                        <div class="zm_slider_items">
                            <?php $number = 0; ?>
                            @foreach($brands['lens'] as $key => $brand)

                                @if($number == 0)
                                    <div class="item">
                                        <ul class="category-icon" id="home_categories">
                                @endif

                                        <li class="havechildren" data-id="190" style="display: inline-block;">
                                            <a href="{{ url('brand/lens/' . urlencode($brand->name)) }}">
                                                <span class="icon"><img src="{{ $brand->logo }}" width="40" height="40"></span>
                                                <span class="text">{{ $brand->name }}</span>
                                            </a>
                                        </li>

                                @if($number == 17)
                                        </ul>
                                    </div>
                                @endif
                                <?php
                                    $number++;
                                    if($number == 18){
                                        $number = 0;
                                    }
                                ?>

                            @endforeach
                            @if($number != 18)
                                    </ul>
                            </div>
                            @endif
                        </div>

                    </div>
                    <div class="tab_brand {{ ($brands['optical']) ? 'active' : '' }}" id="optical_brand">

                        <div class="zm_slider_items">
                            <?php $number = 0; ?>
                            @foreach($brands['optical'] as $key => $brand)

                                @if($number == 0)
                                    <div class="item">
                                        <ul class="category-icon" id="home_categories">
                                @endif

                                            <li class="havechildren" data-id="190" style="display: inline-block;">
                                                <a href="{{ url('brand/optical/' . urlencode($brand->name)) }}">
                                                    <span class="icon"><img src="{{ $brand->logo }}" width="40" height="40"></span>
                                                    <span class="text">{{ $brand->name }}</span>
                                                </a>
                                            </li>

                                @if($number == 17)
                                        </ul>
                                    </div>
                                @endif
                                <?php
                                $number++;
                                if($number == 18){
                                    $number = 0;
                                }
                                ?>

                            @endforeach
                            @if($number != 18)
                                </ul>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="zm_baner_index">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="box_baner">
                        <img src="{!! asset('assets/images/optic_ct_hom.png') !!}">
                        <div class="hb-inner">
                            <div class="container_baner">
                                <h1 style="color: #000;">عدسی ها</h1>
                                <a href="{{ url('/products/optical') }}">مشاهده عدسی ها</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="box_baner">
                        <img src="{!! asset('assets/images/lenz_ct_hom.png') !!}">
                        <div class="hb-inner">
                            <div class="container_baner">
                                <h1 style="color: #000;">لنز ها</h1>
                                <a href="{{ url('/products/lens') }}">مشاهده لنز ها</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="zm_about_index">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-8">
                    <h1>{{ SettingController::get_package_optien('title_about') }}</h1>
                    <p>{!! SettingController::get_package_optien('text_about') !!} </p>
                </div>
                <div class="col-xs-12 col-md-4 hidden-xs hidden-sm">
                    <div class="flat_image">
                        <img src="{!! asset('assets/images/optometry.png') !!}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="zm_baner_center" style="background-image: url('{!! asset('assets/images/home-banner1-2.png') !!}')">

        <div class="box_slide hb-inner">
            <div class="container">
                <h1>{{ SettingController::get_package_optien('title_baner_order') }}</h1>
                <p>{!! SettingController::get_package_optien('text_baner_order') !!} </p>
                <a href="{{ url('order') }}">ایجاد سفارش جدید</a>
            </div>
        </div>

    </div>

    <div class="zm_about_index">
    <div class="container main_product">
        @if($lensOffer)
        <div class="product_archiv">
            <h2 class="title">پرتخفیف ترین لنز ها</h2>
            <div class="zm_slider_product">
                @foreach($lensOffer as $len)
                <div class="item">
                    <div class="product_list">
                        <a href="{{ url('product/' . $len->id . '/' . strtolower(str_replace(' ', '-', $len->name) . '-' . $len->sku)) }}">
                            <img src="{!! ($len->image) ? url($len->image) : asset('assets/images/no_image.png') !!}">
                            <span class="name">{{ $len->name . ' - ' . $len->sku }}</span>
                            @if(Auth::user() && Auth::user()->status == "active")
                                <span class="price">{!! ($len->original_price != $len->price) ? '<span class="original_price">'.number_format($len->original_price). ' تومان </span>' . '<span class="final_price">'.number_format($len->price). ' تومان </span>' : number_format($len->price) . ' تومان' !!}</span>
                            @endif
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($opticalOffer)
            <div class="product_archiv">
                <h2 class="title"> پرتخفیف ترین عدسی ها</h2>
                <div class="zm_slider_product">
                    @foreach($opticalOffer as $optic)
                        <div class="item">
                            <div class="product_list">
                                <a href="{{ url('product/' . $optic->id . '/' . strtolower(str_replace(' ', '-', $optic->name) . '-' . $optic->sku)) }}">
                                    <img src="{!! ($optic->image) ? url($optic->image) : asset('assets/images/no_image.png') !!}">
                                    <span class="name">{{ $optic->name . ' - ' . $optic->sku }}</span>
                                    @if(Auth::user() && Auth::user()->status == "active")
                                        <span class="price">{!! ($optic->original_price != $optic->price) ? '<span class="original_price">'.number_format($optic->original_price). ' تومان </span>' . '<span class="final_price">'.number_format($optic->price). ' تومان </span>' : number_format($optic->price) . ' تومان' !!}</span>
                                    @endif
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    </div>

    <div class="container main_product">
        @if($lens)
        <div class="product_archiv">
            <h2 class="title">جدید ترین لنز ها</h2>
            <div class="zm_slider_product">
                @foreach($lens as $len)
                <div class="item">
                    <div class="product_list">
                        <a href="{{ url('product/' . $len->id . '/' . strtolower(str_replace(' ', '-', $len->name) . '-' . $len->sku)) }}">
                            <img src="{!! ($len->image) ? url($len->image) : asset('assets/images/no_image.png') !!}">
                            <span class="name">{{ $len->name . ' - ' . $len->sku }}</span>
                            @if(Auth::user() && Auth::user()->status == "active")
                                <span class="price">{!! ($len->original_price != $len->price) ? '<span class="original_price">'.number_format($len->original_price). ' تومان </span>' . '<span class="final_price">'.number_format($len->price). ' تومان </span>' : number_format($len->price) . ' تومان' !!}</span>
                            @endif
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($optical)
            <div class="product_archiv">
                <h2 class="title">جدید ترین عدسی ها</h2>
                <div class="zm_slider_product">
                    @foreach($optical as $optic)
                        <div class="item">
                            <div class="product_list">
                                <a href="{{ url('product/' . $optic->id . '/' . strtolower(str_replace(' ', '-', $optic->name) . '-' . $optic->sku)) }}">
                                    <img src="{!! ($optic->image) ? url($optic->image) : asset('assets/images/no_image.png') !!}">
                                    <span class="name">{{ $optic->name . ' - ' . $optic->sku }}</span>
                                    @if(Auth::user() && Auth::user()->status == "active")
                                    <span class="price">{!! ($optic->original_price != $optic->price) ? '<span class="original_price">'.number_format($optic->original_price). ' تومان </span>' . '<span class="final_price">'.number_format($optic->price). ' تومان </span>' : number_format($optic->price) . ' تومان' !!}</span>
                                    @endif
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="zm_baner_index">
        <div class="container">
            <div class="row">

                @if($page)
                    @foreach($page as $item)
                        <div class="col-xs-12 col-md-6">
                            <div class="box_baner">
                                <img src="{!! $item['image'] !!}">
                                <div class="hb-inner">
                                    <div class="container_baner">
                                        <h1>{{ $item['title'] }}</h1>
                                        <a href="{{ url('post/' . $item['slug']) }}">اطلاعات بیشتر</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
        </div>
    </div>

@endsection
