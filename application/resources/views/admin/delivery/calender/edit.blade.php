@extends('admin.layouts.app')

@section('page_name', 'ویرایش تحویل تقویمی')

@section('content')

    <div class="white-box">

        <form method="post" action="" class="avatar" style="direction: rtl">
            {{ csrf_field() }}


            <div class="form-group row">
                <label for="areas" class="col-md-2 col-form-label text-md-right">انتخاب مناطق</label>

                <div class="col-md-8">

                    <select multiple data-role="tagsinput" class="form-control{{ $errors->has('areas') ? ' is-invalid' : '' }}" id="areas" name="areas[]">
                        @if(old('areas', json_decode($request->areas)))
                            @foreach(old('areas', json_decode($request->areas)) as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        @endif
                    </select>

                    @if ($errors->has('areas'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('areas') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <hr>
            </div>

            <div class="form-group row">
                <label for="capacity" class="col-md-3 col-form-label text-md-right">طرفیت مجاز در هر بازه</label>

                <div class="col-md-7">
                    <input id="capacity" type="text" class="form-control{{ $errors->has('capacity') ? ' is-invalid' : '' }}" name="capacity" value="{{ old('capacity', $request->capacity) }}">

                    @if ($errors->has('capacity'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('capacity') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="difference_receive" class="col-md-3 col-form-label text-md-right">اختلاف زمانی دریافت فریم (ساعت)</label>

                <div class="col-md-7">
                    <input id="difference_receive" type="text" class="form-control{{ $errors->has('difference_receive') ? ' is-invalid' : '' }}" name="difference_receive" value="{{ old('difference_receive', $request->difference_receive) }}">

                    @if ($errors->has('difference_receive'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('difference_receive') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="difference_delivery" class="col-md-3 col-form-label text-md-right">اختلاف زمانی تحویل (ساعت)</label>

                <div class="col-md-7">
                    <input id="difference_delivery" type="text" class="form-control{{ $errors->has('difference_delivery') ? ' is-invalid' : '' }}" name="difference_delivery" value="{{ old('difference_delivery', $request->difference_delivery) }}">

                    @if ($errors->has('difference_delivery'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('difference_delivery') }}</strong>
                        </span>
                    @endif
                </div>
            </div>


            <div class="form-group row">
                <hr>
            </div>

            <div class="form-group row">
                <label class="col-md-12 col-form-label text-md-right">زمان های تحویل سفارش به مشتری : </label>
            </div>


            <div class="form-group row">

                <div class="col-md-6">

                    <div class="add_time_list">
                        <div class="row">
                            <div class="col-xs-8">
                                <select class="form-control hour_of_delivery" id="hour_of_delivery">
                                    @for($i = 1 ; $i <= 24 ; $i++)
                                        <option value="{{ $i }}">ساعت {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-xs-4">
                                <button type="button" style="width: 100%" id="add_hour_of_delivery" class="btn btn-warning">افزودن ساعت</button>
                            </div>
                        </div>

                        <hr>

                        <div class="full_time_list get" id="list_hour_of_delivery">
                            @if($delivery_time)

                                @foreach($delivery_time as $key => $item)

                                    <div class="item_time receipt"><span>ساعت {{ $item['start'] }} - ساعت {{ $item['end'] }}</span>
                                        <input type="hidden" name="hour_of_delivery[]" value="{{ $item['start'] }}">
                                        <input type="hidden" name="hour_of_delivery_diff[]" value="{{ $item['end'] }}">
                                        <span class="remove_time pull-right"><i class="fa fa-remove"></i></span>
                                    </div>

                                @endforeach

                            @elseif(old('hour_of_delivery'))
                                @foreach(old('hour_of_delivery') as $key => $item)

                                    <div class="item_time receipt"><span>ساعت {{ $item }} - ساعت {{ old('hour_of_delivery_diff')[$key] }}</span>
                                        <input type="hidden" name="hour_of_delivery[]" value="{{ $item }}">
                                        <input type="hidden" name="hour_of_delivery_diff[]" value="{{ old('hour_of_delivery_diff')[$key] }}">
                                        <span class="remove_time pull-right"><i class="fa fa-remove"></i></span>
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
                                <div class="input-group">
                                    <input id="date_of_delivery" type="text" min="1" class="form-control observer" placeholder="تاریخ را وارد کنید.">
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <button type="button" style="width: 100%" id="add_date_of_delivery" class="btn btn-warning">افزودن تاریخ</button>
                            </div>
                        </div>

                        <hr>

                        <div class="full_time_list" id="list_date_of_delivery">

                            @if($delivery_date)

                                @foreach($delivery_date as $key => $item)

                                    <div class="item_time receipt"><span>{{ tr_num(jdate('Y/m/d', strtotime($item))) }}</span>
                                        <input type="hidden" name="date_of_delivery[]" value="{{ tr_num(jdate('Y/m/d', strtotime($item))) }}">
                                        <span class="remove_time pull-right"><i class="fa fa-remove"></i></span>
                                    </div>

                                @endforeach

                            @elseif(old('date_of_delivery'))
                                @foreach(old('date_of_delivery') as $key => $item)

                                    <div class="item_time receipt"><span>{{ $item }}</span>
                                        <input type="hidden" name="date_of_delivery[]" value="{{ $item }}">
                                        <span class="remove_time pull-right"><i class="fa fa-remove"></i></span>
                                    </div>

                                @endforeach
                            @endif

                        </div>
                    </div>

                </div>

            </div>


            <div class="form-group row">
                <hr>
            </div>

            <div class="form-group row">
                <label class="col-md-12 col-form-label text-md-right">زمان های دریافت فریم توسط پیک : </label>
            </div>

            <div class="form-group row">

                <div class="col-md-6">

                    <div class="add_time_list">
                        <div class="row">
                            <div class="col-xs-8">
                                <select class="form-control hour_of_receipt" id="hour_of_receipt">
                                    @for($i = 1 ; $i <= 24 ; $i++)
                                        <option value="{{ $i }}">ساعت {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-xs-4">
                                <button type="button" style="width: 100%" id="add_hour_of_receipt" class="btn btn-warning">افزودن ساعت</button>
                            </div>
                        </div>

                        <hr>

                        <div class="full_time_list get" id="list_hour_of_receipt">

                            @if($receipt_time)

                                @foreach($receipt_time as $key => $item)

                                    <div class="item_time receipt"><span>ساعت {{ $item['start'] }} - ساعت {{ $item['end'] }}</span>
                                        <input type="hidden" name="hour_of_receipt[]" value="{{ $item['start'] }}">
                                        <input type="hidden" name="hour_of_receipt_diff[]" value="{{ $item['end'] }}">
                                        <span class="remove_time pull-right"><i class="fa fa-remove"></i></span>
                                    </div>

                                @endforeach

                            @elseif(old('hour_of_receipt'))
                                @foreach(old('hour_of_receipt') as $key => $item)

                                    <div class="item_time receipt"><span>ساعت {{ $item }} - ساعت {{ old('hour_of_receipt_diff')[$key] }}</span>
                                        <input type="hidden" name="hour_of_receipt[]" value="{{ $item }}">
                                        <input type="hidden" name="hour_of_receipt_diff[]" value="{{ old('hour_of_receipt_diff')[$key] }}">
                                        <span class="remove_time pull-right"><i class="fa fa-remove"></i></span>
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
                                <div class="input-group">
                                    <input id="date_of_receipt" type="text" min="1" class="form-control observer" placeholder="تاریخ را وارد کنید.">
                                    <span class="input-group-addon"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <button type="button" style="width: 100%" id="add_date_of_receipt" class="btn btn-warning">افزودن تاریخ</button>
                            </div>
                        </div>

                        <hr>

                        <div class="full_time_list" id="list_date_of_receipt">

                            @if($receipt_date)

                                @foreach($receipt_date as $key => $item)

                                    <div class="item_time receipt"><span>{{ tr_num(jdate('Y/m/d', strtotime($item))) }}</span>
                                        <input type="hidden" name="date_of_receipt[]" value="{{ tr_num(jdate('Y/m/d', strtotime($item))) }}">
                                        <span class="remove_time pull-right"><i class="fa fa-remove"></i></span>
                                    </div>

                                @endforeach

                            @elseif(old('date_of_receipt'))
                                @foreach(old('date_of_receipt') as $key => $item)

                                    <div class="item_time receipt"><span>{{ $item }}</span>
                                        <input type="hidden" name="date_of_receipt[]" value="{{ $item }}">
                                        <span class="remove_time pull-right"><i class="fa fa-remove"></i></span>
                                    </div>

                                @endforeach
                            @endif

                        </div>
                    </div>

                </div>

            </div>






            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-info waves-effect waves-light">ثبت </button>
                </div>
            </div>
            <div class="clearfix"></div>
        </form>

    </div>




    <script>

        $(document).delegate('.item_time.receipt .remove_time', 'click', function (e) {
            $(this).parent().remove();
        });

        $('#add_hour_of_delivery').click(function () {
            var data_set = parseInt($('#hour_of_delivery').val());

            var time_difference_delivery  =  parseInt($('#difference_delivery').val());

            var diff = data_set + 1;
            if(time_difference_delivery > 0){
                diff = data_set + time_difference_delivery;
            }

            if(diff > 24){
                diff = diff - 24;
            }

            $('#list_hour_of_delivery').append('<div class="item_time receipt"><span>ساعت '+data_set+' - ساعت '+diff+'</span><input type="hidden" name="hour_of_delivery[]" value="'+data_set+'"><input type="hidden" name="hour_of_delivery_diff[]" value="'+diff+'"><span class="remove_time pull-right"><i class="fa fa-remove"></i></span></div>');

        });

        $('#add_date_of_delivery').click(function () {
            var data_set = $('#date_of_delivery').val();
            if(data_set){

                $('#list_date_of_delivery').append('<div class="item_time receipt"><span>'+data_set+'</span><input type="hidden" name="date_of_delivery[]" value="'+data_set+'"><span class="remove_time pull-right"><i class="fa fa-remove"></i></span></div>');

            }
        });



        $('#add_hour_of_receipt').click(function () {
            var data_set = parseInt($('#hour_of_receipt').val());

            var time_difference_delivery  =  parseInt($('#difference_receive').val());

            var diff = data_set + 1;
            if(time_difference_delivery > 0){
                diff = data_set + time_difference_delivery;
            }

            if(diff > 24){
                diff = diff - 24;
            }

            $('#list_hour_of_receipt').append('<div class="item_time receipt"><span>ساعت '+data_set+' - ساعت '+diff+'</span><input type="hidden" name="hour_of_receipt[]" value="'+data_set+'"><input type="hidden" name="hour_of_receipt_diff[]" value="'+diff+'"><span class="remove_time pull-right"><i class="fa fa-remove"></i></span></div>');

        });

        $('#add_date_of_receipt').click(function () {
            var data_set = $('#date_of_receipt').val();
            if(data_set){

                $('#list_date_of_receipt').append('<div class="item_time receipt"><span>'+data_set+'</span><input type="hidden" name="date_of_receipt[]" value="'+data_set+'"><span class="remove_time pull-right"><i class="fa fa-remove"></i></span></div>');

            }
        });

    </script>

@endsection