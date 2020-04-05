<form method="post" class="form_prescription" action="{{ url('/order/new/prescription/type') }}">
    @csrf

    <input type="hidden" name="type_prescription" value="add">
    <div class="row">

        <div class="col-xs-12 col-md-6">

            <div class="row">
                <div class="name_box">
                    <div class="col-xs-6">
                        <label class="block_table">نام و نام خانوادگی بیمار</label>
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
                        <div class="m-b-30" id="product_type">
                            <input type="checkbox" name="product_type" class="js-switch"  data-color="#feab00" data-secondary-color="#feab00" {{ (@$request->type_product == 'lens') ? '' : 'checked' }} />
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

        <div class="col-xs-12 float-left optic" style="display: {{ (@$request->type_product == 'optical_glass') ? 'block' : 'none' }};">
            <div class="row">
                <div class="col-xs-6 float-left">
                    <div class="row">
                        <div class="col-xs-4 col-md-2 float-left">
                            <h4 class="box-title" style="margin-top: 6px;">دو دید</h4>
                        </div>
                        <div class="col-xs-8 float-left" style="padding: 0">
                            <div class="m-b-30" id="dodid">
                                <input type="checkbox" name="two_eyes" class="js-switch"  data-color="#00c292" data-secondary-color="#f96262" {{ (@$request->dodid == '1') ? 'checked' : '' }} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="col-xs-12 col-md-6 float-left">

            <div class="row {{ (@$request->type_product == 'optical_glass') ? 'optic' : '' }}">

                <div class="box_add_item">
                    <div class="item">

                        <input type="hidden" id="sph_lens" value="{{ json_encode($sph) }}">
                        <input type="hidden" id="sph_optic" value="{{ json_encode($sph_optic) }}">

                        <input type="hidden" id="cyl_lens" value="{{ json_encode($cyl) }}">
                        <input type="hidden" id="cyl_optic" value="{{ json_encode($cyl_optic) }}">

                        <input type="hidden" id="axis_lens" value="{{ json_encode($axis) }}">
                        <input type="hidden" id="axis_optic" value="{{ json_encode($axis_optic) }}">

                        <div class="col-xs-1 float-left">
                            <label class="top_table"></label>
                        </div>
                        <div class="col-xs-2 float-left">
                            <label class="top_table">تعداد</label>
                        </div>
                        <div class="col-xs-2 float-left">
                            <label class="top_table">Sph</label>
                        </div>
                        <div class="col-xs-2 float-left">
                            <label class="top_table">Cyl</label>
                        </div>
                        <div class="col-xs-2 float-left">
                            <label class="top_table">Axis</label>
                        </div>
                        <div class="col-xs-2 float-left">
                            <label class="top_table add_label">Add</label>
                        </div>
                        <div class="col-xs-1 float-left">
                        </div>

                        <div class="clearfix"></div>

                        <div class="boxsearch">
                            <div class="col-xs-1 float-left">
                                <label class="left_table">Right</label>
                            </div>
                            <div class="col-xs-2 float-left">
                                <select class="input_control form-control" name="rcount">
                                    {{--<option value="no_select">انتخاب کنید</option>--}}
                                    @foreach($count as $v)
                                        <option value="{{ $v }}" {{ (old('rcount', @$request->rcount) == $v) ? 'selected' : '' }}>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xs-2 float-left">
                                <select class="input_control form-control" name="rsph">
                                    <option value="no_select">انتخاب کنید</option>
                                    @foreach($sph as $v)
                                        <option value="{{ $v }}" {{ (old('rsph', @$request->rsph) == $v) ? 'selected' : '' }}>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xs-2 float-left">
                                <select class="input_control form-control" name="rcyl">
                                    @foreach($cyl as $v)
                                        <option value="{{ $v }}" {{ (old('rcyl', @$request->rcyl) == $v) ? 'selected' : '' }}>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xs-2 float-left">
                                <select class="input_control form-control" name="raxis">
                                    <option value="no_select">بدون axis</option>
                                    @foreach($axis as $v)
                                        <option value="{{ $v }}" {{ (old('raxis', @$request->raxis) == $v) ? 'selected' : '' }}>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xs-2 float-left addcontroller">
                                <select class="form-control" name="radd" disabled>
                                    @foreach($add_optic as $v)
                                        <option value="{{ $v }}" {{ (old('radd', @$request->radd) == $v) ? 'selected' : '' }}>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xs-1 float-left">
                            </div>

                            <div class="clearfix"></div>
                        </div>

                        <div class="clearfix"></div>

                        <div class="boxsearch">
                            <div class="col-xs-1 float-left">
                                <label class="left_table">Left</label>
                            </div>
                            <div class="col-xs-2 float-left">
                                <select class="input_control form-control" name="lcount">
                                    {{--<option value="no_select">انتخاب کنید</option>--}}
                                    @foreach($count as $v)
                                        <option value="{{ $v }}" {{ (old('lcount', @$request->lcount) == $v) ? 'selected' : '' }}>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xs-2 float-left">
                                <select class="input_control form-control" name="lsph">
                                    <option value="no_select">انتخاب کنید</option>
                                    @foreach($sph as $v)
                                        <option value="{{ $v }}" {{ (old('lsph', @$request->lsph) == $v) ? 'selected' : '' }}>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xs-2 float-left">
                                <select class="input_control form-control" name="lcyl">
                                    @foreach($cyl as $v)
                                        <option value="{{ $v }}" {{ (old('lcyl', @$request->lcyl) == $v) ? 'selected' : '' }}>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xs-2 float-left">
                                <select class="input_control form-control" name="laxis">
                                    <option value="no_select">بدون axis</option>
                                    @foreach($axis as $v)
                                        <option value="{{ $v }}" {{ (old('laxis', @$request->laxis) == $v) ? 'selected' : '' }}>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xs-2 float-left addcontroller">
                                <select class="form-control" name="ladd" disabled>
                                    @foreach($add_optic as $v)
                                        <option value="{{ $v }}" {{ (old('ladd', @$request->ladd) == $v) ? 'selected' : '' }}>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xs-1 float-left">
                            </div>

                            <div class="clearfix"></div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <div class="col-xs-12 col-md-6 float-left">


            <div class="row optic" style="display:none;">

                <div class="box_add_item dodidInput">
                    <div class="item">

                        <div class="header_dodid_data">
                            <div class="col-xs-2 float-left">
                                <label class="top_table">Dia</label>
                            </div>
                            <div class="col-xs-2 float-left">
                                <label class="top_table">IPD</label>
                            </div>
                            <div class="col-xs-2 float-left">
                                <label class="top_table">Prism</label>
                            </div>
                            <div class="col-xs-2 float-left">
                                <label class="top_table">Prism base/curve</label>
                            </div>
                            <div class="col-xs-2 float-left">
                                <label class="top_table">corridor length</label>
                            </div>
                            <div class="col-xs-2 float-left">
                                <label class="top_table">DEC</label>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="clearfix"></div>

                        <div class="boxsearch">
                            <div class="col-xs-2 float-left">
                                <input type="text" disabled class="dodid_input_control form-control" name="rdia" value="{{ old('rdia', @$request->dodid_rdia) }}" autocomplete="off" >
                            </div>
                            <div class="col-xs-2 float-left">
                                <input type="text" disabled class="dodid_input_control form-control" name="ripd" value="{{ old('ripd', @$request->dodid_ripd) }}" autocomplete="off" >
                            </div>
                            <div class="col-xs-2 float-left">
                                <input type="text" disabled class="dodid_input_control form-control" name="rprism" value="{{ (old('rprism', @$request->dodid_rprism)) ? old('rprism', @$request->dodid_rprism) : 0 }}" autocomplete="off" >
                            </div>
                            <div class="col-xs-2 float-left">
                                <input type="text" disabled class="dodid_input_control form-control" name="rprism_base" value="{{ (old('rprism_base', @$request->dodid_rprism_base)) ? old('rprism_base', @$request->dodid_rprism_base) : 0 }}" autocomplete="off" >
                            </div>
                            <div class="col-xs-2 float-left">
                                <input type="text" disabled class="dodid_input_control form-control" name="rcorridor" value="{{ old('rcorridor', @$request->dodid_rcorridor) }}" autocomplete="off" >
                            </div>
                            <div class="col-xs-2 float-left">
                                <input type="text" disabled class="dodid_input_control form-control" name="rdec" value="{{ (old('rdec', @$request->dodid_rdec)) ? old('rdec', @$request->dodid_rdec) : 0 }}" autocomplete="off" >
                            </div>

                            <div class="clearfix"></div>
                        </div>

                        <div class="clearfix"></div>

                        <div class="boxsearch">

                            <div class="col-xs-2 float-left">
                                <input type="text" disabled class="dodid_input_control form-control" name="ldia" value="{{ old('ldia', @$request->dodid_ldia) }}" autocomplete="off" >
                            </div>
                            <div class="col-xs-2 float-left">
                                <input type="text" disabled class="dodid_input_control form-control" name="lipd" value="{{ old('lipd', @$request->dodid_lipd) }}" autocomplete="off" >
                            </div>
                            <div class="col-xs-2 float-left">
                                <input type="text" disabled class="dodid_input_control form-control" name="lprism" value="{{ (old('lprism', @$request->dodid_lprism)) ? old('lprism', @$request->dodid_lprism) : 0 }}" autocomplete="off" >
                            </div>
                            <div class="col-xs-2 float-left">
                                <input type="text" disabled class="dodid_input_control form-control" name="lprism_base" value="{{ (old('lprism_base', @$request->dodid_lprism_base)) ? old('lprism_base', @$request->dodid_lprism_base) : 0 }}" autocomplete="off" >
                            </div>
                            <div class="col-xs-2 float-left">
                                <input type="text" disabled class="dodid_input_control form-control" name="lcorridor" value="{{ old('lcorridor', @$request->dodid_lcorridor) }}" autocomplete="off" >
                            </div>
                            <div class="col-xs-2 float-left">
                                <input type="text" disabled class="dodid_input_control form-control" name="ldec" value="{{ (old('ldec', @$request->dodid_ldec)) ? old('ldec', @$request->dodid_ldec) : 0 }}" autocomplete="off" >
                            </div>

                            <div class="clearfix"></div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="clearfix"></div>

        <div class="presentation_group optic" style="display:none;">

            <div class="col-xs-12 col-md-6 float-left">


                <div class="row box_lathe_btn">
                    <div class="col-xs-12">
                        <div class="checkbox info" id="item_lathe" style="margin-bottom: 31px;">
                            <input type="checkbox" id="lathe" name="lathe">
                            <label for="lathe"> تراشیده شود</label>
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
                                                    <option value="{{ $v }}" {{ (old('rpd', @$request->rpd) == $v) ? 'selected' : '' }}>{{ $v }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-xs-2 float-left">
                                            <input type="text" class="form-control{{ $errors->has('rheight') ? ' is-invalid' : '' }}" name="rheight" value="{{ old('rheight', @$request->rheight) }}" autocomplete="off" >
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
                                                    <option value="{{ $v }}" {{ (old('lpd', @$request->lpd) == $v) ? 'selected' : '' }}>{{ $v }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-xs-2 float-left">
                                            <input type="text" class="form-control{{ $errors->has('lheight') ? ' is-invalid' : '' }}" name="lheight" value="{{ old('lheight', @$request->lheight) }}" autocomplete="off" >
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

            <div class="col-xs-12 col-md-6 float-left prisma_box">

                <div class="row">
                    <div class="col-xs-12">

                        <div class="row">
                            <div class="col-xs-2 float-left">
                                <h4 class="box-title" style="margin-top: 6px;">Prism</h4>
                            </div>
                            <div class="col-xs-8 float-left" style="padding: 0">
                                <div class="m-b-30" id="prisma">
                                    <input type="checkbox" name="prisma" class="js-switch"  data-color="#00c292" data-secondary-color="#f96262" />
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="clearfix"></div>

                    <div class="box_add_item prismaInput">
                        <div class="item">
                            <div class="col-xs-1 float-left">
                                <label class="top_table"></label>
                            </div>
                            <div class="col-xs-2 float-left">
                                <label class="top_table">Prism 1</label>
                            </div>
                            <div class="col-xs-2 float-left">
                                <label class="top_table">Degrees 1</label>
                            </div>
                            <div class="col-xs-2 float-left">
                                <label class="top_table">Prism 2</label>
                            </div>
                            <div class="col-xs-2 float-left">
                                <label class="top_table">Degrees 2</label>
                            </div>
                            <div class="col-xs-1 float-left">
                            </div>

                            <div class="clearfix"></div>

                            <div class="boxsearch">
                                <div class="col-xs-1 float-left">
                                    <label class="left_table">Right</label>
                                </div>
                                <div class="col-xs-2 float-left">
                                    <input type="text" disabled class="prisma_input_control form-control{{ $errors->has('rprisma1') ? ' is-invalid' : '' }}" name="rprisma1" value="{{ old('rprisma1', @$request->prisma_rprisma1) }}" autocomplete="off" >
                                </div>
                                <div class="col-xs-2 float-left">
                                    <input type="text" disabled class="prisma_input_control form-control{{ $errors->has('rdegrees1') ? ' is-invalid' : '' }}" name="rdegrees1" value="{{ old('rdegrees1', @$request->prisma_rdegrees1) }}" autocomplete="off" >
                                </div>
                                <div class="col-xs-2 float-left">
                                    <input type="text" disabled class="prisma_input_control form-control{{ $errors->has('rprisma2') ? ' is-invalid' : '' }}" name="rprisma2" value="{{ old('rprisma2', @$request->prisma_rprisma2) }}" autocomplete="off" >
                                </div>
                                <div class="col-xs-2 float-left">
                                    <input type="text" disabled class="prisma_input_control form-control{{ $errors->has('rdegrees2') ? ' is-invalid' : '' }}" name="rdegrees2" value="{{ old('rdegrees2', @$request->prisma_rdegrees2) }}" autocomplete="off" >
                                </div>
                                <div class="col-xs-1 float-left">
                                </div>

                                <div class="clearfix"></div>
                            </div>

                            <div class="clearfix"></div>

                            <div class="boxsearch">
                                <div class="col-xs-1 float-left">
                                    <label class="left_table">Left</label>
                                </div>
                                <div class="col-xs-2 float-left">
                                    <input type="text" disabled class="prisma_input_control form-control{{ $errors->has('lprisma1') ? ' is-invalid' : '' }}" name="lprisma1" value="{{ old('lprisma1', @$request->prisma_lprisma1) }}" autocomplete="off" >
                                </div>
                                <div class="col-xs-2 float-left">
                                    <input type="text" disabled class="prisma_input_control form-control{{ $errors->has('ldegrees1') ? ' is-invalid' : '' }}" name="ldegrees1" value="{{ old('ldegrees1', @$request->prisma_ldegrees1) }}" autocomplete="off" >
                                </div>
                                <div class="col-xs-2 float-left">
                                    <input type="text" disabled class="prisma_input_control form-control{{ $errors->has('lprisma2') ? ' is-invalid' : '' }}" name="lprisma2" value="{{ old('lprisma2', @$request->prisma_lprisma2) }}" autocomplete="off" >
                                </div>
                                <div class="col-xs-2 float-left">
                                    <input type="text" disabled class="prisma_input_control form-control{{ $errors->has('ldegrees2') ? ' is-invalid' : '' }}" name="ldegrees2" value="{{ old('ldegrees2', @$request->prisma_ldegrees2) }}" autocomplete="off" >
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

        <div class="col-xs-12 float-left">
            <div class="row">
                <div class="col-xs-12 col-md-6 float-left">

                    <div class="row">
                        <div class="description_order">

                            <div class="boxsearch">
                                <div class="col-xs-1 float-left">
                                </div>
                                <div class="col-xs-10 float-left">
                                    <textarea class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" placeholder="توضیحات تکمیلی" >{{ old('description') }}</textarea>
                                </div>

                                <div class="clearfix"></div>

                                <div class="col-xs-12 buttomSend">
                                    <button type="submit" class="btn btn-info">گام بعدی</button>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="clearfix"></div>

    </div>

</form>

@if(@$request->type_product == 'optical_glass')
<script>
    $('.optic').css('display', 'block');
    if($('.addcontroller').hasClass('active')){
        $('.addcontroller select').addClass('input_control').removeAttr('disabled');
    }

    var obj_sph = JSON.parse($('#sph_optic').val());
    showjsondata('select[name="rsph"]', obj_sph);
    showjsondata('select[name="lsph"]', obj_sph);

    var obj_cyl = JSON.parse($('#cyl_optic').val());
    showjsondata('select[name="rcyl"]', obj_cyl);
    showjsondata('select[name="lcyl"]', obj_cyl);

    var obj_axis = JSON.parse($('#axis_optic').val());
    showjsondata('select[name="raxis"]', obj_axis);
    showjsondata('select[name="laxis"]', obj_axis);



    function showjsondata(id, obj) {
        if(obj){
            $(id).html('');
            if(id === 'select[name="rsph"]' || id === 'select[name="lsph"]'){

                $(id).append('<option value="no_select">انتخاب کنید</option>');

            }else if(id === 'select[name="raxis"]' || id === 'select[name="laxis"]'){

                $(id).append('<option value="no_select"> Ax بدون </option>');

            }
            obj.forEach(function (item, index, arr) {
                $(id).append('<option value="'+arr[index]+'">'+arr[index]+'</option>');
            });

        }

    }

    @if(@$request->dodid == '1')

        $('.addcontroller').addClass('active');

        if($('#add_prescription').hasClass('optical_glass')) {
            $('.addcontroller select').addClass('input_control').removeAttr('disabled');
        }

        $('.dodidInput').addClass('active');
        $('.dodid_input_control').removeAttr('disabled').addClass('input_control');

    @endif

    @if(@$request->prisma == '1')

    $('.prismaInput').addClass('active');
    $('.prismaInput input').removeAttr('disabled').addClass('input_control');

    @endif


    $('#presentation_box_op select[name="rsph"]').val("{{ @$request->rsph }}");
    $('#presentation_box_op select[name="lsph"]').val("{{ @$request->lsph }}");
    $('#presentation_box_op select[name="rcyl"]').val("{{ @$request->rcyl }}");
    $('#presentation_box_op select[name="lcyl"]').val("{{ @$request->lcyl }}");
    $('#presentation_box_op select[name="raxis"]').val("{{ @$request->raxis }}");
    $('#presentation_box_op select[name="laxis"]').val("{{ @$request->laxis }}");


</script>
@endif