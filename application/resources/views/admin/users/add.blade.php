@extends('admin.layouts.app')

@section('page_name', 'افزودن کاربر')

@section('content')

    <div class="white-box">

        <form method="post" action="" class="avatar" style="direction: rtl">
            {{ csrf_field() }}

            <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right">نام و نام خانوادگی	</label>

                <div class="col-md-6">
                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" autofocus>

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
                    <input id="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" >

                    @if ($errors->has('email'))
                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="mobile" class="col-md-4 col-form-label text-md-right">شماره موبایل</label>

                <div class="col-md-6">
                    <input id="mobile" type="text" class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" name="mobile" value="{{ old('mobile') }}" >

                    @if ($errors->has('mobile'))
                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('mobile') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="phone" class="col-md-4 col-form-label text-md-right">شماره تلفن ثابت	</label>

                <div class="col-md-6">
                    <input id="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ old('phone') }}" autofocus>

                    @if ($errors->has('phone'))
                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="status" class="col-md-4 col-form-label text-md-right">نقش کاربر</label>

                <div class="col-md-6">
                    <div class="radio radio-info">
                        <div style="display: inline-block; margin-right: 15px">
                            <input type="radio" id="role_admin" name="role" value="admin" {{ ( old('role') == 'admin') ? "checked" : "" }}>
                            <label for="role_admin">مدیر</label>
                        </div>
                        <div style="display: inline-block; margin-right: 15px">
                            <input type="radio" id="role_user" name="role" value="user" {{ ( old('role') == 'user') ? "checked" : "" }}>
                            <label for="role_user">کاربر</label>
                        </div>
                        <div style="display: inline-block; margin-right: 15px">
                            <input type="radio" id="role_labrator" name="role" value="labrator" {{ ( old('role') == 'labrator') ? "checked" : "" }}>
                            <label for="role_labrator">لابراتور</label>
                        </div>
                        <div style="display: inline-block; margin-right: 15px">
                            <input type="radio" id="role_amel" name="role" value="amel" {{ ( old('role') == 'amel') ? "checked" : "" }}>
                            <label for="role_amel">عامل</label>
                        </div>
                        <div style="display: inline-block; margin-right: 15px">
                            <input type="radio" id="role_bonakdar" name="role" value="bonakdar" {{ ( old('role') == 'bonakdar') ? "checked" : "" }}>
                            <label for="role_bonakdar">بنکدار</label>
                        </div>
                    </div>
                    @if ($errors->has('role'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('role') }}</strong>
                        </span>
                    @endif
                </div>
            </div>


            <div class="form-group row">
                <label for="state" class="col-md-4 col-form-label text-md-right">استان</label>

                <div class="col-md-6">
                    <select id="state" class="form-control{{ $errors->has('state') ? ' is-invalid' : '' }}" name="state">
                        <option value="">لطفا انتخاب کنید</option>
                        @if($province)
                            @foreach($province as $pr)
                                <option value="{{ $pr['name'] }}" {{ (old('state') == $pr['name']) ? 'selected' : '' }}>{{ $pr['name'] }}</option>
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

                <div class="col-md-6">
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

            <div class="form-group row area" style="display: {{ (old('city') == 'تهران') ? 'block' : 'none' }}">
                <label for="area" class="col-md-4 col-form-label text-md-right">منطقه شهرداری</label>

                <div class="col-md-6">
                    <select id="area" class="form-control{{ $errors->has('area') ? ' is-invalid' : '' }}" name="area">
                        <option value="">لطفا انتخاب کنید</option>
                        @for($i = 1 ; $i <= 22; $i++)
                            <option value="{{ $i }}" {{ (old('area') == $i) ? 'selected' : '' }}>منطقه {{ $i }}</option>
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

                <div class="col-md-6">
                    <input id="address" type="text" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" value="{{ old('address') }}" autofocus>

                    @if ($errors->has('address'))
                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="based_in_the_passage" class="col-md-4 col-form-label text-md-right">مستقر در پاساژ</label>

                <div class="col-md-6">
                    <div class="checkbox checkbox-info">
                        <div style="display: inline-block; margin-right: 15px">
                            <input type="checkbox" id="based_in_the_passage" name="passage" {{ ( old('passage') == 'on') ? "checked" : "" }}>
                            <label for="based_in_the_passage">بله</label>
                        </div>
                    </div>
                    @if ($errors->has('role'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('role') }}</strong>
                        </span>
                    @endif
                </div>
            </div>


            <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right">رمز عبور</label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" >

                    @if ($errors->has('password'))
                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-info waves-effect waves-light">ثبت کاربر</button>
                </div>
            </div>
            <div class="clearfix"></div>
        </form>

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

                    @if(old('state'))

            var liveId = '{{ old('state') }}';
            var city = '{{ old('city') }}';

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