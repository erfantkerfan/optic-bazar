@extends('admin.layouts.app')

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
                        <label for="filter_amel">عامل</label>
                        <select class="form-control" id="filter_amel" name="filter_amel">
                            <option value="">انتخاب کنید</option>
                            <option value="bazar" {{ (@$_GET['filter_amel'] == 'bazar') ? "selected" : "" }}>به انتخاب سامانه</option>
                            @foreach($amels as $u)
                                <option value="{{ $u['id'] }}" {{ (@$_GET['filter_amel']== $u['id']) ? "selected" : "" }}>{{ $u['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-xs-12">
                    <div class="form-group">
                        <label for="filter_labrators">لابراتور</label>
                        <select class="form-control" id="filter_labrators" name="filter_labrators">
                            <option value="">انتخاب کنید</option>
                            @foreach($labrators as $u)
                                <option value="{{ $u['id'] }}" {{ (@$_GET['filter_labrators']== $u['id']) ? "selected" : "" }}>{{ $u['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-xs-12">
                    <div class="form-group">
                        <label for="filter_bonakdars">بنکدار</label>
                        <select class="form-control" id="filter_bonakdars" name="filter_bonakdars">
                            <option value="">انتخاب کنید</option>
                            <option value="bazar" {{ (@$_GET['filter_bonakdars'] == 'bazar') ? "selected" : "" }}>بازار اپتیک</option>
                            @foreach($bonakdars as $u)
                                <option value="{{ $u['id'] }}" {{ (@$_GET['filter_bonakdars']== $u['id']) ? "selected" : "" }}>{{ $u['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-xs-12">
                    <div class="form-group">
                        <label for="filter_name">فروشگاه</label>
                        <input type="text" class="form-control" id="filter_name" name="filter_name" value="{{ @$_GET['filter_name'] }}">
                    </div>
                </div>
                <div class="col-md-3 col-xs-12">
                    <div class="form-group">
                        <label for="filter_brand">برند</label>
                        <input type="text" class="form-control" id="filter_brand" name="filter_brand" value="{{ @$_GET['filter_brand'] }}">
                    </div>
                </div>
                <div class="col-md-3 col-xs-12">
                    <div class="form-group">
                        <label for="filter_name">وضعیت سفارش</label>
                        <select class="form-control" id="filter_status" name="filter_status">
                            <option value="">انتخاب کنید</option>
                            <option value="100" {{ (@$_GET['filter_status'] == "100") ? "selected" : "" }}>دریافت اطلاعات سفارش</option>
                            <option value="2" {{ (@$_GET['filter_status'] == "2") ? "selected" : "" }}>تهیه اقلام سفارش</option>
                            <option value="1" {{ (@$_GET['filter_status'] == "1") ? "selected" : "" }}>انجام خدمات لابراتور</option>
                            <option value="4" {{ (@$_GET['filter_status'] == "4") ? "selected" : "" }}>تحویل به پیک سامانه</option>
                            <option value="5" {{ (@$_GET['filter_status'] == "5") ? "selected" : "" }}>تحویل به باربری</option>
                            <option value="6" {{ (@$_GET['filter_status'] == "6") ? "selected" : "" }}>بسته شده</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-xs-12">
                    <div class="form-group">
                        <label for="filter_product">نام محصول</label>
                        <input type="text" class="form-control" id="filter_product" name="filter_product" value="{{ @$_GET['filter_product'] }}">
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

                                        @if($orderData && count($orderData[$item['id']]) > 0)

                                            <div class="box_archive_prescription">
                                                <div class="row">

                                                    @foreach($orderData[$item['id']] as $count => $v)
                                                        <div class="item_prescription open counter">
                                                            <div class="col-xs-12 col-md-12">
                                                                <div class="invite">
                                                                    <span style="min-width: auto;">سفارش {{ $v->order_id }} ) </span>
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
                                                                            <div class="col-xs-4">بنکدار</div>
                                                                            <?php $pruser = \App\User::where('id', $v->seller_id)->where('role', 'bonakdar')->first(); ?>
                                                                            <div class="col-xs-8">
                                                                                <div class="use_data">

                                                                                    {{ ($pruser) ? $pruser->name : 'بازار اپتیک' }}

                                                                                    @if($pruser)

                                                                                        <div class="row">
                                                                                            <div class="col-xs-3">شماره تماس : {{ $pruser->phone }}</div>
                                                                                            <div class="col-xs-9">آدرس : {{ $pruser->state . ' - ' . $pruser->city . ' - ' . $pruser->address }}</div>
                                                                                        </div>

                                                                                    @endif

                                                                                </div>

                                                                            </div>
                                                                            <div class="clearfix"></div>
                                                                        </div>

                                                                        <div class="item_full">
                                                                            <div class="col-xs-4">عامل</div>
                                                                            <?php $pruser = \App\User::where('id', $v->lathe_id)->where('role', 'amel')->first(); ?>
                                                                            <div class="col-xs-8">

                                                                                <div class="use_data">

                                                                                    {{ ($pruser) ? $pruser->name : 'به انتخاب سامانه' }}

                                                                                    @if($pruser)

                                                                                        <div class="row">
                                                                                            <div class="col-xs-3">شماره تماس : {{ $pruser->phone }}</div>
                                                                                            <div class="col-xs-9">آدرس : {{ $pruser->state . ' - ' . $pruser->city . ' - ' . $pruser->address }}</div>
                                                                                        </div>

                                                                                    @endif

                                                                                </div>

                                                                                <div class="box_change_use">
                                                                                    <a href="#" class="change_use change_amel">تغییر عامل</a>
                                                                                    <div class="user_list">
                                                                                        <select class="form-control set_amel" data-id="{{ $v->order_id }}">
                                                                                            @foreach($amels as $u)
                                                                                                <option value="{{ $u['id'] }}" {{ ($v->lathe_id == $u['id']) ? "selected" : "" }}>{{ $u['name'] }}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
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
                                                                                <div class="col-xs-8">

                                                                                    <div class="use_data">

                                                                                        {{ ($pruser) ? $pruser->name : 'بدون نام' }}

                                                                                        @if($pruser)

                                                                                            <div class="row">
                                                                                                <div class="col-xs-3">شماره تماس : {{ $pruser->phone }}</div>
                                                                                                <div class="col-xs-9">آدرس : {{ $pruser->state . ' - ' . $pruser->city . ' - ' . $pruser->address }}</div>
                                                                                            </div>

                                                                                        @endif

                                                                                    </div>

                                                                                    <div class="box_change_use">
                                                                                        <a href="#" class="change_use change_labrator">تغییر لابراتور</a>
                                                                                        <div class="user_list">
                                                                                            <select class="form-control set_labrator" data-id="{{ $v->order_id }}">
                                                                                                @foreach($labrators as $u)
                                                                                                    <option value="{{ $u['id'] }}" {{ (isset($labrator[$v->order_id]) && $labrator[$v->order_id] == $u['id']) ? "selected" : "" }}>{{ $u['name'] }}</option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>

                                                                                </div>
                                                                            @else
                                                                                <div class="col-xs-8">

                                                                                    <div class="use_data">نیاز به خدمات اضافی نیست</div>

                                                                                </div>
                                                                            @endif
                                                                            <div class="clearfix"></div>
                                                                        </div>

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

                                                                        <div class="item_full">
                                                                            <div class="col-xs-4">سود سامانه</div>
                                                                            <div class="col-xs-8">{{ number_format($v->paid_amount_shop) }} تومان</div>
                                                                            <div class="clearfix"></div>
                                                                        </div>

                                                                        <div class="item_full">
                                                                            <div class="col-xs-4">سهم لابراتور</div>
                                                                            <div class="col-xs-8">{{ number_format($v->paid_amount_labrator) }} تومان</div>
                                                                            <div class="clearfix"></div>
                                                                        </div>

                                                                        <div class="item_full">
                                                                            <div class="col-xs-4">سهم بنکدار</div>
                                                                            <div class="col-xs-8">{{ number_format($v->paid_amount_bonakdar) }} تومان</div>
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


                                                                        <form class="edit_prescriptions" action="{{ url('cp-manager/order/edit_prescriptions/' . $v->order_id) }}">

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
                                                                                                        <input type="text" name="rcount" class="form-control"  value="{{ $v->rcount }}" />
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text" name="rsph" class="form-control"  value="{{ $v->rsph }}" />
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text" name="rcyl" class="form-control"  value="{{ $v->rcyl }}" />
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text" name="raxis" class="form-control"  value="{{ $v->raxis }}" />
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left addcontroller">
                                                                                                        <input type="text" name="radd" class="form-control"  value="{{ $v->radd }}" />
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
                                                                                                        <input type="text" name="lcount" class="form-control"  value="{{ $v->lcount }}" />
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text" name="lsph" class="form-control"  value="{{ $v->lsph }}" />
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text" name="lcyl" class="form-control"  value="{{ $v->lcyl }}" />
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text" name="laxis" class="form-control"  value="{{ $v->laxis }}" />
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left addcontroller">
                                                                                                        <input type="text" name="ladd" class="form-control"  value="{{ $v->ladd }}" />
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
                                                                                                        <input type="text"  class="dodid_input_control form-control" name="rdia" value="{{ old('rdia', @$v->dodid_rdia) }}" autocomplete="off" >
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text"  class="dodid_input_control form-control" name="ripd" value="{{ old('ripd', @$v->dodid_ripd) }}" autocomplete="off" >
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text"  class="dodid_input_control form-control" name="rprism" value="{{ old('rprism', @$v->dodid_rprism) }}" autocomplete="off" >
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text"  class="dodid_input_control form-control" name="rprism_base" value="{{ old('rprism_base', @$v->dodid_rprism_base) }}" autocomplete="off" >
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text"  class="dodid_input_control form-control" name="rcorridor" value="{{ old('rcorridor', @$v->dodid_rcorridor) }}" autocomplete="off" >
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text"  class="dodid_input_control form-control" name="rdec" value="{{ old('rdec', @$v->dodid_rdec) }}" autocomplete="off" >
                                                                                                    </div>

                                                                                                    <div class="clearfix"></div>
                                                                                                </div>

                                                                                                <div class="clearfix"></div>

                                                                                                <div class="boxsearch">

                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text"  class="dodid_input_control form-control" name="ldia" value="{{ old('ldia', @$v->dodid_ldia) }}" autocomplete="off" >
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text"  class="dodid_input_control form-control" name="lipd" value="{{ old('lipd', @$v->dodid_lipd) }}" autocomplete="off" >
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text"  class="dodid_input_control form-control" name="lprism" value="{{ old('lprism', @$v->dodid_lprism) }}" autocomplete="off" >
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text"  class="dodid_input_control form-control" name="lprism_base" value="{{ old('lprism_base', @$v->dodid_lprism_base) }}" autocomplete="off" >
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text"  class="dodid_input_control form-control" name="lcorridor" value="{{ old('lcorridor', @$v->dodid_lcorridor) }}" autocomplete="off" >
                                                                                                    </div>
                                                                                                    <div class="col-xs-2 float-left">
                                                                                                        <input type="text"  class="dodid_input_control form-control" name="ldec" value="{{ old('ldec', @$v->dodid_ldec) }}" autocomplete="off" >
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
                                                                                                    <input type="text"  class="prisma_input_control form-control{{ $errors->has('rprisma1') ? ' is-invalid' : '' }}" name="rprisma1" value="{{ old('rprisma1', @$v->prisma_rprisma1) }}" autocomplete="off" >
                                                                                                </div>
                                                                                                <div class="col-xs-2 float-left">
                                                                                                    <input type="text"  class="prisma_input_control form-control{{ $errors->has('rdegrees1') ? ' is-invalid' : '' }}" name="rdegrees1" value="{{ old('rdegrees1', @$v->prisma_rdegrees1) }}" autocomplete="off" >
                                                                                                </div>
                                                                                                <div class="col-xs-2 float-left">
                                                                                                    <input type="text"  class="prisma_input_control form-control{{ $errors->has('rprisma2') ? ' is-invalid' : '' }}" name="rprisma2" value="{{ old('rprisma2', @$v->prisma_rprisma2) }}" autocomplete="off" >
                                                                                                </div>
                                                                                                <div class="col-xs-2 float-left">
                                                                                                    <input type="text"  class="prisma_input_control form-control{{ $errors->has('rdegrees2') ? ' is-invalid' : '' }}" name="rdegrees2" value="{{ old('rdegrees2', @$v->prisma_rdegrees2) }}" autocomplete="off" >
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
                                                                                                    <input type="text"  class="prisma_input_control form-control{{ $errors->has('lprisma1') ? ' is-invalid' : '' }}" name="lprisma1" value="{{ old('lprisma1', @$v->prisma_lprisma1) }}" autocomplete="off" >
                                                                                                </div>
                                                                                                <div class="col-xs-2 float-left">
                                                                                                    <input type="text"  class="prisma_input_control form-control{{ $errors->has('ldegrees1') ? ' is-invalid' : '' }}" name="ldegrees1" value="{{ old('ldegrees1', @$v->prisma_ldegrees1) }}" autocomplete="off" >
                                                                                                </div>
                                                                                                <div class="col-xs-2 float-left">
                                                                                                    <input type="text"  class="prisma_input_control form-control{{ $errors->has('lprisma2') ? ' is-invalid' : '' }}" name="lprisma2" value="{{ old('lprisma2', @$v->prisma_lprisma2) }}" autocomplete="off" >
                                                                                                </div>
                                                                                                <div class="col-xs-2 float-left">
                                                                                                    <input type="text"  class="prisma_input_control form-control{{ $errors->has('ldegrees2') ? ' is-invalid' : '' }}" name="ldegrees2" value="{{ old('ldegrees2', @$v->prisma_ldegrees2) }}" autocomplete="off" >
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
                                                                                                                <input type="text" class="form-control" name="rpd"  value="{{ old('lpd', @$lathe->rpd) }}" autocomplete="off" >
                                                                                                            </div>
                                                                                                            <div class="col-xs-2 float-left">
                                                                                                                <input type="text" class="form-control"  name="rheight" value="{{ old('rheight', @$lathe->rheight) }}" autocomplete="off" >
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
                                                                                                                <input type="text" class="form-control" name="lpd"  value="{{ old('lpd', @$lathe->lpd) }}" autocomplete="off" >
                                                                                                            </div>
                                                                                                            <div class="col-xs-2 float-left">
                                                                                                                <input type="text" class="form-control" name="lheight"  value="{{ old('lheight', @$lathe->lheight) }}" autocomplete="off" >
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


                                                                        <?php

                                                                            if($v->type_shipping){
                                                                                ?>
                                                                                <div class="item_full">
                                                                                    <div class="col-xs-12">روش تحویل سفارش  :
                                                                                        @if($v->type_shipping == 'theNormal')
                                                                                            تحویل فوری یا عادی
                                                                                        @elseif($v->type_shipping == 'inPerson')
                                                                                            تحویل حضوری
                                                                                        @elseif($v->type_shipping == 'theCalendar')
                                                                                            تحویل تقویمی
                                                                                        @elseif($v->type_shipping == 'theCity')
                                                                                            تحویل شهرستان
                                                                                        @endif
                                                                                    </div>

                                                                                    <div class="clearfix"></div>
                                                                                </div>
                                                                                <?php
                                                                            }

                                                                            $order_get_end_time = App\Order_detail::where('order_id', $v->order_id)->where('key' , 'order_get_end_time')->first();
                                                                            if($order_get_end_time){
                                                                                ?>
                                                                                <div class="item_full">
                                                                                    <div class="col-xs-12">زمان تقریبی پذیرش سفارش : {{ jdate('Y/m/d', strtotime($v->get_box_date)) }} {{ ($v->get_box_time) ? 'ساعت : ' . $v->get_box_time . ' الی ' . $order_get_end_time->val : '' }}</div>

                                                                                    <div class="clearfix"></div>
                                                                                </div>
                                                                                <?php
                                                                            }

                                                                            $order_send_end_time = App\Order_detail::where('order_id', $v->order_id)->where('key' , 'order_send_end_time')->first();
                                                                            if($order_send_end_time){
                                                                                ?>
                                                                                <div class="item_full">
                                                                                    <div class="col-xs-12">زمان خروج سفارش حاضر شده : {{ jdate('Y/m/d', strtotime($v->send_box_date)) }} {{ ($v->send_box_time) ? 'ساعت : ' . $v->send_box_time . ' الی ' . $order_send_end_time->val : '' }}</div>

                                                                                    <div class="clearfix"></div>
                                                                                </div>
                                                                                <?php
                                                                            }

                                                                        ?>


                                                                        <div class="item_full">
                                                                            <div class="col-xs-12">
                                                                                <button type="submit" class="btn btn-success btn-rounded pull-right" style="padding-left: 50px; padding-right: 50px">بروز رسانی نسخه</button>
                                                                            </div>
                                                                            <div class="clearfix"></div>
                                                                        </div>

                                                                        </form>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                    @endforeach

                                                </div>
                                            </div>

                                        @endif


                                        <div class="endfactor" style="margin-bottom: 50px">

                                            <div class="invoice_dill">
                                                <div class="row">
                                                    <div class="col-xs-6"><span>جمع فاکتور</span></div>
                                                    <div class="col-xs-6">{{ number_format($item['orginal_price']) }} تومان</div>
                                                    <div class="clearfix"></div>
                                                    <hr>
                                                    @if($item['discount'])
                                                        <div class="col-xs-6" style="color: red;"><span>تخفیف</span></div>
                                                        <div class="col-xs-6" style="color: red;">{{ number_format($item['discount']) }} تومان</div>
                                                        <div class="clearfix"></div>
                                                        <hr>
                                                    @endif
                                                    <div class="col-xs-6"><span>روش پرداخت</span></div>
                                                    <div class="col-xs-6">
                                                        @switch($item['payment_method'])
                                                            @case('credit') پرداخت از طریق اعتبار @break
                                                            @default پرداخت آنلاین @break
                                                        @endswitch
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <hr>
                                                    <div class="color_green">
                                                        <div class="col-xs-6"><span>مبلغ پرداخت شده </span></div>
                                                        <div class="col-xs-6">{{ number_format($item['price']) }} تومان</div>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-xs-12">
                                            <a class="btn btn-info btn-rounded" style="padding-left: 50px; padding-right: 50px" target="_blank" href="{{ url('cp-manager/order/print/' . $item['key']) }}">چاپ فاکتور</a>
                                        </div>
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
                    url: '{{ url('cp-manager/order/status-update') }}',
                    data: {id : dataid , value: val}
                }).done(function(result){
                    console.log(result);
                });

            });

            $('.set_amel').change(function () {
                var dataid = $(this).attr('data-id');
                var val = $(this).val();
                var live = $(this);

                $.ajax({
                    method: 'POST',
                    url: '{{ url('cp-manager/order/set_amel') }}',
                    data: {id : dataid , value: val}
                }).done(function(result){

                    if(result !== 'error'){
                        live.parent().parent().parent().find('.use_data').html(result.name+'<div class="row"><div class="col-xs-3">شماره تماس : '+result.phone+'</div><div class="col-xs-9">آدرس : '+result.state + ' - ' + result.city  + ' - ' + result.address+'</div></div>')
                    }

                });

            });

            $('.set_labrator').change(function () {
                var dataid = $(this).attr('data-id');
                var val = $(this).val();
                var live = $(this);

                $.ajax({
                    method: 'POST',
                    url: '{{ url('cp-manager/order/set_labrator') }}',
                    data: {id : dataid , value: val}
                }).done(function(result){
                    if(result !== 'error'){
                        live.parent().parent().parent().find('.use_data').html(result.name+'<div class="row"><div class="col-xs-3">شماره تماس : '+result.phone+'</div><div class="col-xs-9">آدرس : '+result.state + ' - ' + result.city  + ' - ' + result.address+'</div></div>')
                    }
                });

            });

            $('.change_use').click(function (e) {
                e.preventDefault();
                $(this).parent().find('.user_list').toggleClass('active');
            });



            $(document).delegate('.edit_prescriptions', 'submit', function (e) {

                data_full = {};
                image_full = {};

                var counter = 0;
                $(this).find('input').each(function () {
                    if($(this).attr('name') === "two_eyes"){
                        if($('input[name="two_eyes"]').attr('checked')){
                            data_full[$(this).attr('name')] = 1;
                        }else{
                            data_full[$(this).attr('name')] = 0;
                        }
                    }else if($(this).attr('name') === "prisma"){
                        if($('input[name="prisma"]').attr('checked')){
                            data_full[$(this).attr('name')] = 1;
                        }else{
                            data_full[$(this).attr('name')] = 0;
                        }
                    }else if($(this).attr('name') === "product_type"){
                        if($('#add_prescription').hasClass('optical_glass')){
                            data_full[$(this).attr('name')] = 'optical_glass';
                        }else{
                            data_full[$(this).attr('name')] = 'lens';
                        }
                    }else if($(this).attr('name') === "product_type_image"){
                        if($('#product_type_image').hasClass('optic')){
                            data_full[$(this).attr('name')] = 'optical_glass';
                        }else{
                            data_full[$(this).attr('name')] = 'lens';
                        }
                    }else if($(this).attr('name') === "lathe"){
                        if($('.box_lathe_btn').hasClass('open_sub')){
                            data_full[$(this).attr('name')] = 1;
                        }else{
                            data_full[$(this).attr('name')] = 0;
                        }
                    }else if($(this).attr('name') === "prescription_archive"){
                        if($(this).attr('checked')){
                            data_full[$(this).attr('name')] = $(this).val();
                        }
                    }else if($(this).attr('name') === "image[]"){
                        image_full[counter] = $(this).val();
                        counter++;
                    }else{
                        data_full[$(this).attr('name')] = $(this).val();
                    }
                });


                $(this).find('select').each(function () {
                    data_full[$(this).attr('name')] = $(this).val();
                });

                if(image_full){
                    data_full["images"] = image_full;
                }


                $(this).find('button[type="submit"]').attr("disabled", 'disabled').html('لطفا صبر کنید ، در حال بارگذاری ...');
                var action = $(this).attr('action');
                var liveitem = $(this);

                var jsonString = JSON.stringify(data_full);

                $.ajax({
                    method: 'POST',
                    url: action,
                    data: {json : jsonString}
                }).done(function(result){

                    if(result.staus === 'success'){
                        liveitem.find('button[type="submit"]').removeAttr("disabled").html('بروز رسانی نسخه');

                    }else{
                        liveitem.find('button[type="submit"]').removeAttr("disabled").html('بروز رسانی نسخه');
                    }
                });

                return false;

            });

        })
    </script>

@endsection