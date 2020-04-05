@extends('admin.layouts.app')

@section('page_name', 'افزودن پیام')

@section('content')

    <div class="white-box">

        <form method="post" action="" style="direction: rtl">
            {{ csrf_field() }}

            <div class="form-group row">
                <label for="user_id" class="col-md-2 col-form-label text-md-right"> دریافت کننده</label>

                <div class="col-md-9">
                    <select id="user_id" class="select2 form-control{{ $errors->has('user_id') ? ' is-invalid' : '' }}" name="user_id">
                        <option value="">لطفا انتخاب کنید</option>
                        @foreach($userList as $v)
                            <option value="{{ $v['id'] }}" {{ (old('user_id') == $v['id']) ? 'selected' : '' }}>{{ $v['name'] . ' (' . $v['mobile'] . ')' }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('user_id'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('user_id') }}</strong>
                        </span>
                    @endif
                </div>

            </div>

            <div class="form-group row">
                <label for="content" class="col-md-2 col-form-label text-md-right">پیام</label>

                <div class="col-md-9">
                    <textarea id="content" rows="18"  class="form-control textarea_editor {{ $errors->has('content') ? ' is-invalid' : '' }}" name="content" >{{ old('content') }}</textarea>

                    @if ($errors->has('content'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('content') }}</strong>
                        </span>
                    @endif
                </div>
            </div>


            <div class="form-group row mb-0">
                <div class="col-md-9 offset-md-4">
                    <button type="submit" class="btn btn-info waves-effect waves-light">ارسال پیام</button>
                </div>
            </div>
            <div class="clearfix"></div>
        </form>

    </div>

@endsection