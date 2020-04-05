@extends('admin.layouts.app')

@section('page_name', 'ویرایش برگه')

@section('content')

    <div class="white-box">

        <form method="post" action="" style="direction: rtl">
            {{ csrf_field() }}

            <div class="form-group row">
                <label for="title" class="col-md-2 col-form-label text-md-right"> عنوان</label>

                <div class="col-md-9">
                    <input id="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{{ old('title',$request->title ) }}" autofocus>

                    @if ($errors->has('title'))
                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="slug" class="col-md-2 col-form-label text-md-right"> Slug</label>

                <div class="col-md-9">
                    <input id="slug" type="text" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}" name="slug" value="{{ old('slug',$request->slug) }}" autofocus>

                    @if ($errors->has('slug'))
                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('slug') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="content" class="col-md-2 col-form-label text-md-right">توضیحات</label>

                <div class="col-md-9">
                    <textarea id="content" rows="18"  class="form-control textarea_editor {{ $errors->has('content') ? ' is-invalid' : '' }}" name="content" >{{ old('content',$request->content) }}</textarea>

                    @if ($errors->has('content'))
                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('content') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="logo" class="col-md-2 col-form-label text-md-right"></label>

                <div class="col-md-9">

                    <link href="{!! asset('assets/adminui/slim/slim.min.css') !!}" rel="stylesheet">
                    <script src="{!! asset('assets/adminui/slim/slim.kickstart.js') !!}"></script>

                    <div class="slime_uploadfile" style="width: 100%;">
                        <div class="slim"
                             data-label="تصویر"
                             data-size="1300,600"
                             data-ratio="16:7">

                            @if(old('image',$request->image) && !file_exists(old('image',$request->image)))
                                <img class="imslider" src="{{ url(old('image',$request->image)) }}" alt=""/>
                            @endif

                            <input type="file" name="image" accept=""/>
                        </div>
                    </div>

                </div>
            </div>

            <div class="form-group row">
                <label for="location" class="col-md-2 col-form-label text-md-right"> موقعیت</label>

                <div class="col-md-9">
                    <select id="location" class="form-control{{ $errors->has('location') ? ' is-invalid' : '' }}" name="location">
                        <option value="">لطفا انتخاب کنید</option>
                        <option value="home" {{ (old('location',$request->location) == 'home') ? 'selected' : '' }}>نمایش در صفحه اصلی</option>
                        <option value="menu_top" {{ (old('location',$request->location) == 'menu_top') ? 'selected' : '' }}>نمایش در منو</option>
                    </select>
                    @if ($errors->has('location'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('location') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-9 offset-md-4">
                    <button type="submit" class="btn btn-info waves-effect waves-light">ثبت برگه</button>
                </div>
            </div>
            <div class="clearfix"></div>
        </form>

    </div>

@endsection