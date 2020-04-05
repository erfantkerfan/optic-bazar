@extends('bonakdar.layouts.app')

@section('page_name', 'سفارشات')

@section('content')
    <div class="white-box">
        <div class="portlet-body">
            <div class="clearfix"></div>
            <br>

            <form class="filter_list" method="get" style="direction: rtl">

                <div class="col-md-3 col-xs-12">
                    <div class="form-group">
                        <label for="filter_code">شماره فاکتور</label>
                        <input type="text" class="form-control" id="filter_code" name="filter_code" value="{{ @$_GET['filter_code'] }}">
                    </div>
                </div>
                <div class="col-md-3 col-xs-12">
                    <div class="form-group">
                        <label for="filter_start_date">از تاریخ</label>
                        <input type="text" class="form-control observer" id="filter_start_date" name="filter_start_date" value="{{ @$_GET['filter_start_date'] }}">
                    </div>
                </div>
                <div class="col-md-3 col-xs-12">
                    <div class="form-group">
                        <label for="filter_end_date">تا تاریخ</label>
                        <input type="text" class="form-control observer" id="filter_end_date" name="filter_end_date" value="{{ @$_GET['filter_end_date'] }}">
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-2 col-xs-12" style="padding-top: 17px;">
                    <button type="submit" class="btn btn-block btn-success btn-rounded">جستجو</button>
                </div>
                <div class="clearfix"></div>

            </form>
        </div>
    </div>

    <div class="white-box">

        <div class="portlet-body">


            @if(count($request) > 0)
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    @foreach($request as $key => $item)

                    <div class="panel panel-default rtl">
                        <div class="panel-heading" role="tab" id="heading{{ $key }}">
                            <h4 class="panel-title">
                                <a class="collapsed" role="button" data-toggle="collapse"
                                   data-parent="#accordion" href="#collapse{{ $key }}" aria-expanded="true"
                                   aria-controls="collapse<?php echo $key; ?>">
                                    <span class="item_head"><span>نام و نام خانوادگی :</span> {{ $item['user_name'] }}</span>
                                    <span class="item_head"><span>شماره موبایل :</span> {{ $item['user_mobile'] }}</span>
                                    <span class="item_head"><span>تاریخ سفارش :</span> {{ jdate('Y/m/d', strtotime($item['created_at'])) }}</span>
                                    <span class="item_head pull-right"><span>شماره فاکتور :</span><span class="number_style" style="font-family: Tahoma"> BOP-{{ $item['id'] }}</span></span>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse{{ $key }}" class="panel-collapse collapse" role="tabpanel"
                             aria-labelledby="heading{{ $key }}">
                            <div class="panel-body" style="padding: 0;">

                                <div class="zm_step_box active">
                                    <div class="main_step">

                                        <div class="clearfix"></div>

                                        @if(count($orderData[$item['id']]) > 0)

                                            <div class="box_archive_prescription">
                                                <div class="row">

                                                    @foreach($orderData[$item['id']] as $count => $v)
                                                        <div class="item_prescription open counter">
                                                            <div class="col-xs-12 col-md-12">
                                                                <div class="invite">
                                                                    <span style="min-width: auto;">سفارش {{ $count + 1 }} ) </span>
                                                                    <span>نام بیمار : {{ $v->name }}</span>
                                                                    <span>نوع نسخه : {{ ($v->type == "import") ? "تایپ شده" : "تصویر" }}</span>
                                                                    <span>نام محصول : {{ $v->product_sku }}</span>
                                                                    <span class="pull-right" style="margin-left: 0; padding-left: 0">
                                                                        <label>وضعیت :</label>
                                                                        <select class="form-control status_operator" data-id="{{ $v->order_id }}">
                                                                            <option value="0">دریافت اطلاعات سفارش</option>
                                                                            <option value="2" {{ ($v->status_operator == "2") ? "selected" : "" }}>تهیه اقلام سفارش</option>
                                                                            <option value="1" {{ ($v->status_operator == "1") ? "selected" : "" }}>انجام خدمات لابراتور</option>
                                                                            <option value="4" {{ ($v->status_operator == "4") ? "selected" : "" }}>تحویل به پیک سامانه</option>
                                                                            <option value="5" {{ ($v->status_operator == "5") ? "selected" : "" }}>تحویل به باربری</option>
                                                                            <option value="6" {{ ($v->status_operator == "6") ? "selected" : "" }}>بسته شده</option>
                                                                        </select>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-12">
                                                                <div class="full_data_prescription">
                                                                    <div class="row">

                                                                        <div class="item_full">
                                                                            <div class="col-xs-4">نوع محصول</div>
                                                                            <div class="col-xs-8">{{ ($v->type_product == "lens") ? 'لنز' : 'عدسی' }}</div>
                                                                            <div class="clearfix"></div>
                                                                        </div>

                                                                        <div class="item_full">
                                                                            <div class="col-xs-4">کد محصول</div>
                                                                            <div class="col-xs-8">{{ $v->product_sku }}</div>
                                                                            <div class="clearfix"></div>
                                                                        </div>

                                                                        <div class="item_full">
                                                                            <div class="col-xs-4">لابراتور</div>
                                                                            <?php
                                                                            $pruser= [];
                                                                            if(isset($labrator[$v->order_id])){
                                                                                $pruser = \App\User::where('id', $labrator[$v->order_id])->where('role', 'labrator')->first();
                                                                            }
                                                                            ?>
                                                                            @if($pruser)
                                                                                <div class="col-xs-8">{{ ($pruser) ? $pruser->name : 'بدون نام' }}</div>
                                                                            @else
                                                                                <div class="col-xs-8">

                                                                                    <div class="use_data">نیاز به خدمات اضافی نیست</div>

                                                                                </div>
                                                                            @endif
                                                                            <div class="clearfix"></div>
                                                                        </div>

                                                                        @if($pruser)

                                                                            <div class="item_full">
                                                                                <div class="col-xs-4">آدرس لابراتور</div>
                                                                                <div class="col-xs-8">{{ $pruser->state . ' - ' . $pruser->city . ' - ' . $pruser->address }}</div>
                                                                                <div class="clearfix"></div>
                                                                            </div>

                                                                            <div class="item_full">
                                                                                <div class="col-xs-4">شماره تماس لابراتور</div>
                                                                                <div class="col-xs-8">{{ $pruser->phone }}</div>
                                                                                <div class="clearfix"></div>
                                                                            </div>

                                                                        @endif

                                                                        <div class="item_full">
                                                                            <div class="col-xs-4">قیمت</div>
                                                                            <div class="col-xs-8">{{ number_format($v->price) }} تومان</div>
                                                                            <div class="clearfix"></div>
                                                                        </div>

                                                                        <div class="item_full">
                                                                            <div class="col-xs-4">هزینه لابراتور</div>
                                                                            <div class="col-xs-8">{{ number_format($full_labrator_price[$item['id']][$v->order_id]) }} تومان</div>
                                                                            <div class="clearfix"></div>
                                                                        </div>

                                                                        <div class="item_full">
                                                                            <div class="col-xs-4">قیمت کل</div>
                                                                            <div class="col-xs-8">{{ number_format($fullTotal[$item['id']][$v->order_id]) }} تومان</div>
                                                                            <div class="clearfix"></div>
                                                                        </div>

                                                                        @if($pruser)
                                                                        <div class="item_full">
                                                                            <div class="col-xs-4">خدمات لابراتور</div>
                                                                            <div class="col-xs-8">
                                                                                <?php $order_service = \App\Order_detail::where('order_id', $v->order_id)->where('key', 'LIKE' ,'%order_service%%title%')->get();  ?>
                                                                                @if($order_service)
                                                                                    @foreach ($order_service as $service)
                                                                                        <span class="btn btn-info btn-rounded" style="font-size: 12px; margin-left: 5px;">{{ $service['val'] }}</span>
                                                                                    @endforeach
                                                                                @endif
                                                                            </div>
                                                                            <div class="clearfix"></div>
                                                                        </div>
                                                                        @endif



                                                                        @if($v->type == 'import')

                                                                            <div class="item_full import">

                                                                                <div class="col-xs-12 col-md-6 float-left">

                                                                                    <div class="row">

                                                                                        <div class="box_add_item">
                                                                                            <div class="item">
                                                                                                <div class="col-xs-1 float-left">
                                                                                                    <label class="top_table"></label>
                                                                                                </div>
                                                                                                <div class="col-xs-2 float-left">
                                                                                                    <label class="top_table">تعداد</label>
                                                                                                </div>
                                                                                                <div class="col-xs-2 float-left">
                                                                                                    <label class="top_table">Sph</label>
                                                                                                </div>
                                                                                                <div class="col-xs-2 float-left">
                                                                                                    <label class="top_table">Cyl</label>
                                                                                                </div>
                                                                                                <div class="col-xs-2 float-left">
                                                                                                    <label class="top_table">Axis</label>
                                                                                                </div>
                                                                                                <div class="col-xs-2 float-left">
                                                                                                    <label class="top_table add_label">Add</label>
                                                                                                </div>
                                                                                                <div class="col-xs-1 float-left">
                                                                                                </div>

                                                                                                <div class="clearfix"></div>

                                                                                                <div class="boxsearch">
                                                                                                    <div class="col-xs-1 float-left">
                                                                                                        <label class="left_table">Right</label>
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text" name="rcount" class="form-control" disabled value="{{ $v->rcount }}" />
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text" name="rsph" class="form-control" disabled value="{{ $v->rsph }}" />
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text" name="rcyl" class="form-control" disabled value="{{ $v->rcyl }}" />
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text" name="raxis" class="form-control" disabled value="{{ $v->raxis }}" />
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left addcontroller">
                                                                                                        <input type="text" name="radd" class="form-control" disabled value="{{ $v->radd }}" />
                                                                                                    </div>
                                                                                                    <div class="col-xs-1 float-left">
                                                                                                    </div>

                                                                                                    <div class="clearfix"></div>
                                                                                                </div>

                                                                                                <div class="clearfix"></div>

                                                                                                <div class="boxsearch">
                                                                                                    <div class="col-xs-1 float-left">
                                                                                                        <label class="left_table">Left</label>
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text" name="lcount" class="form-control" disabled value="{{ $v->lcount }}" />
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text" name="lsph" class="form-control" disabled value="{{ $v->lsph }}" />
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text" name="lcyl" class="form-control" disabled value="{{ $v->lcyl }}" />
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text" name="laxis" class="form-control" disabled value="{{ $v->laxis }}" />
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left addcontroller">
                                                                                                        <input type="text" name="ladd" class="form-control" disabled value="{{ $v->ladd }}" />
                                                                                                    </div>
                                                                                                    <div class="col-xs-1 float-left">
                                                                                                    </div>

                                                                                                    <div class="clearfix"></div>
                                                                                                </div>

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                </div>

                                                                                <div class="col-xs-12 col-md-6 float-left">


                                                                                    <div class="row optic">

                                                                                        <div class="box_add_item">
                                                                                            <div class="item">

                                                                                                <div class="header_dodid_data">
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <label class="top_table">Dia</label>
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <label class="top_table">IPD</label>
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <label class="top_table">Prism</label>
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <label class="top_table">Prism base/curve</label>
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <label class="top_table">corridor length</label>
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <label class="top_table">DEC</label>
                                                                                                    </div>
                                                                                                    <div class="clearfix"></div>
                                                                                                </div>

                                                                                                <div class="clearfix"></div>

                                                                                                <div class="boxsearch">
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text" disabled class="dodid_input_control form-control" name="rdia" value="{{ old('rdia', @$v->dodid_rdia) }}" autocomplete="off" >
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text" disabled class="dodid_input_control form-control" name="ripd" value="{{ old('ripd', @$v->dodid_ripd) }}" autocomplete="off" >
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text" disabled class="dodid_input_control form-control" name="rprism" value="{{ old('rprism', @$v->dodid_rprism) }}" autocomplete="off" >
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text" disabled class="dodid_input_control form-control" name="rprism_base" value="{{ old('rprism_base', @$v->dodid_rprism_base) }}" autocomplete="off" >
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text" disabled class="dodid_input_control form-control" name="rcorridor" value="{{ old('rcorridor', @$v->dodid_rcorridor) }}" autocomplete="off" >
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text" disabled class="dodid_input_control form-control" name="rdec" value="{{ old('rdec', @$v->dodid_rdec) }}" autocomplete="off" >
                                                                                                    </div>

                                                                                                    <div class="clearfix"></div>
                                                                                                </div>

                                                                                                <div class="clearfix"></div>

                                                                                                <div class="boxsearch">

                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text" disabled class="dodid_input_control form-control" name="ldia" value="{{ old('ldia', @$v->dodid_ldia) }}" autocomplete="off" >
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text" disabled class="dodid_input_control form-control" name="lipd" value="{{ old('lipd', @$v->dodid_lipd) }}" autocomplete="off" >
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text" disabled class="dodid_input_control form-control" name="lprism" value="{{ old('lprism', @$v->dodid_lprism) }}" autocomplete="off" >
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text" disabled class="dodid_input_control form-control" name="lprism_base" value="{{ old('lprism_base', @$v->dodid_lprism_base) }}" autocomplete="off" >
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text" disabled class="dodid_input_control form-control" name="lcorridor" value="{{ old('lcorridor', @$v->dodid_lcorridor) }}" autocomplete="off" >
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text" disabled class="dodid_input_control form-control" name="ldec" value="{{ old('ldec', @$v->dodid_ldec) }}" autocomplete="off" >
                                                                                                    </div>

                                                                                                    <div class="clearfix"></div>
                                                                                                </div>

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                </div>

                                                                                <div class="clearfix"></div>

                                                                            </div>

                                                                        @endif

                                                                        @if($v->type == 'image')
                                                                            <div class="item_full">
                                                                                <div class="col-xs-4">تصویر نسخه</div>
                                                                                <div class="col-xs-8">
                                                                                    <ul class="image_list">

                                                                                        <?php $images = json_decode($v->image); ?>

                                                                                        @if($images)
                                                                                            @foreach($images as $image)
                                                                                                @if($image)
                                                                                                    <li><a target="_blank" href="{{ $image }}"><div class="bac_image_list" style="background-image: url('{{ $image }}')"><img src="{{ $image }}" style="display: none"></div></a></li>
                                                                                                @endif
                                                                                            @endforeach
                                                                                        @endif

                                                                                    </ul>
                                                                                </div>
                                                                                <div class="clearfix"></div>
                                                                            </div>
                                                                        @endif

                                                                        <div class="item_full">

                                                                            <div class="col-xs-12 col-md-6 float-left prisma_box">

                                                                                <div class="row">

                                                                                    <div class="box_add_item">
                                                                                        <div class="item">
                                                                                            <div class="col-xs-1 float-left">
                                                                                                <label class="top_table"></label>
                                                                                            </div>
                                                                                            <div class="col-xs-2 float-left">
                                                                                                <label class="top_table">Prism 1</label>
                                                                                            </div>
                                                                                            <div class="col-xs-2 float-left">
                                                                                                <label class="top_table">Degrees 1</label>
                                                                                            </div>
                                                                                            <div class="col-xs-2 float-left">
                                                                                                <label class="top_table">Prism 2</label>
                                                                                            </div>
                                                                                            <div class="col-xs-2 float-left">
                                                                                                <label class="top_table">Degrees 2</label>
                                                                                            </div>
                                                                                            <div class="col-xs-1 float-left">
                                                                                            </div>

                                                                                            <div class="clearfix"></div>

                                                                                            <div class="boxsearch">
                                                                                                <div class="col-xs-1 float-left">
                                                                                                    <label class="left_table">Right</label>
                                                                                                </div>
                                                                                                <div class="col-xs-2 float-left">
                                                                                                    <input type="text" disabled class="prisma_input_control form-control{{ $errors->has('rprisma1') ? ' is-invalid' : '' }}" name="rprisma1" value="{{ old('rprisma1', @$v->prisma_rprisma1) }}" autocomplete="off" >
                                                                                                </div>
                                                                                                <div class="col-xs-2 float-left">
                                                                                                    <input type="text" disabled class="prisma_input_control form-control{{ $errors->has('rdegrees1') ? ' is-invalid' : '' }}" name="rdegrees1" value="{{ old('rdegrees1', @$v->prisma_rdegrees1) }}" autocomplete="off" >
                                                                                                </div>
                                                                                                <div class="col-xs-2 float-left">
                                                                                                    <input type="text" disabled class="prisma_input_control form-control{{ $errors->has('rprisma2') ? ' is-invalid' : '' }}" name="rprisma2" value="{{ old('rprisma2', @$v->prisma_rprisma2) }}" autocomplete="off" >
                                                                                                </div>
                                                                                                <div class="col-xs-2 float-left">
                                                                                                    <input type="text" disabled class="prisma_input_control form-control{{ $errors->has('rdegrees2') ? ' is-invalid' : '' }}" name="rdegrees2" value="{{ old('rdegrees2', @$v->prisma_rdegrees2) }}" autocomplete="off" >
                                                                                                </div>
                                                                                                <div class="col-xs-1 float-left">
                                                                                                </div>

                                                                                                <div class="clearfix"></div>
                                                                                            </div>

                                                                                            <div class="clearfix"></div>

                                                                                            <div class="boxsearch">
                                                                                                <div class="col-xs-1 float-left">
                                                                                                    <label class="left_table">Left</label>
                                                                                                </div>
                                                                                                <div class="col-xs-2 float-left">
                                                                                                    <input type="text" disabled class="prisma_input_control form-control{{ $errors->has('lprisma1') ? ' is-invalid' : '' }}" name="lprisma1" value="{{ old('lprisma1', @$v->prisma_lprisma1) }}" autocomplete="off" >
                                                                                                </div>
                                                                                                <div class="col-xs-2 float-left">
                                                                                                    <input type="text" disabled class="prisma_input_control form-control{{ $errors->has('ldegrees1') ? ' is-invalid' : '' }}" name="ldegrees1" value="{{ old('ldegrees1', @$v->prisma_ldegrees1) }}" autocomplete="off" >
                                                                                                </div>
                                                                                                <div class="col-xs-2 float-left">
                                                                                                    <input type="text" disabled class="prisma_input_control form-control{{ $errors->has('lprisma2') ? ' is-invalid' : '' }}" name="lprisma2" value="{{ old('lprisma2', @$v->prisma_lprisma2) }}" autocomplete="off" >
                                                                                                </div>
                                                                                                <div class="col-xs-2 float-left">
                                                                                                    <input type="text" disabled class="prisma_input_control form-control{{ $errors->has('ldegrees2') ? ' is-invalid' : '' }}" name="ldegrees2" value="{{ old('ldegrees2', @$v->prisma_ldegrees2) }}" autocomplete="off" >
                                                                                                </div>
                                                                                                <div class="col-xs-1 float-left">
                                                                                                </div>

                                                                                                <div class="clearfix"></div>
                                                                                            </div>

                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                            </div>

                                                                            <div class="col-xs-12 col-md-6 float-left">

                                                                                @if($v->lathe)

                                                                                    <?php $lathe = json_decode($v->lathe); ?>
                                                                                    <div class="row">

                                                                                        <div class="lathe_main_cox">
                                                                                            <div class="col-xs-12">
                                                                                                <div class="row">
                                                                                                    <div class="item">
                                                                                                        <div class="col-xs-2 float-left">
                                                                                                            <label class="top_table"></label>
                                                                                                        </div>
                                                                                                        <div class="col-xs-2 float-left">
                                                                                                            <label class="top_table">PD</label>
                                                                                                        </div>
                                                                                                        <div class="col-xs-2 float-left">
                                                                                                            <label class="top_table">Height</label>
                                                                                                        </div>
                                                                                                        <div class="col-xs-1 float-left">
                                                                                                        </div>

                                                                                                        <div class="clearfix"></div>

                                                                                                        <div class="boxsearch">
                                                                                                            <div class="col-xs-2 float-left">
                                                                                                                <label class="left_table">Right eye</label>
                                                                                                            </div>
                                                                                                            <div class="col-xs-2 float-left">
                                                                                                                <input type="text" class="form-control" name="rpd" disabled value="{{ old('lpd', @$lathe->rpd) }}" autocomplete="off" >
                                                                                                            </div>
                                                                                                            <div class="col-xs-2 float-left">
                                                                                                                <input type="text" class="form-control" disabled name="rheight" value="{{ old('rheight', @$lathe->rheight) }}" autocomplete="off" >
                                                                                                            </div>
                                                                                                            <div class="col-xs-1 float-left">
                                                                                                            </div>

                                                                                                            <div class="clearfix"></div>
                                                                                                        </div>

                                                                                                        <div class="clearfix"></div>

                                                                                                        <div class="boxsearch">
                                                                                                            <div class="col-xs-2 float-left">
                                                                                                                <label class="left_table">Left eye</label>
                                                                                                            </div>
                                                                                                            <div class="col-xs-2 float-left">
                                                                                                                <input type="text" class="form-control" name="lpd" disabled value="{{ old('lpd', @$lathe->lpd) }}" autocomplete="off" >
                                                                                                            </div>
                                                                                                            <div class="col-xs-2 float-left">
                                                                                                                <input type="text" class="form-control" name="lheight" disabled value="{{ old('lheight', @$lathe->lheight) }}" autocomplete="off" >
                                                                                                            </div>
                                                                                                            <div class="col-xs-1 float-left">
                                                                                                            </div>

                                                                                                            <div class="clearfix"></div>
                                                                                                        </div>

                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                    </div>

                                                                                @endif

                                                                            </div>

                                                                            <div class="clearfix"></div>

                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                    @endforeach

                                                </div>
                                            </div>

                                        @endif

                                        <div class="col-xs-12"><a class="btn btn-block btn-info btn-rounded" target="_blank" href="{{ url('cp-bonakdar/order/print/' . $item['key']) }}">چاپ فاکتور</a></div>
                                        <div class="clearfix"></div>

                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="msg">
                    <div class="alert alert-warning alert-info" style="text-align: center">سفارشی یافت نشد.</div>
                </div>
            @endif

            {!! $request->render() !!}
            <div class="clearfix"></div>
        </div>

    </div>

    <script>
        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.status_operator').change(function () {
                var dataid = $(this).attr('data-id');
                var val = $(this).val();

                $.ajax({
                    method: 'POST',
                    url: '{{ url('cp-bonakdar/order/status-update') }}',
                    data: {id : dataid , value: val}
                }).done(function(result){
                    console.log(result);
                });

            });

        })
    </script>

@endsection