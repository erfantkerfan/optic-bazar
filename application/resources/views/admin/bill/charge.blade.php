@extends('admin.layouts.app')

@section('page_name', ' صورت حساب مالی افزایش اعتبار ')

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
                        <label for="filter_user">نام و نام خانوادگی	</label>
                        <input type="text" class="form-control" id="filter_user" name="filter_user" value="{{ @$_GET['filter_user'] }}">
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
            <div class="table-container">

                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                    <tr role="row" class="heading">
                        <th>شماره صورت حساب</th>
                        <th>نام و نام خانوادگی</th>
                        <th>تاریخ </th>
                        <th> مبلغ کل (تومان)</th>
                        <th>شرح صورت حساب</th>
                    </tr>

                    @foreach($request as $k => $item)
                        <tr role="row" class="filter">
                            <td style="font-family: Tahoma">BOP-{{ $item['id'] }}</td>
                            <td>{{ $item['user_name'] }}</td>
                            <td>{{ jdate('Y/m/d', strtotime($item['transactions_created_at'])) }}</td>
                            <td>{{ number_format($item['price']) }}</td>
                            <td>{{ $item['description'] }}</td>
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