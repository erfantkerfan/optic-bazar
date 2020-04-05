<?php use Illuminate\Support\Facades\Input; ?>

<div id="load_seller" style="position: relative;">
    <div class="row">

        <div class="col-xs-12">
            <div class="row">

                <div class="col-md-2 col-xs-6 is_product">
                    <div class="product_list">
                        <div data-priduct="">
                            <img src="{!! asset('assets/images/no_image.png') !!}">
                            <span class="name">به انتخاب سامانه</span>
                        </div>
                        <div class="">
                            <a class="select_seller" href="#" data-key="auto">انتخاب</a>
                        </div>
                    </div>
                </div>

                @if($seller)

                    @foreach($seller as $k => $v)

                        <div class="col-md-2 col-xs-6 is_product">
                            <div class="product_list">
                                <div data-priduct="">
                                    <img src="{!! ($v->image) ? url($v->image) : asset('assets/images/no_image.png') !!}">

                                    <span class="name">{{ $v->name }}</span>
                                </div>
                                <div class="">
                                    <a class="select_seller" href="#" data-key="{{ $v->id }}">انتخاب عامل</a>
                                </div>
                            </div>
                        </div>

                    @endforeach

                @endif

            </div>
            <div class="clearfix"></div>
            {!! $seller->appends(Input::except('page'))->links() !!}
        </div>
    </div>
</div>
