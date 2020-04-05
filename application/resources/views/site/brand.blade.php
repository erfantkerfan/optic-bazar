@extends('site.layouts.app')

@section('content')

    <div class="box_color_pages">
        <div class="product_page_list">
            <div class="container">

                <div class="col-xs-12 col-md-3">
                    <div class="filter_product">
                        <h3>جستجو محصولات</h3>
                        <div class="main_filter filters-panel">
                            <form class="controll_filter">

                                @foreach($filters as $fl)
                                    <div class="filter">
                                        <label class="filter__header">{{ $fl['title'] }}</label>
                                        <div class="filter__items">
                                            @if($fl['type'] == 'select')
                                                <select id="filter_{{ $fl['name'] }}" class="form-control" name="filter_{{ $fl['name'] }}">
                                                    <option value="">لطفا انتخاب کنید</option>
                                                    @foreach($fl['value'] as $c)
                                                        @switch($fl['name'])
                                                            @case('country')
                                                            <option value="{{ $c['title'] }}" {{ (isset($FillterAcrive['filter_' . $fl['name']]) && $FillterAcrive['filter_' . $fl['name']] == $c['title']) ? 'selected' : '' }}>{{ $c['title'] }}</option>
                                                            @break
                                                            @case('brand')
                                                            <option value="{{ $c['name'] }}" {{ (isset($FillterAcrive['filter_' . $fl['name']]) && $FillterAcrive['filter_' . $fl['name']] == $c['name']) ? 'selected' : '' }}>{{ $c['name'] }}</option>
                                                            @break
                                                            @default
                                                            <option value="{{ $c }}" {{ (isset($FillterAcrive['filter_' . $fl['name']]) && $FillterAcrive['filter_' . $fl['name']] == $c) ? 'selected' : '' }}>{{ $c }}</option>
                                                            @break
                                                        @endswitch
                                                    @endforeach
                                                </select>
                                            @elseif($fl['type'] == 'number')

                                                <div class="slider_min_max">
                                                    <input type="text" class="amount amount_{{ $fl['name'] }}" readonly style="border:0; color:#f6931f; font-weight:bold;">
                                                    <input type="hidden" class="min min_filter_{{ $fl['name'] }}" name="min_filter_{{ $fl['name'] }}" data-default="{{ (isset($FillterAcrive['min_filter_' . $fl['name']]) && $FillterAcrive['min_filter_' . $fl['name']]) ? $FillterAcrive['min_filter_' . $fl['name']] : '0' }}">
                                                    <input type="hidden" class="max max_filter_{{ $fl['name'] }}" name="max_filter_{{ $fl['name'] }}" data-default="{{ (isset($FillterAcrive['min_filter_' . $fl['name']]) && $FillterAcrive['min_filter_' . $fl['name']]) ? $FillterAcrive['min_filter_' . $fl['name']] : $fl['value'] }}">
                                                    <div class="slider-range" id="slider-range-{{ $fl['name'] }}"></div>
                                                </div>


                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                <div class="button_search_pro">
                                    <button type="submit" id="filter_product" class="btn btn-info btn-block">جستجو</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-9">
                    @if(count($product) > 0)

                        <div class="row">

                            @foreach($product as $k => $v)

                                <div class="col-md-3 col-xs-6 is_product">
                                    <div class="product_list">
                                        <div data-priduct="">
                                            <a href="{{ url('product/' . $v['id'] . '/' . strtolower(str_replace(' ', '-', $v['name'] . '-' . $v['sku']))) }}" target="_blank">
                                                <img src="{!! ($v['image']) ? url($v['image']) : asset('assets/images/no_image.png') !!}">

                                                <span style="min-height: 40px;" class="name">{{ $v['name'] . ' - ' . $v['sku'] }}</span>

                                                @guest
                                                @else
                                                    <span class="price">{!! ($v['original_price'] != $v['price']) ? '<span class="original_price">'.number_format($v['original_price']). ' تومان </span>' . '<span class="final_price">'.number_format($v['price']). ' تومان </span>' : number_format($v['price']) . ' تومان' !!}</span>
                                                @endguest

                                            </a>
                                        </div>
                                        <div class="box_ad">
                                            @guest
                                            @else
                                                <a class="addtofav" href="{{ url('order/add-to-fav/' . $v->id) }}"><i class="fa fa-star-o"></i></a>
                                            @endguest
                                            <a class="add_to_card" href="{{ url('order/new/') }}">افزوردن به سبد خرید</a>
                                        </div>
                                    </div>
                                </div>

                            @endforeach

                        </div>
                        <div class="clearfix"></div>

                    @else
                        <div class="msg">
                            <div class="alert alert-warning" style="text-align: center">محصولی یافت نشد.</div>
                        </div>
                    @endif
                </div>


                <div class="clearfix"></div>
            </div>
        </div>
    </div>

@endsection
