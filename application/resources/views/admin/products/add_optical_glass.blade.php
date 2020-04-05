@extends('admin.layouts.app')

@section('page_name', 'افزودن عدسی')

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
                <label for="type" class="col-md-4 col-form-label text-md-right"> نوع </label>

                <div class="col-md-6">
                    <select id="type" class="form-control{{ $errors->has('type') ? ' is-invalid' : '' }}" name="type">
                        <option value="">لطفا انتخاب کنید</option>
                        @foreach($types as $key => $type)
                            <option value="{{ $type }}" data-key="{{ $key }}" {{ (old('type') == $type) ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('type'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('type') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row rx">
                <label for="property" class="col-md-4 col-form-label text-md-right"> ویژگی </label>

                <div class="col-md-6">
                    <select id="property" class="form-control{{ $errors->has('property') ? ' is-invalid' : '' }}" name="property">
                        <option value="">لطفا انتخاب کنید</option>
                        @foreach($properties as $property)
                            <option value="{{ $property }}" {{ (old('property') == $property) ? 'selected' : '' }}>{{ $property }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('property'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('property') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row rx stock">
                <label for="refractive_index" class="col-md-4 col-form-label text-md-right">ضریب شکست </label>

                <div class="col-md-6">
                    <select id="refractive_index" class="form-control{{ $errors->has('refractive_index') ? ' is-invalid' : '' }}" name="refractive_index">
                        <option value="">لطفا انتخاب کنید</option>
                        @foreach($refractive_indexs as $refractive_index)
                            <option value="{{ $refractive_index }}" {{ (old('refractive_index') == $refractive_index) ? 'selected' : '' }}>{{ $refractive_index }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('refractive_index'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('refractive_index') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row stock">
                <label for="anti_reflex_color" class="col-md-4 col-form-label text-md-right"> رنگ انتی رفلکس </label>

                <div class="col-md-6">
                    <select id="anti_reflex_color" class="form-control{{ $errors->has('anti_reflex_color') ? ' is-invalid' : '' }}" name="anti_reflex_color">
                        <option value="">لطفا انتخاب کنید</option>
                        @foreach($anti_reflex_colors as $anti_reflex_color)
                            <option value="{{ $anti_reflex_color }}" {{ (old('anti_reflex_color') == $anti_reflex_color) ? 'selected' : '' }}>{{ $anti_reflex_color }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('anti_reflex_color'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('anti_reflex_color') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row stock">
                <label for="block" class="col-md-4 col-form-label text-md-right"> بلوکات </label>

                <div class="col-md-6">
                    <select id="block" class="form-control{{ $errors->has('block') ? ' is-invalid' : '' }}" name="block">
                        <option value="">لطفا انتخاب کنید</option>
                        @foreach($blocks as $block)
                            <option value="{{ $block }}" {{ (old('block') == $block) ? 'selected' : '' }}>{{ $block }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('block'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('block') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row stock">
                <label for="bloc_troll" class="col-md-4 col-form-label text-md-right"> بلوکنترول </label>

                <div class="col-md-6">
                    <select id="bloc_troll" class="form-control{{ $errors->has('bloc_troll') ? ' is-invalid' : '' }}" name="bloc_troll">
                        <option value="">لطفا انتخاب کنید</option>
                        @foreach($bloc_trolls as $bloc_troll)
                            <option value="{{ $bloc_troll }}" {{ (old('bloc_troll') == $bloc_troll) ? 'selected' : '' }}>{{ $bloc_troll }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('bloc_troll'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('bloc_troll') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row stock">
                <label for="photocrophy" class="col-md-4 col-form-label text-md-right"> فتوکروم </label>

                <div class="col-md-6">
                    <select id="photocrophy" class="form-control{{ $errors->has('photocrophy') ? ' is-invalid' : '' }}" name="photocrophy">
                        <option value="">لطفا انتخاب کنید</option>
                        @foreach($photocrophys as $photocrophy)
                            <option value="{{ $photocrophy }}" {{ (old('photocrophy') == $photocrophy) ? 'selected' : '' }}>{{ $photocrophy }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('photocrophy'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('photocrophy') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row stock">
                <label for="photo_color" class="col-md-4 col-form-label text-md-right"> رنگ فتو</label>

                <div class="col-md-6">
                    <select id="photo_color" class="form-control{{ $errors->has('photo_color') ? ' is-invalid' : '' }}" name="photo_color">
                        <option value="">لطفا انتخاب کنید</option>
                        @foreach($photo_colors as $photo_color)
                            <option value="{{ $photo_color }}" {{ (old('photo_color') == $photo_color) ? 'selected' : '' }}>{{ $photo_color }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('photo_color'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('photo_color') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row stock">
                <label for="polycarbonate" class="col-md-4 col-form-label text-md-right">پلی کربنات</label>

                <div class="col-md-6">
                    <select id="polycarbonate" class="form-control{{ $errors->has('polycarbonate') ? ' is-invalid' : '' }}" name="polycarbonate">
                        <option value="">لطفا انتخاب کنید</option>
                        @foreach($polycarbonates as $polycarbonate)
                            <option value="{{ $polycarbonate }}" {{ (old('polycarbonate') == $polycarbonate) ? 'selected' : '' }}>{{ $polycarbonate }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('polycarbonate'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('polycarbonate') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row stock">
                <label for="poly_break" class="col-md-4 col-form-label text-md-right">نشکن </label>

                <div class="col-md-6">
                    <select id="poly_break" class="form-control{{ $errors->has('poly_break') ? ' is-invalid' : '' }}" name="poly_break">
                        <option value="">لطفا انتخاب کنید</option>
                        @foreach($polybreaks as $poly_break)
                            <option value="{{ $poly_break }}" {{ (old('poly_break') == $poly_break) ? 'selected' : '' }}>{{ $poly_break }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('poly_break'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('poly_break') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row stock">
                <label for="color_white" class="col-md-4 col-form-label text-md-right">سفید قابل رنگ </label>

                <div class="col-md-6">
                    <select id="color_white" class="form-control{{ $errors->has('color_white') ? ' is-invalid' : '' }}" name="color_white">
                        <option value="">لطفا انتخاب کنید</option>
                        @foreach($color_whites as $color_white)
                            <option value="{{ $color_white }}" {{ (old('color_white') == $color_white) ? 'selected' : '' }}>{{ $color_white }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('color_white'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('color_white') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row stock">
                <label for="colored_score" class="col-md-4 col-form-label text-md-right">رنگی طبی</label>

                <div class="col-md-6">
                    <select id="colored_score" class="form-control{{ $errors->has('colored_score') ? ' is-invalid' : '' }}" name="colored_score">
                        <option value="">لطفا انتخاب کنید</option>
                        @foreach($colored_scores as $colored_score)
                            <option value="{{ $colored_score }}" {{ (old('colored_score') == $colored_score) ? 'selected' : '' }}>{{ $colored_score }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('colored_score'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('colored_score') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row stock">
                <label for="watering" class="col-md-4 col-form-label text-md-right">آب گریزی</label>

                <div class="col-md-6">
                    <select id="watering" class="form-control{{ $errors->has('watering') ? ' is-invalid' : '' }}" name="watering">
                        <option value="">لطفا انتخاب کنید</option>
                        @foreach($waterings as $watering)
                            <option value="{{ $watering }}" {{ (old('watering') == $watering) ? 'selected' : '' }}>{{ $watering }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('watering'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('watering') }}</strong>
                        </span>
                    @endif
                </div>
            </div>


            <div class="form-group row stock">
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


            <div class="form-group row stock rx">
                <label for="yu_vie" class="col-md-4 col-form-label text-md-right"> یو وی </label>

                <div class="col-md-6">
                    <select id="yu_vie" class="form-control{{ $errors->has('yu_vie') ? ' is-invalid' : '' }}" name="yu_vie">
                        <option value="">لطفا انتخاب کنید</option>
                        @foreach($yu_vies as $yu_vie)
                            <option value="{{ $yu_vie }}" {{ (old('yu_vie') == $yu_vie) ? 'selected' : '' }}>{{ $yu_vie }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('yu_vie'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('yu_vie') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row rx">
                <label for="yu_vie" class="col-md-4 col-form-label text-md-right"> Add </label>

                <div class="col-md-6">
                    <select class="form-control{{ $errors->has('add') ? ' is-invalid' : '' }}" multiple name="add[]">
                        @foreach($adds as $add)
                            <option value="{{ $add }}" {{ (old('add') == $add) ? 'selected' : '' }}>{{ $add }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('add'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('add') }}</strong>
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
                                    <th style="width: 100px">گروه</th>
                                    <th>سایز</th>
                                    <th>موجودی</th>
                                    <th>قیمت (تومان)</th>
                                    <th>قیمت فروش ویژه (اختیاری)</th>
                                    <th>قیمت خرید</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($group as $key => $gp)
                                    <tr>
                                        <td style="direction: ltr; text-align: center; width: 100px">
                                            <span style="direction: ltr; display: inline-block; text-align: center">
                                                {{ $gp['sph'][0] . ' ' . $gp['sph'][1] }}
                                                <input type="hidden" name="sph[{{ $key }}]" value="{{ $gp['sph'][0] . ' ' . $gp['sph'][1] }}">
                                            </span>
                                        </td>
                                        <td>
                                            <select class="form-control" name="size[{{ $key }}]">
                                                <option value="">لطفا انتخاب کنید</option>
                                                @foreach($sizes as $size)
                                                    <option value="{{ $size }}" {{ (old('size')[$key] == $size) ? 'selected' : '' }}>{{ $size }}</option>
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
            <div class="clearfix" style="display: block"></div>
        </form>

    </div>

    <script>
        $('.input_group label').click(function () {
            $('.input_group label').removeClass('active');

            $(this).addClass('active');
        });
        $('#type').change(function(){
            var thisval = $('#type option:selected').attr('data-key');

            if(!thisval){
                $('.rx').css('display', 'block');
                $('.stock').css('display', 'block');
                $('.rx input').removeAttr("disabled");
                $('.rx select').removeAttr("disabled");
                $('.stock input').removeAttr("disabled");
                $('.stock select').removeAttr("disabled");
            }else if(thisval === 'stock'){
                $('.rx').css('display', 'none');
                $('.stock').css('display', 'block');
                $('.rx input').attr("disabled", 'disabled');
                $('.rx select').attr("disabled", 'disabled');
                $('.stock input').removeAttr("disabled");
                $('.stock select').removeAttr("disabled");
            }else{
                $('.stock').css('display', 'none');
                $('.rx').css('display', 'block');
                $('.stock input').attr("disabled", 'disabled');
                $('.stock select').attr("disabled", 'disabled');
                $('.rx input').removeAttr("disabled");
                $('.rx select').removeAttr("disabled");
            }
        });

        @if(old('type') && old('type') == "Stock تک دید")
            $('.rx').css('display', 'none');
            $('.stock').css('display', 'block');
            $('.rx input').attr("disabled", 'disabled');
            $('.rx select').attr("disabled", 'disabled');
            $('.stock input').removeAttr("disabled");
            $('.stock select').removeAttr("disabled");
        @elseif(old('type') && old('type') != "Stock تک دید")
            $('.stock').css('display', 'none');
            $('.rx').css('display', 'block');
            $('.stock input').attr("disabled", 'disabled');
            $('.stock select').attr("disabled", 'disabled');
            $('.rx input').removeAttr("disabled");
            $('.rx select').removeAttr("disabled");
        @endif
    </script>
@endsection