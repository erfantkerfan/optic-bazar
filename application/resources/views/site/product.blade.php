@extends('site.layouts.app')

@section('content')

    <div class="box_color_pages">
        <div class="product_page_list">
            <div class="container">

                <div class="row">
                    <div class="col-xs-12 col-md-5">

                        <div class="zm_slider">
                            @if(json_decode($request->gallery))
                                @if($request->image)
                                <div class="item">
                                    <div class="e-card-img-container" style="background-image: url('{{ url($request->image) }}')">
                                        <img src="{!! url($request->image) !!}" class="hidden">
                                    </div>
                                </div>
                                @endif
                                @foreach(json_decode($request->gallery) as $item)
                                    <div class="item">
                                        <div class="e-card-img-container" style="background-image: url('{{ $item }}')">
                                            <img src="{!! $item !!}" class="hidden">
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="item">
                                    <div class="e-card-img-container" style="background-image: url('{!! ($request->image) ? url($request->image) : asset('assets/images/no_image.png') !!}')">
                                        <img src="{!! ($request->image) ? url($request->image) : asset('assets/images/no_image.png') !!}" class="hidden">
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                    <div class="col-xs-12 col-md-7">
                        <div class="single_product">
                            <h1>{{ $request->name . ' - ' . $request->sku }}</h1>
                            <div class="price_box">
                                <span class="labal_parice">قیمت : </span>
                                @if(Auth::user() && Auth::user()->status == "active")
                                    <span class="price">{!! ($requestFull->original_price != $requestFull->price) ? '<span class="original_price">'.number_format($requestFull->original_price). ' تومان </span>' . '<span class="final_price">'.number_format($requestFull->price). ' تومان </span>' : number_format($requestFull->price) . ' تومان' !!}</span>
                                @endif
                            </div>

                            @if($request->description)
                                <div class="description">
                                    <div class="title_des">توضیحات</div>
                                    <div class="main_des">
                                        {!! $request->description !!}
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="single_product">
                            <div class="attr">
                                <div class="title_attr">مشخصات محصول</div>
                                <div class="main_attr">
                                    <div class="row">
                                        @if($request->type == 1)

                                            <div class="col-xs-12 col-md-4 title_dtt">کشور سازنده</div><div class="col-xs-12 col-md-8 value_att">{{ $country->title }}</div>
                                            <div class="clearfix"></div>

                                            <div class="col-xs-12 col-md-4 title_dtt">برند</div><div class="col-xs-12 col-md-8 value_att">{{ $request->name }}</div>
                                            <div class="clearfix"></div>

                                            @if($requestFull->curvature)
                                                <div class="col-xs-12 col-md-4 title_dtt">انحنا لنز</div><div class="col-xs-12 col-md-8 value_att">{{ $requestFull->curvature }}</div>
                                                <div class="clearfix"></div>
                                            @endif

                                            @if($requestFull->structure)
                                                <div class="col-xs-12 col-md-4 title_dtt">ساختار</div><div class="col-xs-12 col-md-8 value_att">{{ $requestFull->structure }}</div>
                                                <div class="clearfix"></div>
                                            @endif

                                            @if($requestFull->consumption_period)
                                                <div class="col-xs-12 col-md-4 title_dtt">دوره مصرف</div><div class="col-xs-12 col-md-8 value_att">{{ $requestFull->consumption_period }}</div>
                                                <div class="clearfix"></div>
                                            @endif

                                            @if($requestFull->color)
                                                <div class="col-xs-12 col-md-4 title_dtt">نام رنگ</div><div class="col-xs-12 col-md-8 value_att">{{ $requestFull->color }}</div>
                                                <div class="clearfix"></div>
                                            @endif

                                            @if($requestFull->color_description)
                                                <div class="col-xs-12 col-md-4 title_dtt">جزئیات رنگ</div><div class="col-xs-12 col-md-8 value_att">{{ $requestFull->color_description }}</div>
                                                <div class="clearfix"></div>
                                            @endif

                                            @if($requestFull->number)
                                                <div class="col-xs-12 col-md-4 title_dtt">موجودی در هر بسته</div><div class="col-xs-12 col-md-8 value_att">{{ $requestFull->number }}</div>
                                                <div class="clearfix"></div>
                                            @endif

                                            @if($requestFull->astigmatism)
                                                <div class="col-xs-12 col-md-4 title_dtt">درچه استیگمات</div><div class="col-xs-12 col-md-8 value_att">{{ $requestFull->astigmatism }}</div>
                                                <div class="clearfix"></div>
                                            @endif

                                            @if($requestFull->thickness)
                                                <div class="col-xs-12 col-md-4 title_dtt">ضخامت مرکزی</div><div class="col-xs-12 col-md-8 value_att">{{ $requestFull->thickness }}</div>
                                                <div class="clearfix"></div>
                                            @endif

                                            @if($requestFull->abatement)
                                                <div class="col-xs-12 col-md-4 title_dtt">اب رسانی</div><div class="col-xs-12 col-md-8 value_att">{{ $requestFull->abatement }}</div>
                                                <div class="clearfix"></div>
                                            @endif

                                            @if($requestFull->oxygen_supply)
                                                <div class="col-xs-12 col-md-4 title_dtt">اکسیژن رسانی</div><div class="col-xs-12 col-md-8 value_att">{{ $requestFull->oxygen_supply }}</div>
                                                <div class="clearfix"></div>
                                            @endif



                                        @elseif($request->type == 2)

                                            <div class="col-xs-12 col-md-4 title_dtt">کشور سازنده</div><div class="col-xs-12 col-md-8 value_att">{{ $country->title }}</div>
                                            <div class="clearfix"></div>

                                            <div class="col-xs-12 col-md-4 title_dtt">برند</div><div class="col-xs-12 col-md-8 value_att">{{ $request->name }}</div>
                                            <div class="clearfix"></div>

                                            @if($requestFull->type)
                                            <div class="col-xs-12 col-md-4 title_dtt">نوع</div><div class="col-xs-12 col-md-8 value_att">{{ $requestFull->type }}</div>
                                            <div class="clearfix"></div>
                                            @endif

                                            @if($requestFull->refractive_index)
                                            <div class="col-xs-12 col-md-4 title_dtt">ضریب شکست</div><div class="col-xs-12 col-md-8 value_att">{{ $requestFull->refractive_index }}</div>
                                            <div class="clearfix"></div>
                                            @endif

                                            @if($requestFull->photo_color)
                                            <div class="col-xs-12 col-md-4 title_dtt">رنگ فتو</div><div class="col-xs-12 col-md-8 value_att">{{ $requestFull->photo_color }}</div>
                                            <div class="clearfix"></div>
                                            @endif

                                            @if($requestFull->anti_reflex_color)
                                            <div class="col-xs-12 col-md-4 title_dtt">رنگ انتی رفلکس</div><div class="col-xs-12 col-md-8 value_att">{{ $requestFull->anti_reflex_color }}</div>
                                            <div class="clearfix"></div>
                                            @endif

                                            @if($requestFull->block)
                                            <div class="col-xs-12 col-md-4 title_dtt">بلوکات</div><div class="col-xs-12 col-md-8 value_att">{{ $requestFull->block }}</div>
                                            <div class="clearfix"></div>
                                            @endif

                                            @if($requestFull->bloc_troll)
                                            <div class="col-xs-12 col-md-4 title_dtt">بلوکنترول</div><div class="col-xs-12 col-md-8 value_att">{{ $requestFull->bloc_troll }}</div>
                                            <div class="clearfix"></div>
                                            @endif

                                            @if($requestFull->photocrophy)
                                            <div class="col-xs-12 col-md-4 title_dtt">فتوکروم</div><div class="col-xs-12 col-md-8 value_att">{{ $requestFull->photocrophy }}</div>
                                            <div class="clearfix"></div>
                                            @endif

                                            @if($requestFull->polycarbonate)
                                            <div class="col-xs-12 col-md-4 title_dtt">پلی کربنات</div><div class="col-xs-12 col-md-8 value_att">{{ $requestFull->polycarbonate }}</div>
                                            <div class="clearfix"></div>
                                            @endif

                                            @if($requestFull->poly_break)
                                            <div class="col-xs-12 col-md-4 title_dtt">نشکن</div><div class="col-xs-12 col-md-8 value_att">{{ $requestFull->poly_break }}</div>
                                            <div class="clearfix"></div>
                                            @endif

                                            @if($requestFull->color_white)
                                            <div class="col-xs-12 col-md-4 title_dtt">سفید قابل رنگ</div><div class="col-xs-12 col-md-8 value_att">{{ $requestFull->color_white }}</div>
                                            <div class="clearfix"></div>
                                            @endif

                                            @if($requestFull->colored_score)
                                            <div class="col-xs-12 col-md-4 title_dtt">رنگی طبی </div><div class="col-xs-12 col-md-8 value_att">{{ $requestFull->colored_score }}</div>
                                            <div class="clearfix"></div>
                                            @endif

                                            @if($requestFull->watering)
                                            <div class="col-xs-12 col-md-4 title_dtt">آب گریزی</div><div class="col-xs-12 col-md-8 value_att">{{ $requestFull->watering }}</div>
                                            <div class="clearfix"></div>
                                            @endif

                                            @if($requestFull->structure)
                                            <div class="col-xs-12 col-md-4 title_dtt">ساختار</div><div class="col-xs-12 col-md-8 value_att">{{ $requestFull->structure }}</div>
                                            <div class="clearfix"></div>
                                            @endif

                                            @if($requestFull->yu_vie)
                                            <div class="col-xs-12 col-md-4 title_dtt">یو وی</div><div class="col-xs-12 col-md-8 value_att">{{ $requestFull->yu_vie }}</div>
                                            <div class="clearfix"></div>
                                            @endif

                                            @if($requestFull->property)
                                            <div class="col-xs-12 col-md-4 title_dtt">ویژگی</div><div class="col-xs-12 col-md-8 value_att">{{ $requestFull->property }}</div>
                                            <div class="clearfix"></div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
            </div>
        </div>
    </div>

@endsection
