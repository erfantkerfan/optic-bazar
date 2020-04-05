@extends('admin.layouts.app')

@section('page_name', 'افزودن اسلاید')

@section('content')

    <div class="white-box">

        <form method="post" action="" style="direction: rtl">
            {{ csrf_field() }}

            <div class="form-group row">
                <label for="logo" class="col-md-2 col-form-label text-md-right"></label>

                <div class="col-md-9">

                    <div>

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="{{ (old('video')) ? '' : 'active' }}"><a href="#image" aria-controls="image" role="tab" data-toggle="tab">تصویر</a></li>
                            <li role="presentation" class="{{ (old('video')) ? 'active' : '' }}"><a href="#video" aria-controls="video" role="tab" data-toggle="tab">ویدیو</a></li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane {{ (old('video')) ? '' : 'active' }}" id="image">

                                <link href="{!! asset('assets/adminui/slim/slim.min.css') !!}" rel="stylesheet">
                                <script src="{!! asset('assets/adminui/slim/slim.kickstart.js') !!}"></script>

                                <div class="slime_uploadfile" style="width: 100%;">
                                    <div class="slim"
                                         data-label="تصویر"
                                         data-size="1300,600"
                                         data-ratio="16:7">

                                        @if(old('image') && !file_exists(old('image')))
                                            <img class="imslider" src="{{ url(old('image')) }}" alt=""/>
                                        @endif

                                        <input type="file" name="image" accept=""/>
                                    </div>
                                </div>

                                @if ($errors->has('image'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('image') }}</strong>
                                    </span>
                                @endif

                            </div>
                            <div role="tabpanel" class="tab-pane {{ (old('video')) ? 'active' : '' }}" id="video">

                                <link rel="stylesheet" href="{!! asset('assets/jquery-file-upload/css/jquery.fileupload.css') !!}">
                                <script src="{!! asset('assets/jquery-file-upload/js/vendor/jquery.ui.widget.js') !!}"></script>
                                <script src="{!! asset('assets/jquery-file-upload/js/jquery.fileupload.js') !!}"></script>
                                <div class="images">

                                    <div id="ad_slideshow">


                                        <div class="bac_upload_video">
                                            <span class="btn btn-xs fileinput-button btn-success">

                                                <span class="but_name_image">ویدیو</span>
                                                <span style="display: none;" id="gallery_results_wrapper">
                                                    @if(old('video'))
                                                        <input type="hidden" name="gallery[]" value="{{ old('video') }}">
                                                    @endif
                                                </span>
                                            </span>
                                            <input id="gallery_upload_input" name="video_set" type="file">

                                            <script>
                                                $(document).ready(function(e){


                                                    $.ajaxSetup({
                                                        headers: {
                                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                        }
                                                    });

                                                    $(function () { "use strict";
                                                        var url = '{{url('/upload/video')}}';
                                                        $("#gallery_upload_input").fileupload({
                                                            url: url,
                                                            done: function (e, data) {
                                                                $('#gallery_upload_process_bar .progress-bar').css('width','0');
                                                                if(data.result.indexOf("alert")>=0){

                                                                    //error
                                                                    $("#gallery_upload_process_bar_error").html(data.result);

                                                                }else{

                                                                    //Success
                                                                    var result = data.result.split(',');
                                                                    result = result[0];
                                                                    zm_ufe_apply_single_image_gallery(result);
                                                                    $("#gallery_place_holder").html('<li><img src="{{ asset('assets/images/no_image.png') }}"><div class="buttombox"><button class="remove_image"><i class="fa fa-trash"></i></button></div></li>');
                                                                    $("#gallery_results_wrapper").html('<input type="hidden" name="video" value="'+result+'">');


                                                                }
                                                            },
                                                            progressall: function (e, data) {
                                                                var progress = parseInt(data.loaded / data.total * 100, 10);
                                                                $("#gallery_upload_process_bar .progress-bar").css("width",progress+"%");
                                                            }
                                                        }).prop("disabled", !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : "disabled");
                                                    });
                                                    $('.fileinput-button').click(function(e){
                                                        $('#gallery_upload_process_bar .progress-bar').css('width','0');
                                                    });
                                                    $('#gallery_place_holder').delegate('li button.remove_image','click',function(e){
                                                        $('#gallery_results_wrapper input:nth-child('+($(this).parent().index()+1)+')').remove();
                                                        $(this).parent().parent().remove();
                                                    });
                                                });
                                            </script>


                                            <div id="gallery_upload_process_bar" class="progress">
                                                <div class="progress-bar progress-bar-success"></div>
                                            </div>
                                            <div id="gallery_upload_process_bar_error"></div>
                                            <script>
                                                function zm_ufe_apply_single_image_gallery(image){ }
                                            </script>

                                        </div>
                                        <div class="clearfix"></div>

                                        <ul id="gallery_place_holder">
                                            @if(old('video'))
                                                <li><img src="{{ asset('assets/images/no_image.png') }}"><div class="buttombox"><button class="remove_image"><i class="fa fa-trash"></i></div></li>
                                            @endif

                                        </ul>

                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <div class="form-group row">
                <label for="name" class="col-md-2 col-form-label text-md-right"> عنوان</label>

                <div class="col-md-9">
                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" autofocus>

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
                    <textarea id="description" rows="8"  class="form-control textarea_editor {{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" >{{ old('description') }}</textarea>

                    @if ($errors->has('description'))
                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="link" class="col-md-2 col-form-label text-md-right"> لینک</label>

                <div class="col-md-9">
                    <input id="link" type="text" class="form-control{{ $errors->has('link') ? ' is-invalid' : '' }}" name="link" value="{{ old('link') }}">

                    @if ($errors->has('link'))
                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('link') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-9 offset-md-4">
                    <button type="submit" class="btn btn-info waves-effect waves-light">ثبت اسلاید</button>
                </div>
            </div>
            <div class="clearfix"></div>
        </form>

    </div>

@endsection