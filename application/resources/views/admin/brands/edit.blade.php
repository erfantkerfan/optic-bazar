@extends('admin.layouts.app')

@section('page_name', 'ویرایش برند')

@section('content')

    <div class="white-box">

        <form method="post" action="" style="direction: rtl">
            {{ csrf_field() }}

            <div class="form-group row">
                <label for="logo" class="col-md-2 col-form-label text-md-right"></label>

                <div class="col-md-9">

                    <link href="{!! asset('assets/adminui/slim/slim.min.css') !!}" rel="stylesheet">
                    <script src="{!! asset('assets/adminui/slim/slim.kickstart.js') !!}"></script>

                    <div class="slime_uploadfile" style="width: 25%; margin: 0 auto">
                        <div class="slim"
                             data-label="لوگو"
                             data-size="260,261"
                             data-ratio="1:1">

                            @if(old('logo', $request->logo))
                                <img class="imslider" src="{{ old('logo', $request->logo) }}" alt=""/>
                            @endif

                            <input type="file" name="logo" accept=""/>
                        </div>
                    </div>

                    @if ($errors->has('logo'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('logo') }}</strong>
                        </span>
                    @endif

                </div>
            </div>

            <div class="form-group row">
                <label for="name" class="col-md-2 col-form-label text-md-right"> نام برند</label>

                <div class="col-md-9">
                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', $request->name) }}" autofocus>

                    @if ($errors->has('name'))
                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="description" class="col-md-2 col-form-label text-md-right">توضیحات</label>

                <div class="col-md-9">
                    <textarea id="description" rows="8"  class="form-control textarea_editor {{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" >{{ old('description', $request->description) }}</textarea>

                    @if ($errors->has('description'))
                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="profit" class="col-md-2 col-form-label text-md-right"> سود (%)</label>

                <div class="col-md-9">
                    <input id="profit" type="number" min="0" max="100" class="form-control{{ $errors->has('profit') ? ' is-invalid' : '' }}" name="profit" value="{{ old('profit', $request->profit) }}">

                    @if ($errors->has('profit'))
                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('profit') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-9 offset-md-4">
                    <button type="submit" class="btn btn-info waves-effect waves-light">ثبت برند</button>
                </div>
            </div>
            <div class="clearfix"></div>
        </form>

    </div>

@endsection