<?php use Illuminate\Support\Facades\Input; ?>

<div id="load" style="position: relative;">
    <div class="row">

        <input type="hidden" id="filterSages" value="{{ $filterSages }}" >

        <div class="col-xs-12">
            <div class="search_fave">
                <div class="row">
                    <div class="col-xs-12 col-md-7">
                        <div class="row">
                            <div class="col-xs-9">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="search_name" id="search_name" value="{{ @$_GET['search_name'] }}" placeholder="جستجو نام محصول">
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <button type="submit" id="search_product" class="btn btn-info" style="line-height: 24px;width: 100%;">جستجو</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-5">

                        <div class="row">
                            <div class="col-xs-4">
                            </div>
                            <div class="col-xs-4">
                                <a class="dropdown-toggle buttom-select-fav" href="#" id="fav_product">  محصولات منتخب </a>
                            </div>
                            <div class="col-xs-4">
                                <a class="dropdown-toggle buttom-select-fav all" href="#" id="show_all">  مشاهده همه </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            @if($activeFillter)
                <div class="zm-active-fillter"></div>
            @endif

        </div>

        <div class="col-xs-12">
            <div class="zm_blank_hight"></div>
        </div>

        <div class="col-xs-12 col-md-3">
            <div class="filter_product">
                <h3>جستجو محصولات</h3>
                <div class="main_filter filters-panel">
                    <form class="controll_filter" action="{{ url('order/new/get_products') }}">

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
            <div class="row">

                @if(count($product) > 0)

                    @foreach($product as $k => $v)

                        <div class="col-md-3 col-xs-6 is_product">
                            <div class="product_list">
                                <div data-priduct="">
                                    <a href="{{ url('product/' . $v['id'] . '/' . strtolower(str_replace(' ', '-', $v['name'] . '-' . $v['sku']))) }}" target="_blank">
                                        <img src="{!! ($v['image']) ? url($v['image']) : asset('assets/images/no_image.png') !!}">

                                        <span style="min-height: 40px;" class="name">{{ $v['name'] . ' - ' . $v['sku'] }}</span>
                                        <span class="price">{!! ($v['original_price'] != $v['price']) ? '<span class="original_price">'.number_format($v['original_price']). ' تومان </span>' . '<span class="final_price">'.number_format($v['price']). ' تومان </span>' : number_format($v['price']) . ' تومان' !!}</span>
                                    </a>
                                </div>
                                <div class="">
                                    <a class="addtofav {{ ($favoriteList[$k] == 'active') ? "active" : "" }}" href="{{ url('order/add-to-fav/' . $v['id']) }}" data-key="{{ $v['id'] }}" data-detail=""><i class="fa {{ ($favoriteList[$k] == 'active') ? "fa-star" : "fa-star-o" }}"></i></a>
                                    <a class="add_to_card {{ ($followUpProduct) ? "active" : "" }} " href="{{ url('order/new/add-to-card/' . $v['id']) }}">افزوردن به سبد خرید</a>
                                </div>
                            </div>
                        </div>

                    @endforeach

                @else
                    <div class="msg">
                        <div class="alert alert-warning" style="text-align: center">محصولی یافت نشد.</div>
                    </div>
                @endif

            </div>
            <div class="clearfix"></div>
            {{--{!! $product->appends(Input::except('page'))->links() !!}--}}
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function ErrorAddtofav() {
            $.toast({
                heading: 'خطا در ثبت محصول منتخب.',
                position: 'bottom-right',
                loaderBg:'#862e15',
                bgColor: '#e74e1d',
                textColor: '#fff',
                hideAfter: 5000,
                stack: 6
            });
        }


        $(document).delegate('.addtofav', 'click', function (e) {
            e.preventDefault();

            var product_id = $(this).attr('data-key');
            var detail_id = $(this).attr('data-detail');
            var thisAction = $(this);
            if(!$(this).hasClass('active')){

                $(this).attr("disabled", 'disabled');
                $.ajax({
                    method: 'POST',
                    url: '{{ url('order/add-to-fav/add') }}',
                    data: {product_id : product_id, detail_id : detail_id}
                }).done(function(result){
                    if(result === 'true'){
                        thisAction.removeAttr("disabled").addClass('active').find('i').attr('class', 'fa fa-star');
                    }else{
                        ErrorAddtofav();
                    }
                });

            }else{

                $(this).attr("disabled", 'disabled');
                $.ajax({
                    method: 'POST',
                    url: '{{ url('order/add-to-fav/remove') }}',
                    data: {product_id : product_id, detail_id : detail_id}
                }).done(function(result){
                    if(result === 'true'){
                        thisAction.removeAttr("disabled").removeClass('active').find('i').attr('class', 'fa fa-star-o');
                    }else{
                        ErrorAddtofav();
                    }
                });

            }

        });

    })
</script>
