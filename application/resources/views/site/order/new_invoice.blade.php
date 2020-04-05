@extends('site.layouts.app')

@section('content')

    <div class="box_color_pages">
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


                        <div class="tab_order_rate">


                            @if(count($request) > 0)

                                <div class="m-card header_box_app">
                                    <div class="header_order">
                                        <a href="{{ url('/order/payment') }}" class="btn btn-success custom_but next_order_finish pull-right">تکمیل فرایند خرید</a>
                                        <a href="{{ url('/order/new') }}" class="btn btn-info custom_but add_new_order">افزودن سفارش بعدی</a>
                                    </div>
                                </div>

                                <div class="box_archive_prescription">
                                    <div class="row">

                                        @foreach($request as $v)
                                            <div class="item_prescription" onclick="openPrescription(this)">
                                                <div class="col-xs-12 col-md-11">
                                                    <div class="invite">
                                                        <span>نام بیمار : {{ $v->name }}</span>
                                                        <span>نوع نسخه : {{ ($v->type == "import") ? "تایپ شده" : "تصویر" }}</span>
                                                        {{--<span>نام محصول : {{ $v->product_sku }}</span>--}}
                                                        <span>تاریخ ثبت سفارش : {{ jdate('Y/m/d', strtotime($v->created)) }}</span>
                                                        <span>قیمت کل : {{ number_format($fullTotal[$v->order_id]) }} تومان</span>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-1 text-right">
                                                    <a class="btn btn-danger btn-circle delete-warning" href="{{ url('order/delete/' . $v->key) }}" title="حذف این سفارش"> <i class="fa fa-trash"></i> </a>
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

                                <div class="m-card header_box_app" style="margin-top: 15px">
                                    <div class="header_order">
                                        <div class="pull-right" style="margin: 10px 0">
                                            <div class="sum_invoice">جمع کل فاکتور : <span class="full_total" data-key="{{ $totalInvoice }}">{{ number_format($totalInvoice) }} تومان</span></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-8 col-md-4">
                                                <input id="discount_cod" autocomplete="off"  type="text" class="form-control" name="discount" placeholder="کد تخفیف خود را وارد کنید">
                                            </div>
                                            <div class="col-xs-12 col-md-5">
                                                <div class="inerror"></div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>

                            @else
                                <div class="msg">
                                    <div class="alert alert-warning alert-info" style="text-align: center">سبد خرید شما خالی است.</div>
                                </div>
                            @endif



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

        $(document).ready(function () {

            $(document).delegate('.delete-warning', 'click', function (e) {
                e.preventDefault();
                var hreflink = $(this).attr('href');
                swal({
                    title: "",
                    text: "آیا از حذف این سفارش مطمئن هستید ؟",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: "خیر نیازی نیست",
                    confirmButtonText: "بله اطمینان دارم",
                    closeOnConfirm: false
                }, function(){
                    window.location.href = hreflink;
                });
            });

            /*$(document).delegate('.item_prescription .invite', 'click', function (e) {
                e.preventDefault();
                $(this).parent().parent().toggleClass('open');
            });*/


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var zm_ajax_handler;
            var timeOut;
            $('#discount_cod').keyup(function(){
                var key = $('#discount_cod').val();
                clearTimeout(timeOut);
                if(typeof zm_ajax_handler == 'object'){
                    zm_ajax_handler.abort();
                }

                if(key.length>4){
                    $('.inerror').html("").addClass('loading');
                    timeOut = setTimeout(function () {

                        zm_ajax_handler = $.ajax({
                            url:"{{ url('/order/discount-set') }}",

                            type:'POST',
                            data: {discount_cod : $('#discount_cod').val()},

                            success:function(result){

                                $('.inerror').removeClass("active");
                                var old_total = $('.full_total').attr('data-key');

                                if(result.status === "error"){

                                    old_total = Number(old_total).toLocaleString("us-US", {minimumFractionDigits: 0});
                                    $('.inerror').html(result.msg).removeClass('loading').removeClass('success');
                                    $('.full_total').html(old_total + ' تومان');

                                }
                                if(result.status === "success"){

                                    if(result.message_show === "on") {
                                        swal({
                                            title: "",
                                            text: result.message,
                                            type: "",
                                            showCancelButton: true,
                                            confirmButtonColor: "#DD6B55",
                                            confirmButtonText: "{{ trans('v2.Accept') }}",
                                            cancelButtonText: "{{ trans('v2.Decline') }}",
                                            closeOnConfirm: false,
                                            closeOnCancel: false
                                        }, function (isConfirm) {
                                        });
                                    }


                                    var discount = parseInt(result.discount);
                                    var sum_discount = Number(discount).toLocaleString("us-US", {minimumFractionDigits: 0});

                                    var total_sub = old_total - discount;
                                    total_sub = (total_sub <= 0) ? 0 : total_sub;
                                    total_sub = Number(total_sub).toLocaleString("us-US", {minimumFractionDigits: 0});

                                    $('#discount_cod').addClass('success').attr('disabled', 'disabled');
                                    $('.inerror').html('تخفیف شما: <span>'  + sum_discount + ' تومان</span>').removeClass('loading').addClass('success');

                                    $('.full_total').html(total_sub + ' تومان');
                                }

                            }
                        });

                    },2000);
                }else{
                    $('.inerror').html("").removeClass('loading').removeClass('success');
                    $('#discount_cod').removeClass('success');
                }
            });


        });

    </script>

@endsection
