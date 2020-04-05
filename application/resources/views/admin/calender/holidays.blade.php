@extends('admin.layouts.app')

@section('page_name', 'روزهای تعطیل')

@section('content')

    <?php use \App\Http\Controllers\SettingController; ?>

    <div class="white-box">

        <form method="post" action="" class="avatar" style="direction: rtl">
            {{ csrf_field() }}

            <div class="form-group row">

                <div class="col-md-6">
                    <div class="add_date_list">

                        <div class="row">
                            <div class="col-xs-10">

                                <div class="input-group">
                                    <input id="date_set" type="text" min="1" class="form-control observer" placeholder="تاریخ تعطیلات زا وارد کنید.">
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>

                            </div>
                            <div class="col-xs-2">
                                <button type="button" style="width: 100%" id="add_date_set" class="btn btn-warning">افزودن تاریخ</button>
                            </div>
                        </div>

                        <hr>

                        <div class="full_date_list">

                            @if($occupiedTime)
                                @foreach($occupiedTime as $date)
                                    <div class="item_date">
                                        <span>روز {{ $date }}</span>
                                        <input type="hidden" name="calender_date_off[]" value="{{ $date }}">
                                        <span class="remove_time pull-right">
                                        <i class="fa fa-remove"></i>
                                        </span>
                                    </div>
                                @endforeach
                            @endif

                        </div>

                    </div>
                </div>

            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-info waves-effect waves-light">ثبت تنظیمات</button>
                </div>
            </div>
            <div class="clearfix"></div>
        </form>

    </div>


    <script>

        $('.remove_time').click(function () {
            $(this).parent().remove();
        });

        $('#add_date_set').click(function () {
            var data_set = $('#date_set').val();
            $('.full_date_list').append('<div class="item_date"><span>روز '+data_set+'</span><input type="hidden" name="calender_date_off[]" value="'+data_set+'"><span class="remove_time pull-right"><i class="fa fa-remove"></i></span></div>');
            $('#date_set').val('');
        });

    </script>

@endsection