@extends('admin.layouts.app')

@section('page_name', 'تحویل و ارسال')

@section('content')

    <?php use \App\Http\Controllers\SettingController; ?>

    <div class="white-box">

        <form method="get" action="" style="direction: rtl">
            <div class="form-group row">

                <div class="col-md-7">

                    <div class="add_time_list">
                        <div class="row">
                            <div class="col-xs-5">

                                <div class="input-group">
                                    <input type="text" min="1" class="form-control mydatepicker" name="date" value="{{ $date }}" autocomplete="off" placeholder="تاریخ را وارد کنید.">
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>

                            </div>
                            <div class="col-xs-5">
                                <select class="form-control" name="unit">
                                    <option value="group_1" {{ ($unit == 'group_1') ? 'selected' : '' }}>مناطق دسته اول</option>
                                    <option value="group_2" {{ ($unit == 'group_2') ? 'selected' : '' }}>مناطق دسته دوم</option>
                                    <option value="group_3" {{ ($unit == 'group_3') ? 'selected' : '' }}>مناطق دسته سوم</option>
                                </select>
                            </div>

                            <div class="col-xs-2">
                                <button type="submit" style="width: 100%" class="btn btn-info">جستجو تاریخ</button>
                            </div>
                        </div>

                        <hr>


                    </div>
                </div>

                <div class="clearfix"></div>

            </div>

        </form>

        <form method="post" action="" class="avatar" style="direction: rtl">
            {{ csrf_field() }}

            <input type="hidden" name="date" value="{{ $date }}">
            <input type="hidden" name="unit" value="{{ $unit }}">

            <div class="form-group row">

                <div class="col-md-6">

                    <div class="add_time_list">
                        <div class="row">
                            <div class="col-xs-8">
                                <input id="time_set_get" type="number" min="1" class="form-control" placeholder="ساعت دریافت را وارد کنید">
                            </div>
                            <div class="col-xs-4">
                                <button type="button" style="width: 100%" id="add_time_set_get" class="btn btn-warning">افزودن ساعت دریافت</button>
                            </div>
                        </div>

                        <hr>

                        <div class="full_time_list get">

                            @if($calender_get_time)
                                @foreach($calender_get_time as $item)
                                    <div class="item_time">
                                        <span>ساعت {{ $item }}</span>
                                        <input type="hidden" name="calender_get_time[]" value="{{ $item }}">
                                        <span class="remove_time pull-right">
                                        <i class="fa fa-remove"></i>
                                        </span>
                                    </div>
                                @endforeach
                            @endif

                        </div>
                    </div>

                </div>

                <div class="col-md-6">

                    <div class="add_time_list">
                        <div class="row">
                            <div class="col-xs-8">
                                <input id="time_set_send" type="number" min="1" class="form-control" placeholder="ساعت ارسال را وارد کنید">
                            </div>
                            <div class="col-xs-4">
                                <button type="button" style="width: 100%" id="add_time_set_send" class="btn btn-warning">افزودن ساعت ارسال</button>
                            </div>
                        </div>

                        <hr>

                        <div class="full_time_list send">

                            @if($calender_send_time)
                                @foreach($calender_send_time as $item)
                                    <div class="item_time">
                                        <span>ساعت {{ $item }}</span>
                                        <input type="hidden" name="calender_send_time[]" value="{{ $item }}">
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
        $('#add_time_set_get').click(function () {
            var data_set = $('#time_set_get').val();
            $('.full_time_list.get').append('<div class="item_time"><span>ساعت '+data_set+'</span><input type="hidden" name="calender_get_time[]" value="'+data_set+'"><span class="remove_time pull-right"><i class="fa fa-remove"></i></span></div>');
            $('#time_set_get').val('');
        });

        $('#add_time_set_send').click(function () {
            var data_set = $('#time_set_send').val();
            $('.full_time_list.send').append('<div class="item_time"><span>ساعت '+data_set+'</span><input type="hidden" name="calender_send_time[]" value="'+data_set+'"><span class="remove_time pull-right"><i class="fa fa-remove"></i></span></div>');
            $('#time_set_send').val('');
        });

        $('.remove_time').click(function () {
            $(this).parent().remove();
        });
    </script>

@endsection