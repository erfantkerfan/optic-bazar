<?php use Illuminate\Support\Facades\Input; ?>

<div id="load_seller" style="position: relative;">
    <div class="row">

        <div class="col-xs-12">
            <div class="row">

                <?php $labratorUser = array(); ?>
                @if($labrator)

                    @foreach($labrator as $k => $v)

                        @if(!in_array($v->id, $labratorUser))

                            <?php $labratorUser[] = $v->id ?>
                            <div class="col-md-3 col-xs-12" style="margin-bottom: 20px">
                                <div class="product_list">
                                    <div data-priduct="">
                                        <img src="{!! ($v->image) ? url($v->image) : asset('assets/images/no_image.png') !!}">

                                        <span class="name">{{ $v->name }}</span>
                                    </div>
                                    <div class="box_data">
                                        <div class="header_list">
                                            <div class="col-xs-6">خدمات لابراتور</div>
                                            <div class="col-xs-6">قیمت</div>
                                            <div class="clearfix"></div>
                                        </div>
                                        @foreach($labrator as $count => $detail)
                                            @if($v->id == $detail->id)
                                                <div class="item_list">
                                                    <div class="col-xs-6">

                                                        <div class="checkbox info">
                                                            <input type="checkbox" id="service_{{ $count }}" name="service[]" value="{{ $detail->services_id }}">
                                                            <label for="service_{{ $count }}">{{ $detail->title }}</label>
                                                        </div>

                                                    </div>
                                                    <div class="col-xs-6">{{ number_format($detail->price) }} تومان</div>
                                                    <div class="clearfix"></div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="">
                                        <a class="select_labrator" href="#" data-key="{{ $v->id }}">انتخاب و ادامه</a>
                                    </div>
                                </div>
                            </div>

                        @endif

                    @endforeach

                @endif

                <div class="col-xs-12 buttomSend">
                    <a href="#" id="no_service" class="btn_no_service">نیاز به خدمات اضافی نیست</a>
                </div>
            </div>
            <div class="clearfix"></div>
            {!! $labrator->appends(Input::except('page'))->links() !!}
        </div>
    </div>
</div>
