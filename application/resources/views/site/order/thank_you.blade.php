@extends('site.layouts.app')

@section('content')

    <div class="box_color_pages baclite">
        <div class="product_page_list">

            <div class="zm_order_lists">
                <div class="container">
                    <div class="row">

                        <div class="error clearfix">
                            @if(Session::has('error'))
                                <div class="alert alert-danger">{{ Session::get('error') }}</div>
                            @endif

                            @if(Session::has('success'))
                                <div class="alert alert-success">{{ Session::get('success') }}</div>
                            @endif

                        </div>

                        <div class="zm_step_box active">
                            <div class="main_step">

                                <div class="alert alert-success">پرداخت شما با موفقیت انجام شد .  امتیاز حاصل از این خرید در مجموعه امتیاز های شما در باشگاه مشتریان لحاظ میگردد .</div>
                                <div class="header_pey">
                                    <p> جزثیات دقیق سفارش را در بخش زیر می توانید بررسی کنید:</p>
                                </div>

                                <div class="header_factor">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <div class="info_user">
                                                <p><span>نام و نام خانوادگی : </span> {{ $UserLogin->name }}</p>
                                                <p><span>شماره همراه : </span> {{ $UserLogin->mobile }}</p>
                                                <p><span>ایمیل : </span> {{ $UserLogin->email }}</p>
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="info_user">
                                                <p><span>کد پیگیری : </span> {{ $request->id }}</p>
                                                <p><span>تاریخ سفارش : </span> <span style="direction: ltr">{{ jdate('Y/m/d', strtotime($request->created_at)) }}</span></p>
                                            </div>
                                        </div>
                                        <div class="col-xs-2"> <img src="{!! asset('assets/images/logo.png') !!}"></div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div class="zm_step_box active">
                            <div class="new-form-inner">
                                <div class="title-section-card">فاکتور</div>
                            </div>
                            <div class="main_step">



                                @if(count($order) > 0)

                                    <div class="box_archive_prescription">
                                        <div class="row">

                                            @foreach($order as $v)
                                                <div class="item_prescription" onclick="openPrescription(this)">
                                                    <div class="col-xs-12 col-md-12">
                                                        <div class="invite">
                                                            <span>نام بیمار : {{ $v->name }}</span>
                                                            <span>نام محصول : {{ $v->product_sku }}</span>
                                                            <span class="pull-right" style=" margin-left: 0; padding-left: 0; text-align: left;">قیمت کل : {{ number_format($fullTotal[$v->order_id]) }} تومان</span>
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
                                                                    <div class="col-xs-4">فروشنده</div>
                                                                    <?php $pruser = \App\User::where('id', $v->seller)->where('role', 'bonakdar')->first(); ?>
                                                                    <div class="col-xs-8">{{ ($pruser) ? $pruser->name : 'بازار اپتیک' }}</div>
                                                                    <div class="clearfix"></div>
                                                                </div>

                                                                <div class="item_full">
                                                                    <div class="col-xs-4">عامل</div>
                                                                    <?php $pruser = \App\User::where('id', $v->lathe_id)->where('role', 'amel')->first(); ?>
                                                                    <div class="col-xs-8">{{ ($pruser) ? $pruser->name : 'به انتخاب سامانه' }}</div>
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
                                                                    <div class="col-xs-8">{{ ($pruser) ? $pruser->name : 'به انتخاب سامانه' }}</div>
                                                                    <div class="clearfix"></div>
                                                                </div>

                                                                <div class="item_full">
                                                                    <div class="col-xs-4">قیمت</div>
                                                                    <div class="col-xs-8">{{ number_format($v->price) }} تومان</div>
                                                                    <div class="clearfix"></div>
                                                                </div>

                                                                <div class="item_full">
                                                                    <div class="col-xs-4">هزینه لابراتور</div>
                                                                    <div class="col-xs-8">{{ number_format($full_labrator_price[$v->order_id]) }} تومان</div>
                                                                    <div class="clearfix"></div>
                                                                </div>

                                                                <div class="item_full">
                                                                    <div class="col-xs-4">قیمت کل</div>
                                                                    <div class="col-xs-8">{{ number_format($fullTotal[$v->order_id]) }} تومان</div>
                                                                    <div class="clearfix"></div>
                                                                </div>

                                                                <div class="item_full">
                                                                    <div class="col-xs-4">تاریخ تولد</div>
                                                                    <div class="col-xs-8">{{ $v->birth }}</div>
                                                                    <div class="clearfix"></div>
                                                                </div>


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

                                                                                <div class="box_add_item {{ ($v->dodid) ? '' : 'dodidInput' }}">
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

                                                                    <div class="item_full">

                                                                        <div class="col-xs-12 col-md-6 float-left">

                                                                            <div class="row">

                                                                                <div class="box_add_item  {{ ($v->prisma) ? '' : 'prismaInput' }}">
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


                            </div>
                        </div>

                        <div class="zm_step_box active">
                            <div class="main_step">

                                <div class="endfactor">

                                    <div class="invoice_dill">
                                        <div class="row">
                                            <div class="col-xs-6"><span>جمع فاکتور</span></div>
                                            <div class="col-xs-6">{{ number_format($request->orginal_price) }} تومان</div>
                                            <div class="clearfix"></div>
                                            <hr>
                                            @if($request->discount)
                                                <div class="col-xs-6" style="color: red;"><span>تخفیف</span></div>
                                                <div class="col-xs-6" style="color: red;">{{ number_format($request->discount) }} تومان</div>
                                                <div class="clearfix"></div>
                                                <hr>
                                            @endif
                                            <div class="col-xs-6"><span>روش پرداخت</span></div>
                                            <div class="col-xs-6">
                                                @switch($request->payment_method)
                                                    @case('credit') پرداخت از طریق اعتبار @break
                                                    @default پرداخت آنلاین @break
                                                @endswitch
                                            </div>
                                            <div class="clearfix"></div>
                                            <hr>
                                            <div class="color_green">
                                                <div class="col-xs-6"><span>مبلغ پرداخت شده </span></div>
                                                <div class="col-xs-6">{{ number_format($request->price) }} تومان</div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>

                                </div>


                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function openPrescription(button) {
            $(button).toggleClass('open');
        }

        /*$(document).ready(function () {

            $(document).delegate('.item_prescription .invite', 'click', function (e) {
                e.preventDefault();
                $(this).parent().parent().toggleClass('open');
            });

        });*/

    </script>


@endsection
