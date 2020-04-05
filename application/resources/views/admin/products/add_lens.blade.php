@extends('admin.layouts.app')

@section('page_name', 'افزودن لنز')

@section('content')

    <div class="white-box">

        <form method="post" action="" style="direction: rtl">
            {{ csrf_field() }}

            <div class="form-group row">
                <label for="seller" class="col-md-4 col-form-label text-md-right">نام فروشنده 	</label>

                <div class="col-md-6">
                    <select id="seller" class="form-control{{ $errors->has('seller') ? ' is-invalid' : '' }}" name="seller">
                        <option value="0">انتخاب پیش فرض</option>
                        @foreach($bonakdars as $bonak)
                            <option value="{{ $bonak['id'] }}" {{ (old('seller') == $bonak['id']) ? 'selected' : '' }}>{{ $bonak['name'] }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('seller'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('seller') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="sku" class="col-md-4 col-form-label text-md-right">کد محصول </label>

                <div class="col-md-6">
                    <input id="sku" type="text" class="form-control{{ $errors->has('sku') ? ' is-invalid' : '' }}" name="sku" value="{{ old('sku') }}" autofocus>

                    @if ($errors->has('sku'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('sku') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="country" class="col-md-4 col-form-label text-md-right">نام کشور سازنده 	</label>

                <div class="col-md-6">
                    <select id="country" class="form-control{{ $errors->has('country') ? ' is-invalid' : '' }}" name="country">
                        <option value="">لطفا انتخاب کنید</option>
                        @foreach($country as $c)
                            <option value="{{ $c['title'] }}" {{ (old('country') == $c['title']) ? 'selected' : '' }}>{{ $c['title'] }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('country'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('country') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="brand" class="col-md-4 col-form-label text-md-right"> برند </label>

                <div class="col-md-6">
                    <select id="brand" class="form-control{{ $errors->has('brand') ? ' is-invalid' : '' }}" name="brand">
                        <option value="">لطفا انتخاب کنید</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand['id'] }}" {{ (old('brand') == $brand['id']) ? 'selected' : '' }}>{{ $brand['name'] }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('brand'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('brand') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <hr>

            {{--speshial data--}}

            <div class="form-group row">
                <label for="curvature" class="col-md-4 col-form-label text-md-right"> انحنا لنز </label>

                <div class="col-md-6">
                    <select id="curvature" class="form-control{{ $errors->has('curvature') ? ' is-invalid' : '' }}" name="curvature">
                        <option value="">لطفا انتخاب کنید</option>
                        @foreach($curvatures as $curvature)
                            <option value="{{ $curvature }}" {{ (old('curvature') == $curvature) ? 'selected' : '' }}>{{ $curvature }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('curvature'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('curvature') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="structure" class="col-md-4 col-form-label text-md-right"> ساختار </label>

                <div class="col-md-6">
                    <select id="structure" class="form-control{{ $errors->has('structure') ? ' is-invalid' : '' }}" name="structure">
                        <option value="">لطفا انتخاب کنید</option>
                        @foreach($structures as $structure)
                            <option value="{{ $structure }}" {{ (old('structure') == $structure) ? 'selected' : '' }}>{{ $structure }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('structure'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('structure') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="consumption_period" class="col-md-4 col-form-label text-md-right"> دوره مصرف </label>

                <div class="col-md-6">
                    <select id="consumption_period" class="form-control{{ $errors->has('consumption_period') ? ' is-invalid' : '' }}" name="consumption_period">
                        <option value="">لطفا انتخاب کنید</option>
                        @foreach($consumption_period as $period)
                            <option value="{{ $period }}" {{ (old('consumption_period') == $period) ? 'selected' : '' }}>{{ $period }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('consumption_period'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('consumption_period') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="color" class="col-md-4 col-form-label text-md-right"> نام رنگ </label>

                <div class="col-md-6">
                    <input id="color" type="text" class="form-control{{ $errors->has('color') ? ' is-invalid' : '' }}" name="color" value="{{ old('color') }}">

                    @if ($errors->has('color'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('color') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="color_description" class="col-md-4 col-form-label text-md-right"> جزئیات رنگ </label>

                <div class="col-md-6">
                    <input id="color_description" type="text" class="form-control{{ $errors->has('color_description') ? ' is-invalid' : '' }}" name="color_description" value="{{ old('color_description') }}">

                    @if ($errors->has('color_description'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('color_description') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="number" class="col-md-4 col-form-label text-md-right"> موجودی در هر بسته </label>

                <div class="col-md-6">
                    <select id="number" class="form-control{{ $errors->has('number') ? ' is-invalid' : '' }}" name="number">
                        <option value="">لطفا انتخاب کنید</option>
                        @foreach($numbers as $number)
                            <option value="{{ $number }}" {{ (old('number') == $number) ? 'selected' : '' }}>{{ $number }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('number'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('number') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            {{--<div class="form-group row">
                <label for="astigmatism" class="col-md-4 col-form-label text-md-right"> درجه استیگمات </label>

                <div class="col-md-6">
                    <select id="astigmatism" class="form-control{{ $errors->has('astigmatism') ? ' is-invalid' : '' }}" name="astigmatism">
                        @foreach($astigmatisms as $astigmatism)
                            <option value="{{ $astigmatism }}" {{ (old('astigmatism') == $astigmatism) ? 'selected' : '' }}>{{ $astigmatism }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('astigmatism'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('astigmatism') }}</strong>
                        </span>
                    @endif
                </div>
            </div>--}}

            <div class="form-group row">
                <label for="thickness" class="col-md-4 col-form-label text-md-right">ضخامت مرکزی 	</label>

                <div class="col-md-6">
                    <input id="thickness" type="text" class="form-control{{ $errors->has('thickness') ? ' is-invalid' : '' }}" name="thickness" value="{{ old('thickness') }}">

                    @if ($errors->has('thickness'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('thickness') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="abatement" class="col-md-4 col-form-label text-md-right"> اب رسانی </label>

                <div class="col-md-6">
                    <input id="abatement" type="number" min="0" max="100" class="form-control{{ $errors->has('abatement') ? ' is-invalid' : '' }}" name="abatement" value="{{ old('abatement') }}">

                    @if ($errors->has('abatement'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('abatement') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="oxygen_supply" class="col-md-4 col-form-label text-md-right">اکسیژن رسانی 	</label>

                <div class="col-md-6">
                    <input id="oxygen_supply" type="number" min="0" max="100" class="form-control{{ $errors->has('oxygen_supply') ? ' is-invalid' : '' }}" name="oxygen_supply" value="{{ old('oxygen_supply') }}">

                    @if ($errors->has('oxygen_supply'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('oxygen_supply') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row stock rx">
                <label for="yu_vie" class="col-md-4 col-form-label text-md-right"> Axis </label>

                <div class="col-md-6">
                    <select class="form-control{{ $errors->has('axis') ? ' is-invalid' : '' }}" multiple name="axis[]">
                        @foreach($axises as $axis)
                            <option value="{{ $axis }}" {{ (old('axis') == $axis) ? 'selected' : '' }}>{{ $axis }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('axis'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('axis') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="group" class="col-md-3 col-form-label text-md-right"> فهرست محصولات </label>

                <div class="col-md-9">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>گروه</th>
                                    <th>قطر لنز</th>
                                    <th>موجودی</th>
                                    <th>قیمت (تومان)</th>
                                    <th>قیمت فروش ویژه (اختیاری)</th>
                                    <th>قیمت خرید</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($group as $key => $gp)
                                    <tr>
                                        <td style="direction: ltr; text-align: center">
                                            <span style="direction: ltr; display: inline-block; text-align: center">
                                                Sph = [ {{ $gp['sph'][0] . ' ' . $gp['sph'][1] }} )
                                            </span>
                                            <input type="hidden" name="sph[{{ $key }}]" value="{{ $gp['sph'][0] . ' ' . $gp['sph'][1] }}">
                                            @if($gp['cyl'])
                                                <span style="direction: ltr; display: inline-block; text-align: center; margin-left: 10px">
                                                        cyl = [ {{ $gp['cyl'] }} ]
                                                </span>
                                                <input type="hidden" name="cyl[{{ $key }}]" value="{{ $gp['cyl'] }}">
                                            @endif
                                        </td>
                                        <td>
                                            <select class="form-control" name="diagonal[{{ $key }}]">
                                                <option value="">لطفا انتخاب کنید</option>
                                                @foreach($diagonals as $diagonal)
                                                    <option value="{{ $diagonal }}" {{ (old('diagonal')[$key] == $diagonal) ? 'selected' : '' }}>{{ $diagonal }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" min="0" class="form-control" name="inventory[{{ $key }}]" value="{{ (old('inventory')[$key]) ? old('inventory')[$key] : 0 }}">
                                        </td>
                                        <td>
                                            <input type="number" min="0" class="form-control" name="price[{{ $key }}]" value="{{ (old('price')[$key]) ? old('price')[$key] : 0 }}">
                                        </td>
                                        <td>
                                            <input type="number" min="0" class="form-control" name="discount_price[{{ $key }}]" value="{{ (old('discount_price')[$key]) ? old('discount_price')[$key] : 0 }}">
                                        </td>
                                        <td>
                                            <input type="number" min="0" class="form-control" name="purchase_price[{{ $key }}]" value="{{ (old('purchase_price')[$key]) ? old('purchase_price')[$key] : 0 }}">
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <div class="form-group row">
                <label for="content" class="col-md-4 col-form-label text-md-right">توضیحات</label>

                <div class="col-md-8">
                    <textarea id="content" rows="18"  class="form-control textarea_editor {{ $errors->has('content') ? ' is-invalid' : '' }}" name="content" >{{ old('content') }}</textarea>

                    @if ($errors->has('content'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('content') }}</strong>
                        </span>
                    @endif
                </div>
            </div>


            <div class="form-group row">
                <label for="image" class="col-md-4 col-form-label text-md-right"> تصویر محصول </label>

                <div class="col-md-3">

                    <link href="{!! asset('assets/adminui/slim/slim.min.css') !!}" rel="stylesheet">
                    <script src="{!! asset('assets/adminui/slim/slim.kickstart.js') !!}"></script>

                    <div class="slime_uploadfile" style="width: 100%;">
                        <div class="slim"
                             data-label="تصویر محصول"
                             data-size="260,261"
                             data-ratio="1:1">
                            <input type="file" id="image" name="image" accept=""/>
                        </div>
                    </div>

                </div>
                <div class="col-md-5">

                    <link rel="stylesheet" href="{!! asset('assets/jquery-file-upload/css/jquery.fileupload.css') !!}">
                    <script src="{!! asset('assets/jquery-file-upload/js/vendor/jquery.ui.widget.js') !!}"></script>
                    <script src="{!! asset('assets/jquery-file-upload/js/jquery.fileupload.js') !!}"></script>
                    <div class="images">

                        <div id="ad_slideshow">


                            <div class="bac_upload_video">
                                <span class="btn btn-xs fileinput-button btn-success">

                                    <span class="but_name_image">گالری تصاویر</span>
                                    <span style="display: none;" id="gallery_results_wrapper">
                                        @if(old('gallery'))
                                            @foreach(old('gallery') as $image)
                                                <input type="hidden" name="gallery[]" value="{{ $image }}">
                                            @endforeach
                                        @endif
                                    </span>
                                </span>
                                <input id="gallery_upload_input" name="image_gallery" type="file">

                                <script>
                                    $(document).ready(function(e){


                                        $.ajaxSetup({
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            }
                                        });

                                        $(function () { "use strict";
                                            var url = '{{url('/upload-gallery')}}';
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
                                                        $("#gallery_results_wrapper").append('<input type="hidden" name="gallery[]" value="'+result+'">');


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
                                @if(old('gallery'))
                                    @foreach(old('gallery') as $image)
                                        <li><img src="{{ $image }}"><div class="buttombox"><button class="remove_image"><i class="fa fa-trash"></i></div></li>
                                    @endforeach
                                @endif

                            </ul>

                        </div>

                    </div>

                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-info waves-effect waves-light">افزودن محصول</button>
                </div>
            </div>
            <div class="clearfix"></div>
        </form>

    </div>

    <script>
        $('.input_group label').click(function () {
            $('.input_group label').removeClass('active');

            $(this).addClass('active');
        });
    </script>
@endsection