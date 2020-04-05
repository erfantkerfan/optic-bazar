@extends('amel.layouts.print_app')

@section('content')

    <style>
        .item_full,
        .lathe_main_cox label,
        .boxsearch .left_table,
        .box_add_item label.top_table {
            font-size: 9px !important;
        }
        .item_full.import {
            padding: 20px 0 0 0;
            background: #fff;
        }
        .full_data_prescription .item_full{
            padding: 0;
            background: #fff;
            margin-top: -10px;
        }
        .form-control {
            height: 27px;
            font-size: 10px;
            padding: 0;
        }
        .header_dodid_data {
            margin:0 !important;
            margin-top: -8px !important;
        }
        .full_data_prescription {
            padding: 0;
            margin-top: 2px;
        }
        .item_prescription {
            padding: 0;
            margin-bottom: 10px;
        }
        .box_archive_prescription {
            width: 800px;
        }
        .item_prescription .invite {
            padding-top: 0;
            padding-bottom: 20px;
        }
        .boxsearch {
            margin-bottom: 0;
        }
        .item_full.full_data {
            margin-bottom: 2px;
            position: relative;
            top: -1px;
        }
    </style>
    <div class="white-box">

        <div class="portlet-body">


            @if(count($orderData) > 0)

                <div class="box_archive_prescription">
                    <div class="row">

                        @foreach($orderData as $count => $v)
                            <div class="item_prescription open counter" style="border-bottom: 1px solid #ccc;">
                                <div class="col-xs-12 col-md-12">
                                    <div class="invite" style="font-size: 11px;">
                                        <span style="margin-left: 0; padding: 0">فروشگاه : {{ $UserLogin->name }}</span>
                                        <span style="min-width: auto;margin-left: 0;">سفارش {{ $v->order_id }} ) </span>
                                        <span style="margin-left: 0; min-width: auto">شماره فاکتور : <span style="font-family: Tahoma;margin-left: 0; padding: 0; min-width: auto"> BOP-{{ $trans->id }}</span></span>
                                        <span style="margin-left: 0;">{{ $v->product_sku }}</span>
                                        <span style="margin-left: 0; padding: 0; min-width: auto">{{ \App\Brand::where('id', $v->brand_id)->first()->name }}</span>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="full_data_prescription">
                                        <div class="row">

                                            @if($v->type == 'import')

                                                <div class="item_full import" style="height: 100px;">

                                                    <div class="col-xs-6 float-left">

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
                                                                            <input type="text" name="rsph" class="form-control" disabled value="{{ str_replace('no_select', '' , $v->rsph) }}" />
                                                                        </div>
                                                                        <div class="col-xs-2 float-left">
                                                                            <input type="text" name="rcyl" class="form-control" disabled value="{{ str_replace('no_select', '' , $v->rcyl) }}" />
                                                                        </div>
                                                                        <div class="col-xs-2 float-left">
                                                                            <input type="text" name="raxis" class="form-control" disabled value="{{ str_replace('no_select', '' , $v->raxis) }}" />
                                                                        </div>
                                                                        <div class="col-xs-2 float-left addcontroller">
                                                                            <input type="text" name="radd" class="form-control" disabled value="{{ str_replace('بدون محدودیت', '' , $v->radd) }}" />
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
                                                                            <input type="text" name="lsph" class="form-control" disabled value="{{ str_replace('no_select', '' , $v->lsph) }}" />
                                                                        </div>
                                                                        <div class="col-xs-2 float-left">
                                                                            <input type="text" name="lcyl" class="form-control" disabled value="{{ str_replace('no_select', '' , $v->lcyl) }}" />
                                                                        </div>
                                                                        <div class="col-xs-2 float-left">
                                                                            <input type="text" name="laxis" class="form-control" disabled value="{{ str_replace('no_select', '' , $v->laxis) }}" />
                                                                        </div>
                                                                        <div class="col-xs-2 float-left addcontroller">
                                                                            <input type="text" name="ladd" class="form-control" disabled value="{{ str_replace('بدون محدودیت', '' , $v->ladd) }}" />
                                                                        </div>
                                                                        <div class="col-xs-1 float-left">
                                                                        </div>

                                                                        <div class="clearfix"></div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="col-xs-6 float-left">


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
                                                                            <input type="text" disabled class="dodid_input_control form-control" name="rdia" value="{{ old('rdia', @$request->dodid_rdia) }}" autocomplete="off" >
                                                                        </div>
                                                                        <div class="col-xs-2 float-left">
                                                                            <input type="text" disabled class="dodid_input_control form-control" name="ripd" value="{{ old('ripd', @$request->dodid_ripd) }}" autocomplete="off" >
                                                                        </div>
                                                                        <div class="col-xs-2 float-left">
                                                                            <input type="text" disabled class="dodid_input_control form-control" name="rprism" value="{{ old('rprism', @$request->dodid_rprism) }}" autocomplete="off" >
                                                                        </div>
                                                                        <div class="col-xs-2 float-left">
                                                                            <input type="text" disabled class="dodid_input_control form-control" name="rprism_base" value="{{ old('rprism_base', @$request->dodid_rprism_base) }}" autocomplete="off" >
                                                                        </div>
                                                                        <div class="col-xs-2 float-left">
                                                                            <input type="text" disabled class="dodid_input_control form-control" name="rcorridor" value="{{ old('rcorridor', @$request->dodid_rcorridor) }}" autocomplete="off" >
                                                                        </div>
                                                                        <div class="col-xs-2 float-left">
                                                                            <input type="text" disabled class="dodid_input_control form-control" name="rdec" value="{{ old('rdec', @$request->dodid_rdec) }}" autocomplete="off" >
                                                                        </div>

                                                                        <div class="clearfix"></div>
                                                                    </div>

                                                                    <div class="clearfix"></div>

                                                                    <div class="boxsearch">

                                                                        <div class="col-xs-2 float-left">
                                                                            <input type="text" disabled class="dodid_input_control form-control" name="ldia" value="{{ old('ldia', @$request->dodid_ldia) }}" autocomplete="off" >
                                                                        </div>
                                                                        <div class="col-xs-2 float-left">
                                                                            <input type="text" disabled class="dodid_input_control form-control" name="lipd" value="{{ old('lipd', @$request->dodid_lipd) }}" autocomplete="off" >
                                                                        </div>
                                                                        <div class="col-xs-2 float-left">
                                                                            <input type="text" disabled class="dodid_input_control form-control" name="lprism" value="{{ old('lprism', @$request->dodid_lprism) }}" autocomplete="off" >
                                                                        </div>
                                                                        <div class="col-xs-2 float-left">
                                                                            <input type="text" disabled class="dodid_input_control form-control" name="lprism_base" value="{{ old('lprism_base', @$request->dodid_lprism_base) }}" autocomplete="off" >
                                                                        </div>
                                                                        <div class="col-xs-2 float-left">
                                                                            <input type="text" disabled class="dodid_input_control form-control" name="lcorridor" value="{{ old('lcorridor', @$request->dodid_lcorridor) }}" autocomplete="off" >
                                                                        </div>
                                                                        <div class="col-xs-2 float-left">
                                                                            <input type="text" disabled class="dodid_input_control form-control" name="ldec" value="{{ old('ldec', @$request->dodid_ldec) }}" autocomplete="off" >
                                                                        </div>

                                                                        <div class="clearfix"></div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="clearfix"></div>

                                                </div>
                                                <div class="item_full">

                                                    <div class="col-xs-6 float-left prisma_box">

                                                        <div class="row">
                                                            <div class="box_add_item">
                                                                <div class="item">
                                                                    <div class="col-xs-1 float-left">
                                                                        <label class="top_table"></label>
                                                                    </div>
                                                                    <div class="col-xs-2 float-left">
                                                                        <label class="top_table">pd</label>
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
                                                                            <input type="text" disabled class="prisma_input_control form-control{{ $errors->has('rpd') ? ' is-invalid' : '' }}" name="rpd" value="{{ old('rpd', @$v->rpd) }}" autocomplete="off" >
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
                                                                            <input type="text" disabled class="prisma_input_control form-control{{ $errors->has('lpd') ? ' is-invalid' : '' }}" name="lpd" value="{{ old('lpd', @$v->lpd) }}" autocomplete="off" >
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

                                                    <div class="col-xs-6 float-left" style="text-align: right">

                                                        <div class="row">

                                                            <div class="col-xs-3">
                                                                <p>خدمات تراش کاری:

                                                                    <?php $order_service = \App\Order_detail::where('order_id', $v->order_id)->where('key', 'LIKE' ,'%order_service%%title%')->get();  ?>
                                                                    @if($order_service)
                                                                        @foreach ($order_service as $service)
                                                                            <span style="font-size: 10px; margin-left: 5px;">{{ $service['val'] }}</span>
                                                                        @endforeach
                                                                    @endif


                                                                </p>
                                                            </div>

                                                            <div class="col-xs-3">
                                                                <p>لابراتور: {{ (isset($labrator[$v->order_id])) ? isset($labrator[$v->order_id]) : '' }}</p>
                                                            </div>
                                                            <div class="col-xs-3">
                                                                <?php $pruser = \App\User::where('id', $v->lathe_id)->where('role', 'amel')->first(); ?>
                                                                <p>عامل: {{ ($pruser) ? $pruser->name : 'به انتخاب سامانه' }}</p>
                                                            </div>
                                                            <div class="col-xs-3">
                                                                <?php $pruser = \App\User::where('id', $v->seller_id)->where('role', 'bonakdar')->first(); ?>
                                                                <p>بنکدار: {{ ($pruser) ? $pruser->name : 'بازار اپتیک' }}</p>
                                                            </div>

                                                            <div class="clearfix"></div>

                                                            <div class="col-xs-4">
                                                                <p>توضیحات: {{ $v->description }}</p>
                                                            </div>
                                                            <div class="col-xs-3">
                                                                <p>نوع تحویل:

                                                                    @if($v->type_shipping == 'theNormal')
                                                                        فوری یا عادی
                                                                    @elseif($v->type_shipping == 'inPerson')
                                                                        حضوری
                                                                    @elseif($v->type_shipping == 'theCalendar')
                                                                        تقویمی
                                                                    @elseif($v->type_shipping == 'theCity')
                                                                        شهرستان
                                                                    @endif

                                                                </p>
                                                            </div>
                                                            <div class="col-xs-5">
                                                                <div class="row">

                                                                    <div class="col-xs-12">
                                                                        <p>زمان پذیرش: {{ jdate('Y/m/d', strtotime($v->get_box_date)) }} {{ ($v->get_box_time) ? 'ساعت ' . $v->get_box_time : '' }}</p>
                                                                    </div>
                                                                    <div class="col-xs-12">
                                                                        <p>زمان خروج: {{ jdate('Y/m/d', strtotime($v->send_box_date)) }} {{ ($v->send_box_time) ? 'ساعت ' . $v->send_box_time : '' }}</p>
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

                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        @endforeach

                    </div>
                </div>

            @endif

            <div class="clearfix"></div>
        </div>

    </div>

    <script type="text/javascript">
        window.print();

    </script>

@endsection