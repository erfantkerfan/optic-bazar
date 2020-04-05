<form method="post" class="form_prescription form_prescription_follow" action="{{ url('/order/new/prescription/archive') }}">

    <div class="refresh">
        <button class="btn btn-default btn-rounded waves-effect waves-light" id="refresh_box"><span style="margin-right: 14px;">بروز رسانی</span> <i style="float: right;margin: 2px 0 2px;" class="fa fa-refresh m-l-5"></i></button>
    </div>

    <div class="refresh_main">

        @if(count($prescriptions) > 0)

            <div class="box_archive_prescription">
                <div class="row">

                    @foreach($prescriptions as $v)
                        <div class="item_prescription" onclick="openPrescription(this)">
                            <div class="col-xs-12 col-md-9">
                                <div class="invite">
                                    <span>نام بیمار : {{ $v->name }}</span>
                                    <span>نوع نسخه : {{ ($v->type == "import") ? "تایپ شده" : "تصویر" }}</span>
                                    <span>تاریخ ثبت نسخه : {{ jdate('Y/m/d', strtotime($v->created_at)) }}</span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-3">
                                <div class="row">
                                    <div class="col-xs-2" style="padding-top: 2px !important;">
                                        <a class="btn btn-danger btn-circle delete-warning" href="{{ url('user/prescription/delete/' . $v->id) }}" title="حذف این نسخه"> <i class="fa fa-trash"></i> </a>
                                    </div>
                                    <div class="col-xs-10">
                                        <label class="btn btn-block btn-outline btn-rounded btn-success archive_selector {{ (@$followUp['presentation'] == $v->id) ? "active" : "" }}"> انتخاب <input style="position: absolute;visibility: hidden;" type="radio" name="prescription_archive" value="{{ $v->id }}" {{ (@$followUp['presentation'] == $v->id) ? "checked" : "" }} /></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="full_data_prescription">
                                    <div class="row">

                                        <div class="item_full">
                                            <div class="col-xs-4">تاریخ تولد</div>
                                            <div class="col-xs-8">{{ $v->birth }}</div>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="item_full">
                                            <div class="col-xs-4">نوع محصول</div>
                                            <div class="col-xs-8">{{ ($v->type_product == "lens") ? 'لنز' : 'عدسی' }}</div>
                                            <div class="clearfix"></div>
                                        </div>

                                        @if($v->type == 'import')

                                            <div class="item_full import">

                                                <div class="col-xs-12 col-md-6 float-left">

                                                    <div class="row">

                                                        <div class="box_add_item">
                                                            <div class="item">
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
                                                                        <input type="text" name="rcount" class="form-control" disabled value="{{ $v->rcount }}" />
                                                                    </div>
                                                                    <div class="col-xs-2 float-left">
                                                                        <input type="text" name="rsph" class="form-control" disabled value="{{ $v->rsph }}" />
                                                                    </div>
                                                                    <div class="col-xs-2 float-left">
                                                                        <input type="text" name="rcyl" class="form-control" disabled value="{{ $v->rcyl }}" />
                                                                    </div>
                                                                    <div class="col-xs-2 float-left">
                                                                        <input type="text" name="raxis" class="form-control" disabled value="{{ $v->raxis }}" />
                                                                    </div>
                                                                    <div class="col-xs-2 float-left addcontroller">
                                                                        <input type="text" name="radd" class="form-control" disabled value="{{ $v->radd }}" />
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
                                                                        <input type="text" name="lcount" class="form-control" disabled value="{{ $v->lcount }}" />
                                                                    </div>
                                                                    <div class="col-xs-2 float-left">
                                                                        <input type="text" name="lsph" class="form-control" disabled value="{{ $v->lsph }}" />
                                                                    </div>
                                                                    <div class="col-xs-2 float-left">
                                                                        <input type="text" name="lcyl" class="form-control" disabled value="{{ $v->lcyl }}" />
                                                                    </div>
                                                                    <div class="col-xs-2 float-left">
                                                                        <input type="text" name="laxis" class="form-control" disabled value="{{ $v->laxis }}" />
                                                                    </div>
                                                                    <div class="col-xs-2 float-left addcontroller">
                                                                        <input type="text" name="ladd" class="form-control" disabled value="{{ $v->ladd }}" />
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


                                                    <div class="row optic">

                                                        <div class="box_add_item {{ ($v->dodid) ? '' : 'dodidInput' }}">
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
                                                                        <input type="text" disabled class="dodid_input_control form-control" name="rdia" value="{{ old('rdia', @$v->dodid_rdia) }}" autocomplete="off" >
                                                                    </div>
                                                                    <div class="col-xs-2 float-left">
                                                                        <input type="text" disabled class="dodid_input_control form-control" name="ripd" value="{{ old('ripd', @$v->dodid_ripd) }}" autocomplete="off" >
                                                                    </div>
                                                                    <div class="col-xs-2 float-left">
                                                                        <input type="text" disabled class="dodid_input_control form-control" name="rprism" value="{{ old('rprism', @$v->dodid_rprism) }}" autocomplete="off" >
                                                                    </div>
                                                                    <div class="col-xs-2 float-left">
                                                                        <input type="text" disabled class="dodid_input_control form-control" name="rprism_base" value="{{ old('rprism_base', @$v->dodid_rprism_base) }}" autocomplete="off" >
                                                                    </div>
                                                                    <div class="col-xs-2 float-left">
                                                                        <input type="text" disabled class="dodid_input_control form-control" name="rcorridor" value="{{ old('rcorridor', @$v->dodid_rcorridor) }}" autocomplete="off" >
                                                                    </div>
                                                                    <div class="col-xs-2 float-left">
                                                                        <input type="text" disabled class="dodid_input_control form-control" name="rdec" value="{{ old('rdec', @$v->dodid_rdec) }}" autocomplete="off" >
                                                                    </div>

                                                                    <div class="clearfix"></div>
                                                                </div>

                                                                <div class="clearfix"></div>

                                                                <div class="boxsearch">

                                                                    <div class="col-xs-2 float-left">
                                                                        <input type="text" disabled class="dodid_input_control form-control" name="ldia" value="{{ old('ldia', @$v->dodid_ldia) }}" autocomplete="off" >
                                                                    </div>
                                                                    <div class="col-xs-2 float-left">
                                                                        <input type="text" disabled class="dodid_input_control form-control" name="lipd" value="{{ old('lipd', @$v->dodid_lipd) }}" autocomplete="off" >
                                                                    </div>
                                                                    <div class="col-xs-2 float-left">
                                                                        <input type="text" disabled class="dodid_input_control form-control" name="lprism" value="{{ old('lprism', @$v->dodid_lprism) }}" autocomplete="off" >
                                                                    </div>
                                                                    <div class="col-xs-2 float-left">
                                                                        <input type="text" disabled class="dodid_input_control form-control" name="lprism_base" value="{{ old('lprism_base', @$v->dodid_lprism_base) }}" autocomplete="off" >
                                                                    </div>
                                                                    <div class="col-xs-2 float-left">
                                                                        <input type="text" disabled class="dodid_input_control form-control" name="lcorridor" value="{{ old('lcorridor', @$v->dodid_lcorridor) }}" autocomplete="off" >
                                                                    </div>
                                                                    <div class="col-xs-2 float-left">
                                                                        <input type="text" disabled class="dodid_input_control form-control" name="ldec" value="{{ old('ldec', @$v->dodid_ldec) }}" autocomplete="off" >
                                                                    </div>

                                                                    <div class="clearfix"></div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="clearfix"></div>

                                            </div>

                                            <div class="item_full">

                                                <div class="col-xs-12 col-md-6 float-left">

                                                    <div class="row">

                                                        <div class="box_add_item  {{ ($v->prisma) ? '' : 'prismaInput' }}">
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
                                                                        <input type="text" disabled class="prisma_input_control form-control{{ $errors->has('rprisma1') ? ' is-invalid' : '' }}" name="rprisma1" value="{{ old('rprisma1', @$v->prisma_rprisma1) }}" autocomplete="off" >
                                                                    </div>
                                                                    <div class="col-xs-2 float-left">
                                                                        <input type="text" disabled class="prisma_input_control form-control{{ $errors->has('rdegrees1') ? ' is-invalid' : '' }}" name="rdegrees1" value="{{ old('rdegrees1', @$v->prisma_rdegrees1) }}" autocomplete="off" >
                                                                    </div>
                                                                    <div class="col-xs-2 float-left">
                                                                        <input type="text" disabled class="prisma_input_control form-control{{ $errors->has('rprisma2') ? ' is-invalid' : '' }}" name="rprisma2" value="{{ old('rprisma2', @$v->prisma_rprisma2) }}" autocomplete="off" >
                                                                    </div>
                                                                    <div class="col-xs-2 float-left">
                                                                        <input type="text" disabled class="prisma_input_control form-control{{ $errors->has('rdegrees2') ? ' is-invalid' : '' }}" name="rdegrees2" value="{{ old('rdegrees2', @$v->prisma_rdegrees2) }}" autocomplete="off" >
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
                                                                        <input type="text" disabled class="prisma_input_control form-control{{ $errors->has('lprisma1') ? ' is-invalid' : '' }}" name="lprisma1" value="{{ old('lprisma1', @$v->prisma_lprisma1) }}" autocomplete="off" >
                                                                    </div>
                                                                    <div class="col-xs-2 float-left">
                                                                        <input type="text" disabled class="prisma_input_control form-control{{ $errors->has('ldegrees1') ? ' is-invalid' : '' }}" name="ldegrees1" value="{{ old('ldegrees1', @$v->prisma_ldegrees1) }}" autocomplete="off" >
                                                                    </div>
                                                                    <div class="col-xs-2 float-left">
                                                                        <input type="text" disabled class="prisma_input_control form-control{{ $errors->has('lprisma2') ? ' is-invalid' : '' }}" name="lprisma2" value="{{ old('lprisma2', @$v->prisma_lprisma2) }}" autocomplete="off" >
                                                                    </div>
                                                                    <div class="col-xs-2 float-left">
                                                                        <input type="text" disabled class="prisma_input_control form-control{{ $errors->has('ldegrees2') ? ' is-invalid' : '' }}" name="ldegrees2" value="{{ old('ldegrees2', @$v->prisma_ldegrees2) }}" autocomplete="off" >
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

                                        @endif

                                        @if($v->type == 'image')
                                            <div class="item_full">
                                                <div class="col-xs-4">تصویر نسخه</div>
                                                <div class="col-xs-8">
                                                    <ul class="image_list">

                                                        <?php $images = json_decode($v->image); ?>

                                                        @if($images)
                                                            @foreach($images as $image)
                                                                <li><a target="_blank" href="{{ $image }}"><div class="bac_image_list" style="background-image: url('{{ $image }}')"><img src="{{ $image }}" style="display: none"></div></a></li>
                                                            @endforeach
                                                        @endif

                                                    </ul>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    @endforeach

                </div>
            </div>

            <div class="col-xs-12 col-md-6">

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

            @if(@$followUp['follow'])
                <input type="hidden" name="follow_up" value="{{ @$followUp['follow'] }}">
            @endif

            <div class="clearfix"></div>

        @else
            <div class="msg">
                <div class="alert alert-warning alert-info" style="text-align: center">تا کنون هیج نسخه ای برای شما ثبت نشده است.</div>
            </div>
        @endif
    </div>

</form>

<script>

    function openPrescription(button) {
        $(button).toggleClass('open');
    }

</script>