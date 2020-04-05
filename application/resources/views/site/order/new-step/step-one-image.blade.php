<form method="post" class="form_prescription" action="{{ url('/order/new/prescription/image') }}">
    @csrf

    <input type="hidden" name="type_prescription" value="image">
    <div class="row">

        <div class="col-xs-12 col-md-6">

            <div class="row">
                <div class="name_box">
                    <div class="col-xs-6">
                        <label class="block_table">نام و نام خانوادگی بیمار </label>
                        <input type="text" class="input_control form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', @$request->name) }}" autocomplete="off" autofocus>
                    </div>

                    <div class="col-xs-5">
                        <label class="block_table">تاریخ تولد</label>
                        <input type="text" class="observer form-control{{ $errors->has('bir') ? ' is-invalid' : '' }}" name="bir" value="{{ old('bir', @$request->birth) }}" autocomplete="off" >
                    </div>

                    <div class="clearfix"></div>
                </div>
            </div>

        </div>

        <div class="col-xs-12 col-md-6">

            <div class="row product_type_switch">
                <div class="col-xs-12 float-left">
                    <div class="float-left">
                        <h4 class="box-title" style="margin-top: 6px; padding-right: 10px;min-width: 30px">لنز</h4>
                    </div>
                    <div class="float-left" style="padding: 0">
                        <div class="m-b-30 {{ (@$request->type_product == 'optical_glass') ? 'optic' : '' }}" id="product_type_image">
                            <input type="checkbox" name="product_type_image" class="js-switch"  data-color="#feab00" data-secondary-color="#feab00" {{ (@$request->type_product == 'lens') ? '' : 'checked' }} />
                        </div>
                    </div>
                    <div class="float-left">
                        <h4 class="box-title" style="margin-top: 6px; padding-left: 15px">عدسی</h4>
                    </div>
                </div>
            </div>

        </div>

        <div class="clearfix"></div>
        <hr>

        <div class="clearfix"></div>

        <link rel="stylesheet" href="{!! asset('assets/jquery-file-upload/css/jquery.fileupload.css') !!}">
        <script src="{!! asset('assets/jquery-file-upload/js/vendor/jquery.ui.widget.js') !!}"></script>
        <script src="{!! asset('assets/jquery-file-upload/js/jquery.fileupload.js') !!}"></script>

        <div class="col-xs-12">
            <div class="main_des_box title addimage_client">
                <div class="images">

                    <div id="ad_slideshow">

                        <?php $images = json_decode(@$request->image); ?>

                        <div class="bac_upload_video">
                            <span class="btn btn-xs fileinput-button btn-success">

                                <span class="but_name_image"><i class="fa fa-image"></i> افزودن تصویر نسخه</span>
                                <span style="display: none;" id="gallery_results_wrapper">
                                    @if($images)
                                        @foreach($images as $image)
                                            <input type="hidden" name="image[]" value="{{ $image }}">
                                        @endforeach
                                    @endif
                                </span>
                            </span>
                            <input id="gallery_upload_input" name="image" type="file">

                            <script>
                                $(document).ready(function(e){


                                    $.ajaxSetup({
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        }
                                    });

                                    $(function () { "use strict";
                                        var url = '{{url('/upload')}}';
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
                                                    $("#gallery_place_holder").append('<li><img src="'+result+'"><div class="buttombox"><button class="remove_image"><i class="fa fa-trash"></i></button></div></li>');
                                                    $("#gallery_results_wrapper").append('<input type="hidden" name="image[]" value="'+result+'">');


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
                            @if($images)
                                @foreach($images as $image)
                                        <li><img src="{{ $image }}"><div class="buttombox"><button class="remove_image"><i class="fa fa-trash"></i></button></div></li>
                                @endforeach
                            @endif

                        </ul>

                    </div>

                </div>
            </div>
        </div>

        <div class="col-xs-12 col-md-6 float-left">
        </div>

        <div class="col-xs-12 col-md-6">

            <div class="lathe_group" style="display: {{ (@$request->type_product == 'optical_glass') ? 'block' : 'none' }};">

                <div class="row box_lathe_btn_image">
                    <div class="col-xs-12">
                        <div class="checkbox info" id="item_lathe_vizit" style="margin-bottom: 31px;">
                            <input type="checkbox" id="lathe_vizit" name="lathe">
                            <label for="lathe_vizit"> تراشیده شود</label>
                        </div>
                    </div>
                    <div class="lathe_main_cox">
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="item">
                                    <div class="col-xs-2 float-left">
                                        <label class="top_table"></label>
                                    </div>
                                    <div class="col-xs-2 float-left">
                                        <label class="top_table">PD</label>
                                    </div>
                                    <div class="col-xs-2 float-left">
                                        <label class="top_table">Height</label>
                                    </div>
                                    <div class="col-xs-1 float-left">
                                    </div>

                                    <div class="clearfix"></div>

                                    <div class="boxsearch">
                                        <div class="col-xs-2 float-left">
                                            <label class="left_table">Right eye</label>
                                        </div>
                                        <div class="col-xs-2 float-left">
                                            <select class="form-control" name="rpd">
                                                @foreach($pd as $v)
                                                    <option value="{{ $v }}" {{ (old('rpd') == $v) ? 'selected' : '' }}>{{ $v }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-xs-2 float-left">
                                            <input type="text" class="form-control{{ $errors->has('rheight') ? ' is-invalid' : '' }}" name="rheight" value="{{ old('rheight') }}" autocomplete="off" >
                                        </div>
                                        <div class="col-xs-1 float-left">
                                        </div>

                                        <div class="clearfix"></div>
                                    </div>

                                    <div class="clearfix"></div>

                                    <div class="boxsearch">
                                        <div class="col-xs-2 float-left">
                                            <label class="left_table">Left eye</label>
                                        </div>
                                        <div class="col-xs-2 float-left">
                                            <select class="form-control" name="lpd">
                                                @foreach($pd as $v)
                                                    <option value="{{ $v }}" {{ (old('lpd') == $v) ? 'selected' : '' }}>{{ $v }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-xs-2 float-left">
                                            <input type="text" class="form-control{{ $errors->has('lheight') ? ' is-invalid' : '' }}" name="lheight" value="{{ old('lheight') }}" autocomplete="off" >
                                        </div>
                                        <div class="col-xs-1 float-left">
                                        </div>

                                        <div class="clearfix"></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

            </div>

            <div class="description_order">

                <div class="boxsearch">
                    <textarea class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" placeholder="توضیحات تکمیلی" >{{ old('description') }}</textarea>
                </div>

            </div>
        </div>

        <div class="clearfix"></div>

        <div class="col-xs-12 col-md-6">

            <div class="row">
                <div class="col-xs-12 buttomSend">
                    <button type="submit" class="btn btn-info">گام بعدی</button>
                </div>
            </div>

        </div>


        <div class="clearfix"></div>

    </div>

</form>