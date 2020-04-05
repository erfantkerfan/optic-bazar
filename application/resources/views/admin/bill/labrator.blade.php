@extends('admin.layouts.app')

@section('page_name', ' صورت حساب مالی لابراتور ')

@section('content')
    <div class="white-box">
        <div class="portlet-body">
            <div class="clearfix"></div>
            <br>

            <form class="filter_list" method="get" style="direction: rtl">

                <div class="col-md-3 col-xs-12">
                    <div class="form-group">
                        <label for="filter_code">شماره صورت حساب</label>
                        <input type="text" class="form-control" id="filter_code" name="filter_code" value="{{ @$_GET['filter_code'] }}">
                    </div>
                </div>
                <div class="col-md-3 col-xs-12">
                    <div class="form-group">
                        <label for="filter_user">نام لابراتور</label>
                        <input type="text" class="form-control" id="filter_user" name="filter_user" value="{{ @$_GET['filter_user'] }}">
                    </div>
                </div>
                {{--<div class="col-md-3 col-xs-12">
                    <div class="form-group">
                        <label for="filter_checkout">وضعیت مالی</label>
                        <select class="form-control" id="filter_checkout" name="filter_checkout">
                            <option value="">لطفا انتخاب کنید</option>
                            <option value="0" {{ (@$_GET['filter_checkout'] == '0') ? "selected" : "" }} >بستانکار</option>
                            <option value="1" {{ (@$_GET['filter_checkout'] == '1') ? "selected" : "" }}>بدهکار</option>
                            <option value="2" {{ (@$_GET['filter_checkout'] == '2') ? "selected" : "" }}>تسویه شده</option>
                        </select>
                    </div>
                </div>--}}
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
            <div class="table-container">

                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                    <tr role="row" class="heading">
                        <th>شماره صورت حساب</th>
                        <th>نام لابراتور</th>
                        <th>تاریخ سفارش</th>
                        <th> مبلغ کل (تومان)</th>
                        <th>روش پرداخت</th>
                        {{--<th>وضعیت مالی</th>--}}
                        <th>شرح صورت حساب</th>
                        <th></th>
                    </tr>

                    @foreach($request as $k => $item)
                        <?php $labrator = App\User::where('id' , $order_labrator[$item['order_id']])->first() ?>
                        <tr role="row" class="filter">
                            <td style="font-family: Tahoma">BOP-{{ $item['transactions_id'] . '-' . $item['order_id'] }}</td>
                            <td>{{ ($labrator) ? $labrator->name : '' }}</td>
                            <td>{{ jdate('Y/m/d', strtotime($item['transactions_created_at'])) }}</td>
                            <td>{{ number_format($item['price']) }}</td>
                            <td>
                                @if($item['transactions_payment_method'] == 'credit')
                                    <span>پر داخت از طریق اعتبار</span>
                                @else
                                    <span>پر داخت آنلاین</span>
                                @endif
                            </td>
                            {{--<td>
                                @switch($item['checkout_amel'])
                                    @case(0)
                                    بستانکار
                                    @break
                                    @case(1)
                                    بدهکار
                                    @break
                                    @case(2)
                                    تسویه شده
                                    @break
                                @endswitch
                            </td>--}}
                            <td>{{ $item['description'] }}</td>
                            <td>

                                <div class="col-xs-12">
                                    <a class="btn btn-block btn-info btn-rounded" target="_blank" href="{{ url('cp-manager/orders?filter_code=BOP-' . $item['transactions_id']) }}"> مشاهده سفارش </a>
                                </div>

                                {{--<div class="col-xs-6">
                                    <a class="btn btn-block btn-info btn-rounded" target="_blank" href="{{ url('cp-manager/bill/labrator/edit/' . $item['order_id']) }}"> ویرایش </a>
                                </div>--}}

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {!! $request->render() !!}
            <div class="clearfix"></div>

            @else

                <div class="msg">
                    <div class="alert alert-warning alert-info" style="text-align: center">صورت حسابی یافت نشد.</div>
                </div>

            @endif


        </div>

    </div>
@endsection