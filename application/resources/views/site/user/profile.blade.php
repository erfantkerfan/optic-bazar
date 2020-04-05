@extends('site.layouts.app')

@section('content')

    <div class="box_color_pages baclite">
        <div class="product_page_list">

            <div class="zm_order_lists">
                <div class="container">

                    <div class="row">

                        <div class="zm_step_box active">
                            <div class="new-form-inner" style="margin-bottom: 30px">
                                <div class="title-section-card" style="font-size: 16px">پروفایل</div>
                            </div>

                            <div class="main_step">

                                <div class="error clearfix">
                                    @if(Session::has('error'))
                                        <div class="alert alert-danger" style="margin-top: -30px;">{{ Session::get('error') }}</div>
                                    @endif

                                    @if(Session::has('success'))
                                        <div class="alert alert-success" style="margin-top: -30px;">{{ Session::get('success') }}</div>
                                    @endif

                                </div>

                                <form method="post" action="" style="direction: rtl">
                                    {{ csrf_field() }}

                                    <div class="form-group row">
                                        <label for="name" class="col-md-4 col-form-label text-md-right">نام و نام خانوادگی	</label>

                                        <div class="col-md-8">
                                            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', $request->name) }}" autofocus>

                                            @if ($errors->has('name'))
                                                <span class="invalid-feedback">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="email" class="col-md-4 col-form-label text-md-right">ایمیل</label>

                                        <div class="col-md-6">
                                            <input id="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email', $request->email) }}" >

                                            @if ($errors->has('email'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="mobile" class="col-md-4 col-form-label text-md-right">شماره موبایل</label>

                                        <div class="col-md-8">
                                            <input id="mobile" type="text" disabled class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" value="{{ old('mobile', $request->mobile) }}" >
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="phone" class="col-md-4 col-form-label text-md-right">شماره تلفن ثابت	</label>

                                        <div class="col-md-8">
                                            <input id="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ old('phone', $request->phone) }}" autofocus>

                                            @if ($errors->has('phone'))
                                                <span class="invalid-feedback">
                                                <strong>{{ $errors->first('phone') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="state" class="col-md-4 col-form-label text-md-right">استان</label>

                                        <div class="col-md-8">
                                            <select id="state" class="form-control{{ $errors->has('state') ? ' is-invalid' : '' }}" name="state">
                                                <option value="">لطفا انتخاب کنید</option>
                                                @if($province)
                                                    @foreach($province as $pr)
                                                        <option value="{{ $pr['name'] }}" {{ (old('state', $request->state) == $pr['name']) ? 'selected' : '' }}>{{ $pr['name'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>

                                            @if ($errors->has('state'))
                                                <span class="invalid-feedback">
                                                <strong>{{ $errors->first('state') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="city" class="col-md-4 col-form-label text-md-right">شهر</label>

                                        <div class="col-md-8">
                                            <select id="city" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" name="city">
                                                <option value="">لطفا انتخاب کنید</option>
                                            </select>

                                            @if ($errors->has('city'))
                                                <span class="invalid-feedback">
                                                <strong>{{ $errors->first('city') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row area" style="display: {{ (old('city', $request->city) == 'تهران') ? 'block' : 'none' }}">
                                        <label for="area" class="col-md-4 col-form-label text-md-right">منطقه شهرداری</label>

                                        <div class="col-md-8">
                                            <select id="area" class="form-control{{ $errors->has('area') ? ' is-invalid' : '' }}" name="area">
                                                <option value="">لطفا انتخاب کنید</option>
                                                @for($i = 1 ; $i <= 22; $i++)
                                                    <option value="{{ $i }}" {{ (old('area', $request->area) == $i) ? 'selected' : '' }}>منطقه {{ $i }}</option>
                                                @endfor
                                            </select>

                                            @if ($errors->has('area'))
                                                <span class="invalid-feedback">
                                                <strong>{{ $errors->first('area') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="address" class="col-md-4 col-form-label text-md-right">آدرس</label>

                                        <div class="col-md-8">
                                            <input id="address" type="text" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" value="{{ old('address', $request->address) }}" autofocus>

                                            @if ($errors->has('address'))
                                                <span class="invalid-feedback">
                                                <strong>{{ $errors->first('address') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="based_in_the_passage" class="col-md-4 col-form-label text-md-right">مستقر در پاساژ کیخسرو</label>

                                        <div class="col-md-8">
                                            <div class="checkbox checkbox-info">
                                                <div style="display: inline-block; margin-left: 15px">
                                                    <input type="checkbox" id="based_in_the_passage" name="passage" {{ ( old('passage', $request->passage) == 'on') ? "checked" : "" }}>
                                                    <label for="based_in_the_passage" style="padding-right: 27px;">بله</label>
                                                </div>
                                            </div>
                                            @if ($errors->has('passage'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('passage') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <p style="color: red;">در صورتی که نیاز به تغییر رمز عبور دارید این بخش را پر کنید.</p>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label for="old_password" class="col-md-4 col-form-label text-md-right">رمز عبور قبلی</label>

                                        <div class="col-md-8">
                                            <input id="old_password" type="password" class="form-control{{ $errors->has('old_password') ? ' is-invalid' : '' }}" name="old_password" >

                                            @if ($errors->has('old_password'))
                                                <span class="invalid-feedback">
                                                <strong>{{ $errors->first('old_password') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label for="password" class="col-md-4 col-form-label text-md-right"> رمز عبور جدید</label>

                                        <div class="col-md-8">
                                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" >

                                            @if ($errors->has('password'))
                                                <span class="invalid-feedback">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">تکرار رمز عبور جدید</label>

                                        <div class="col-md-8">
                                            <input id="password_confirmation" type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation" >

                                            @if ($errors->has('password_confirmation'))
                                                <span class="invalid-feedback">
                                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row mb-0">

                                        <a class="pull-right" href="{{ url('user/delivery_calender') }}" style=" margin: 11px 12px -7px; color: #00a5f1; font-size: 14px;">تنظیمات تحویل تفویمی</a>

                                        <div class="col-md-6 offset-md-4">
                                            <button type="submit" class="btn btn-info waves-effect waves-light">ثبت اطلاعات</button>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>


    <script>
        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $(document).delegate('#state', 'change', function (e) {
                e.preventDefault();

                var liveitem = $(this);
                var liveId = $(this).val();
                $('.area').css('display', 'none');

                $('#city').html('<option value="">لطفا انتخاب کنید</option>');

                $.ajax({
                    method: 'POST',
                    url: '{{ url('city-list') }}/' + liveId,
                    data: {}
                }).done(function(result){

                    if(result.status == 'success'){
                        result.data['0'].forEach(function(element) {
                            $('#city').append('<option value="'+element['name']+'">'+element['name']+'</option>');
                        });

                    }

                });

            });

            @if(old('state', $request->state))

            var liveId = '{{ old('state', $request->state) }}';
            var city = '{{ old('city', $request->city) }}';

            $.ajax({
                method: 'POST',
                url: '{{ url('city-list') }}/' + liveId,
                data: {}
            }).done(function(result){

                if(result.status == 'success'){
                    result.data['0'].forEach(function(element) {
                        $('#city').append('<option value="'+element['name']+'">'+element['name']+'</option>');
                    });

                    $('#city option[value="'+city+'"]').prop('selected', 'selected');
                }

            });

            @endif

            $(document).delegate('#city', 'change', function (e) {
                e.preventDefault();

                var liveitem = $(this);
                var liveId = $(this).val();

                if(liveId === 'تهران'){
                    $('.area').css('display', 'block');
                }else{
                    $('.area').css('display', 'none');
                }

            });


        })
    </script>


@endsection
