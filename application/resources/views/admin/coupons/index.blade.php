@extends('admin.layouts.app')

@section('page_name', 'کوپن ها')

@section('content')

    <div class="white-box">

        <div class="portlet-body">

            <div class="table-container">

                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                    <tr role="row" class="heading">
                        <th>ردیف</th>
                        <th>کد تخفیف</th>
                        <th>تعداد استفاده شده</th>
                        <th>نحوه تخفیف</th>
                        <th>مبلغ کوپن</th>
                        <th>تعداد مصرف</th>
                        <th>تاریخ شروع</th>
                        <th>تاریخ انقضا</th>
                        <th>اعمال</th>
                    </tr>

                    @foreach($request as $k => $item)
                        <tr role="row" class="filter">
                            <td>{{ $k + 1 }}</td>
                            <td>{{ $item['coupon_key'] }}</td>
                            <?php
                            $userCount = \App\couponUsed::where('coupon_id', $item['id'])->count();
                            ?>
                            <td>{{ number_format($userCount) }} بار</td>
                            <td>
                                @switch($item['discount_type'])
                                    @case('percent')
                                    تخفیف درصدی
                                    @break
                                    @case('fixed_product')
                                    تخفيف ثابت محصول (تومان)
                                    @break
                                @endswitch
                            </td>
                            <td>{{ number_format($item['coupon_amount']) }}</td>
                            <td>
                                @switch($item['disposable'])
                                    @case(1)
                                   برای مصرف یک بار
                                    @break
                                    @default
                                    بی نهایت
                                    @break
                                @endswitch
                            </td>
                            <td>{{ jdate('Y/m/d', strtotime($item['start_date'])) }}</td>
                            <td>{{ jdate('Y/m/d', strtotime($item['expiry_date'])) }}</td>
                            <td>
                                <div class="col-xs-6">
                                    <a class="btn btn-block btn-info btn-rounded request_but" href="{{ url('cp-manager/coupon/edit/' . $item['id']) }}"> ویرایش </a>
                                </div>
                                <div class="col-xs-6">
                                    <a class="btn btn-block btn-danger btn-rounded delete-warning" href="{{ url('cp-manager/coupon/delete/' . $item['id']) }}"> حذف </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {!! $request->render() !!}
            <div class="clearfix"></div>
        </div>



        <script>
            $('.delete-warning').click(function(e){
                e.preventDefault();
                var hreflink = $(this).attr('href');
                swal({
                    title: "",
                    text: "آیا از حذف این اسلاید مطمئن هستید ؟",
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

        </script>

    </div>
@endsection