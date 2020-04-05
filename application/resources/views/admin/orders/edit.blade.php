@extends('admin.layouts.app')

@section('page_name', 'ویرایش برند')

@section('content')

    <div class="white-box">

            <div class="zm_step_box active">
                <div class="main_step">

                    <div class="header_factor">
                        <div class="row">
                            <div class="col-xs-4">
                                <div class="info_user">
                                    <p><span>نام و نام خانوادگی : </span> {{ $User->name }}</p>
                                    <p><span>شماره همراه : </span> {{ $User->mobile }}</p>
                                    <p><span>ایمیل : </span> {{ $User->email }}</p>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="info_user">
                                    <p><span>کد پیگیری : </span> {{ $request->id }}</p>
                                    <p><span>تاریخ سفارش : </span> <span style="direction: ltr">{{ jdate('Y/m/d', strtotime($request->created_at)) }}</span></p>
                                    <p><span>وضعیت سفارش : </span>

                                        @switch($request->status)
                                            @case('paid')
                                            پرداخت شده
                                            @break
                                            @case('unpaid')
                                            پرداخت نشده
                                            @break
                                            @case('pending')
                                            در انتظار پرداخت
                                            @break
                                        @endswitch

                                    </p>
                                </div>
                            </div>
                            <div class="col-xs-2"> <img src="{!! asset('assets/images/logo.png') !!}"></div>
                            <div class="clearfix"></div>
                        </div>
                    </div>


                </div>
            </div>

        <div class="clearfix"></div>

    </div>


    <div class="white-box">




        @if(count($order) > 0)

            <div class="box_archive_prescription">
                <div class="row">

                    @foreach($order as $v)
                        <div class="item_prescription" onclick="openPrescription(this)">
                            <div class="col-xs-12 col-md-12">
                                <div class="invite">
                                    <span>نام بیمار : {{ $v->name }}</span>
                                    <span>نوع نسخه : {{ ($v->type == "import") ? "تایپ شده" : "تصویر" }}</span>
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
                                            <div class="col-xs-4">قیمت</div>
                                            <div class="col-xs-8">{{ number_format($v->price) }} تومان</div>
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
                                            <div class="item_full">
                                                <div class="col-xs-4">Right</div>
                                                <div class="col-xs-8">
                                                    <div class="row">

                                                        <div class="item_full">
                                                            <div class="col-xs-4">تعداد</div>
                                                            <div class="col-xs-8">{{ $v->rcount }}</div>
                                                            <div class="clearfix"></div>
                                                        </div>

                                                        <div class="item_full">
                                                            <div class="col-xs-4">Sph</div>
                                                            <div class="col-xs-8">{{ $v->rsph }}</div>
                                                            <div class="clearfix"></div>
                                                        </div>

                                                        <div class="item_full">
                                                            <div class="col-xs-4">Cyl</div>
                                                            <div class="col-xs-8">{{ $v->rcyl }}</div>
                                                            <div class="clearfix"></div>
                                                        </div>

                                                        <div class="item_full">
                                                            <div class="col-xs-4">Axis</div>
                                                            <div class="col-xs-8">{{ $v->raxis }}</div>
                                                            <div class="clearfix"></div>
                                                        </div>

                                                        @if($v->dodid)
                                                            <div class="item_full">
                                                                <div class="col-xs-4">Add</div>
                                                                <div class="col-xs-8">{{ $v->radd }}</div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        @endif

                                                        @if($v->dodid_rdia)
                                                            <div class="item_full">
                                                                <div class="col-xs-4">Dia</div>
                                                                <div class="col-xs-8">{{ $v->dodid_rdia }}</div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        @endif

                                                        @if($v->dodid_ripd)
                                                            <div class="item_full">
                                                                <div class="col-xs-4">IPD</div>
                                                                <div class="col-xs-8">{{ $v->dodid_ripd }}</div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        @endif

                                                        @if($v->dodid_rprism)
                                                            <div class="item_full">
                                                                <div class="col-xs-4">Prism</div>
                                                                <div class="col-xs-8">{{ $v->dodid_rprism }}</div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        @endif

                                                        @if($v->dodid_rprism_base)
                                                            <div class="item_full">
                                                                <div class="col-xs-4">Prism base/curve</div>
                                                                <div class="col-xs-8">{{ $v->dodid_rprism_base }}</div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        @endif

                                                        @if($v->dodid_rcorridor)
                                                            <div class="item_full">
                                                                <div class="col-xs-4">corridor length</div>
                                                                <div class="col-xs-8">{{ $v->dodid_rcorridor }}</div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        @endif

                                                        @if($v->dodid_rdec)
                                                            <div class="item_full">
                                                                <div class="col-xs-4">DEC</div>
                                                                <div class="col-xs-8">{{ $v->dodid_rdec }}</div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        @endif

                                                        @if($v->prisma && $v->prisma_rprisma1)
                                                            <div class="item_full">
                                                                <div class="col-xs-4">Prism 1</div>
                                                                <div class="col-xs-8">{{ $v->prisma_rprisma1 }}</div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        @endif

                                                        @if($v->prisma && $v->prisma_rdegrees1)
                                                            <div class="item_full">
                                                                <div class="col-xs-4">Degrees 1</div>
                                                                <div class="col-xs-8">{{ $v->prisma_rdegrees1 }}</div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        @endif

                                                        @if($v->prisma && $v->prisma_rprisma2)
                                                            <div class="item_full">
                                                                <div class="col-xs-4">Prism 2</div>
                                                                <div class="col-xs-8">{{ $v->prisma_rprisma2 }}</div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        @endif

                                                        @if($v->prisma && $v->prisma_rdegrees2)
                                                            <div class="item_full">
                                                                <div class="col-xs-4">Degrees 2</div>
                                                                <div class="col-xs-8">{{ $v->prisma_rdegrees2 }}</div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        @endif

                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>

                                            <div class="item_full">
                                                <div class="col-xs-4">Left</div>
                                                <div class="col-xs-8">
                                                    <div class="row">

                                                        <div class="item_full">
                                                            <div class="col-xs-4">تعداد</div>
                                                            <div class="col-xs-8">{{ $v->lcount }}</div>
                                                            <div class="clearfix"></div>
                                                        </div>

                                                        <div class="item_full">
                                                            <div class="col-xs-4">Sph</div>
                                                            <div class="col-xs-8">{{ $v->lsph }}</div>
                                                            <div class="clearfix"></div>
                                                        </div>

                                                        <div class="item_full">
                                                            <div class="col-xs-4">Cyl</div>
                                                            <div class="col-xs-8">{{ $v->lcyl }}</div>
                                                            <div class="clearfix"></div>
                                                        </div>

                                                        <div class="item_full">
                                                            <div class="col-xs-4">Axis</div>
                                                            <div class="col-xs-8">{{ $v->laxis }}</div>
                                                            <div class="clearfix"></div>
                                                        </div>

                                                        @if($v->dodid)
                                                            <div class="item_full">
                                                                <div class="col-xs-4">Add</div>
                                                                <div class="col-xs-8">{{ $v->ladd }}</div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        @endif



                                                        @if($v->dodid_ldia)
                                                            <div class="item_full">
                                                                <div class="col-xs-4">Dia</div>
                                                                <div class="col-xs-8">{{ $v->dodid_ldia }}</div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        @endif

                                                        @if($v->dodid_lipd)
                                                            <div class="item_full">
                                                                <div class="col-xs-4">IPD</div>
                                                                <div class="col-xs-8">{{ $v->dodid_lipd }}</div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        @endif

                                                        @if($v->dodid_lprism)
                                                            <div class="item_full">
                                                                <div class="col-xs-4">Prism</div>
                                                                <div class="col-xs-8">{{ $v->dodid_lprism }}</div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        @endif

                                                        @if($v->dodid_lprism_base)
                                                            <div class="item_full">
                                                                <div class="col-xs-4">Prism base/curve</div>
                                                                <div class="col-xs-8">{{ $v->dodid_lprism_base }}</div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        @endif

                                                        @if($v->dodid_lcorridor)
                                                            <div class="item_full">
                                                                <div class="col-xs-4">corridor length</div>
                                                                <div class="col-xs-8">{{ $v->dodid_lcorridor }}</div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        @endif

                                                        @if($v->dodid_ldec)
                                                            <div class="item_full">
                                                                <div class="col-xs-4">DEC</div>
                                                                <div class="col-xs-8">{{ $v->dodid_ldec }}</div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        @endif

                                                        @if($v->prisma && $v->prisma_lprisma1)
                                                            <div class="item_full">
                                                                <div class="col-xs-4">Prism 1</div>
                                                                <div class="col-xs-8">{{ $v->prisma_lprisma1 }}</div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        @endif

                                                        @if($v->prisma && $v->prisma_ldegrees1)
                                                            <div class="item_full">
                                                                <div class="col-xs-4">Degrees 1</div>
                                                                <div class="col-xs-8">{{ $v->prisma_ldegrees1 }}</div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        @endif

                                                        @if($v->prisma && $v->prisma_lprisma2)
                                                            <div class="item_full">
                                                                <div class="col-xs-4">Prism 2</div>
                                                                <div class="col-xs-8">{{ $v->prisma_lprisma2 }}</div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        @endif

                                                        @if($v->prisma && $v->prisma_ldegrees2)
                                                            <div class="item_full">
                                                                <div class="col-xs-4">Degrees 2</div>
                                                                <div class="col-xs-8">{{ $v->prisma_ldegrees2 }}</div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        @endif

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


    <div class="white-box">

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


            <div class="clearfix"></div>

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