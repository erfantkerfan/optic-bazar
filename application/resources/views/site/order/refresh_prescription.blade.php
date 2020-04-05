
@if(count($prescriptions) > 0)

    @foreach($prescriptions as $v)
        <div class="item_prescription">
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
                            <div class="item_full">
                                <div class="col-xs-4">Right</div>
                                <div class="col-xs-8">
                                    <div class="row">

                                        <div class="item_full">
                                            <div class="col-xs-4">تعداد</div>
                                            <div class="col-xs-8">{{ $v->rcount }}</div>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="item_full">
                                            <div class="col-xs-4">Sph</div>
                                            <div class="col-xs-8">{{ $v->rsph }}</div>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="item_full">
                                            <div class="col-xs-4">Cyl</div>
                                            <div class="col-xs-8">{{ $v->rcyl }}</div>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="item_full">
                                            <div class="col-xs-4">Axis</div>
                                            <div class="col-xs-8">{{ $v->raxis }}</div>
                                            <div class="clearfix"></div>
                                        </div>

                                        @if($v->dodid)
                                            <div class="item_full">
                                                <div class="col-xs-4">Add</div>
                                                <div class="col-xs-8">{{ $v->radd }}</div>
                                                <div class="clearfix"></div>
                                            </div>
                                        @endif

                                        @if($v->dodid_rdia)
                                            <div class="item_full">
                                                <div class="col-xs-4">Dia</div>
                                                <div class="col-xs-8">{{ $v->dodid_rdia }}</div>
                                                <div class="clearfix"></div>
                                            </div>
                                        @endif

                                        @if($v->dodid_ripd)
                                            <div class="item_full">
                                                <div class="col-xs-4">IPD</div>
                                                <div class="col-xs-8">{{ $v->dodid_ripd }}</div>
                                                <div class="clearfix"></div>
                                            </div>
                                        @endif

                                        @if($v->dodid_rprism)
                                            <div class="item_full">
                                                <div class="col-xs-4">Prism</div>
                                                <div class="col-xs-8">{{ $v->dodid_rprism }}</div>
                                                <div class="clearfix"></div>
                                            </div>
                                        @endif

                                        @if($v->dodid_rprism_base)
                                            <div class="item_full">
                                                <div class="col-xs-4">Prism base/curve</div>
                                                <div class="col-xs-8">{{ $v->dodid_rprism_base }}</div>
                                                <div class="clearfix"></div>
                                            </div>
                                        @endif

                                        @if($v->dodid_rcorridor)
                                            <div class="item_full">
                                                <div class="col-xs-4">corridor length</div>
                                                <div class="col-xs-8">{{ $v->dodid_rcorridor }}</div>
                                                <div class="clearfix"></div>
                                            </div>
                                        @endif

                                        @if($v->dodid_rdec)
                                            <div class="item_full">
                                                <div class="col-xs-4">DEC</div>
                                                <div class="col-xs-8">{{ $v->dodid_rdec }}</div>
                                                <div class="clearfix"></div>
                                            </div>
                                        @endif

                                        @if($v->prisma && $v->prisma_rprisma1)
                                            <div class="item_full">
                                                <div class="col-xs-4">Prism 1</div>
                                                <div class="col-xs-8">{{ $v->prisma_rprisma1 }}</div>
                                                <div class="clearfix"></div>
                                            </div>
                                        @endif

                                        @if($v->prisma && $v->prisma_rdegrees1)
                                            <div class="item_full">
                                                <div class="col-xs-4">Degrees 1</div>
                                                <div class="col-xs-8">{{ $v->prisma_rdegrees1 }}</div>
                                                <div class="clearfix"></div>
                                            </div>
                                        @endif

                                        @if($v->prisma && $v->prisma_rprisma2)
                                            <div class="item_full">
                                                <div class="col-xs-4">Prism 2</div>
                                                <div class="col-xs-8">{{ $v->prisma_rprisma2 }}</div>
                                                <div class="clearfix"></div>
                                            </div>
                                        @endif

                                        @if($v->prisma && $v->prisma_rdegrees2)
                                            <div class="item_full">
                                                <div class="col-xs-4">Degrees 2</div>
                                                <div class="col-xs-8">{{ $v->prisma_rdegrees2 }}</div>
                                                <div class="clearfix"></div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="item_full">
                                <div class="col-xs-4">Left</div>
                                <div class="col-xs-8">
                                    <div class="row">

                                        <div class="item_full">
                                            <div class="col-xs-4">تعداد</div>
                                            <div class="col-xs-8">{{ $v->lcount }}</div>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="item_full">
                                            <div class="col-xs-4">Sph</div>
                                            <div class="col-xs-8">{{ $v->lsph }}</div>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="item_full">
                                            <div class="col-xs-4">Cyl</div>
                                            <div class="col-xs-8">{{ $v->lcyl }}</div>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="item_full">
                                            <div class="col-xs-4">Axis</div>
                                            <div class="col-xs-8">{{ $v->laxis }}</div>
                                            <div class="clearfix"></div>
                                        </div>

                                        @if($v->dodid)
                                            <div class="item_full">
                                                <div class="col-xs-4">Add</div>
                                                <div class="col-xs-8">{{ $v->ladd }}</div>
                                                <div class="clearfix"></div>
                                            </div>
                                        @endif



                                        @if($v->dodid_ldia)
                                            <div class="item_full">
                                                <div class="col-xs-4">Dia</div>
                                                <div class="col-xs-8">{{ $v->dodid_ldia }}</div>
                                                <div class="clearfix"></div>
                                            </div>
                                        @endif

                                        @if($v->dodid_lipd)
                                            <div class="item_full">
                                                <div class="col-xs-4">IPD</div>
                                                <div class="col-xs-8">{{ $v->dodid_lipd }}</div>
                                                <div class="clearfix"></div>
                                            </div>
                                        @endif

                                        @if($v->dodid_lprism)
                                            <div class="item_full">
                                                <div class="col-xs-4">Prism</div>
                                                <div class="col-xs-8">{{ $v->dodid_lprism }}</div>
                                                <div class="clearfix"></div>
                                            </div>
                                        @endif

                                        @if($v->dodid_lprism_base)
                                            <div class="item_full">
                                                <div class="col-xs-4">Prism base/curve</div>
                                                <div class="col-xs-8">{{ $v->dodid_lprism_base }}</div>
                                                <div class="clearfix"></div>
                                            </div>
                                        @endif

                                        @if($v->dodid_lcorridor)
                                            <div class="item_full">
                                                <div class="col-xs-4">corridor length</div>
                                                <div class="col-xs-8">{{ $v->dodid_lcorridor }}</div>
                                                <div class="clearfix"></div>
                                            </div>
                                        @endif

                                        @if($v->dodid_ldec)
                                            <div class="item_full">
                                                <div class="col-xs-4">DEC</div>
                                                <div class="col-xs-8">{{ $v->dodid_ldec }}</div>
                                                <div class="clearfix"></div>
                                            </div>
                                        @endif

                                        @if($v->prisma && $v->prisma_lprisma1)
                                            <div class="item_full">
                                                <div class="col-xs-4">Prism 1</div>
                                                <div class="col-xs-8">{{ $v->prisma_lprisma1 }}</div>
                                                <div class="clearfix"></div>
                                            </div>
                                        @endif

                                        @if($v->prisma && $v->prisma_ldegrees1)
                                            <div class="item_full">
                                                <div class="col-xs-4">Degrees 1</div>
                                                <div class="col-xs-8">{{ $v->prisma_ldegrees1 }}</div>
                                                <div class="clearfix"></div>
                                            </div>
                                        @endif

                                        @if($v->prisma && $v->prisma_lprisma2)
                                            <div class="item_full">
                                                <div class="col-xs-4">Prism 2</div>
                                                <div class="col-xs-8">{{ $v->prisma_lprisma2 }}</div>
                                                <div class="clearfix"></div>
                                            </div>
                                        @endif

                                        @if($v->prisma && $v->prisma_ldegrees2)
                                            <div class="item_full">
                                                <div class="col-xs-4">Degrees 2</div>
                                                <div class="col-xs-8">{{ $v->prisma_ldegrees2 }}</div>
                                                <div class="clearfix"></div>
                                            </div>
                                        @endif

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


    <div class="clearfix"></div>

@else
    <div class="msg">
        <div class="alert alert-warning alert-info" style="text-align: center">تا کنون هیج نسخه ای برای شما ثبت نشده است.</div>
    </div>
@endif