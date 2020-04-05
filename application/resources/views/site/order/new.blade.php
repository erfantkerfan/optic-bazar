@extends('site.layouts.app')

@section('content')

    <div class="box_color_pages baclite">
        <div class="product_page_list">

            <div class="zm_order_lists">
                <div class="container">
                    <div class="row">

                        <div class="error clearfix">
                            @if(Session::has('error'))
                                <div class="alert alert-danger">{{ Session::get('error') }}</div>
                            @endif

                            @if(Session::has('success'))
                                <div class="alert alert-success">{{ Session::get('success') }}</div>
                            @endif

                        </div>


                        <div class="zm_step_box {{ (@$followUp['presentation']) ? "" : "active" }}" id="presentation_box_op">
                            <div select_labrator class="new-form-inner">
                                <div class="parametr_controller">
                                    <ul>
                                        <li><a class="accardeon" title="بستن و باز کردن این بخش" href="#">مینیمایز</a></li>
                                        <li><a class="edit" title="ویرایش" href="#">ویرایش</a></li>
                                        <li><a class="loock open" title="قفل" href="#">قفل</a></li>
                                        <li><a class="clear" title="خالی کردن این بخش" href="#">پاک کردن</a></li>
                                    </ul>
                                </div>
                                <div class="title-section-card">وارد کردن و یا انتخاب نسخه</div>
                            </div>
                            <div class="loader_bo hidden"><div class="cssload-speeding-wheel"></div></div>
                            <div class="main_step tab_order_rate">

                                <div id="new_loader_presentation">

                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li role="presentation" class="{{ (@$followUp['presentation']) ? "" : "active" }}"><a href="#add_prescription" aria-controls="add_prescription" role="tab" data-toggle="tab">وارد کردن نسخه جدید</a></li>
                                        <li role="presentation"><a href="#image_prescription" aria-controls="image_prescription" role="tab" data-toggle="tab">بارگذاری تصویر نسخه</a></li>
                                        <li role="presentation" class="{{ (@$followUp['presentation']) ? "active" : "" }}"><a href="#archive_prescription" aria-controls="archive_prescription" role="tab" data-toggle="tab">نسخه های بایگانی شده</a></li>
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane {{ (@$followUp['presentation']) ? "" : "active" }}" id="add_prescription">

                                            @include('site.order.new-step.step-one-import')

                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="image_prescription">

                                            @include('site.order.new-step.step-one-image')

                                        </div>
                                        <div role="tabpanel" class="tab-pane {{ (@$followUp['presentation']) ? "active" : "" }}" id="archive_prescription">

                                            @include('site.order.new-step.step-one-list')

                                        </div>
                                    </div>

                                </div>

                                <input type="hidden" id="order_key" name="order_key" value="{{ @$order_key }}">
                                <input type="hidden" id="presentation_edit" name="presentation_edit" value="">

                            </div>
                        </div>


                        <div class="zm_step_box {{ (!@$followUp['product'] && @$followUp['presentation']) ? "active" : "" }}" id="products_box_op">
                            <div class="new-form-inner">
                                <div class="parametr_controller">
                                    <ul>
                                        <li><a class="accardeon" title="بستن و باز کردن این بخش" href="#">مینیمایز</a></li>
                                        <li><a class="edit" title="ویرایش" href="#">ویرایش</a></li>
                                        <li><a class="loock open" title="قفل" href="#">قفل</a></li>
                                        <li><a class="clear" title="خالی کردن این بخش" href="#">پاک کردن</a></li>
                                    </ul>
                                </div>
                                <div class="title-section-card">انتخاب عدسی و یا لنز</div>
                            </div>
                            <div class="loader_bo hidden"><div class="cssload-speeding-wheel"></div></div>
                            <div class="main_step">
                                <div id="product_archive"></div>
                            </div>
                        </div>

                        <div class="zm_step_box" id="seller_box_op">
                            <div class="new-form-inner">
                                <div class="parametr_controller">
                                    <ul>
                                        <li><a class="accardeon" title="بستن و باز کردن این بخش" href="#">مینیمایز</a></li>
                                        <li><a class="edit" title="ویرایش" href="#">ویرایش</a></li>
                                        <li><a class="loock open" title="قفل" href="#">قفل</a></li>
                                        <li><a class="clear" title="خالی کردن این بخش" href="#">پاک کردن</a></li>
                                    </ul>
                                </div>
                                <div class="title-section-card">انتخاب عامل</div>
                            </div>
                            <div class="loader_bo hidden"><div class="cssload-speeding-wheel"></div></div>
                            <div class="main_step"></div>
                        </div>

                        <div class="zm_step_box {{ (@$followUp['product'] && @$followUp['presentation']) ? "active" : "" }}" id="lathe_box_op" style="display: none">
                            <div class="new-form-inner">
                                <div class="parametr_controller">
                                    <ul>
                                        <li><a class="accardeon" title="بستن و باز کردن این بخش" href="#">مینیمایز</a></li>
                                        <li><a class="edit" title="ویرایش" href="#">ویرایش</a></li>
                                        <li><a class="loock open" title="قفل" href="#">قفل</a></li>
                                        <li><a class="clear" title="خالی کردن این بخش" href="#">پاک کردن</a></li>
                                    </ul>
                                </div>
                                <div class="title-section-card">خدمات لابراتوار</div>
                            </div>
                            <div class="loader_bo hidden"><div class="cssload-speeding-wheel"></div></div>
                            <div class="main_step"></div>
                        </div>

                        @if(!@$followUp['date'])
                        <div class="zm_step_box" id="data_box_op">
                            <div class="new-form-inner">
                                <div class="parametr_controller">
                                    <ul>
                                        <li><a class="accardeon" title="بستن و باز کردن این بخش" href="#">مینیمایز</a></li>
                                        <li><a class="edit" title="ویرایش" href="#">ویرایش</a></li>
                                        <li><a class="loock open" title="قفل" href="#">قفل</a></li>
                                        <li><a class="clear" title="خالی کردن این بخش" href="#">پاک کردن</a></li>
                                    </ul>
                                </div>
                                <div class="title-section-card">زمان سفارش</div>
                            </div>
                            <div class="loader_bo hidden"><div class="cssload-speeding-wheel"></div></div>
                            <div class="main_step"></div>
                        </div>
                        @endif

                        <div class="enterbox">
                            <button type="submit" id="submit_full_form" class="btn btn-success" disabled style="line-height: 24px;width: 100%;">ثبت سفارش</button>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>


    <script>
        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).delegate('#prisma .switchery-default', 'click', function (e) {

                if($('.prismaInput').hasClass('active')){
                    $('.prismaInput').removeClass('active');
                    $('.prismaInput input').attr('disabled', 'true').removeClass('input_control').removeClass('is-invalid');
                }else{
                    $('.prismaInput').addClass('active');
                    $('.prismaInput input').removeAttr('disabled').addClass('input_control');
                }

            });

            $(document).delegate('#dodid .switchery-default', 'click', function (e) {

                if($('.addcontroller').hasClass('active')){
                    $('.addcontroller').removeClass('active');
                    $('.addcontroller select').removeClass('input_control').removeClass('is-invalid').attr('disabled', 'true');
                    $('.dodidInput').removeClass('active');
                    $('.dodid_input_control').attr('disabled', 'true').removeClass('input_control').removeClass('is-invalid');
                }else{
                    $('.addcontroller').addClass('active');

                    if($('#add_prescription').hasClass('optical_glass')) {
                        $('.addcontroller select').addClass('input_control').removeAttr('disabled');
                    }

                    $('.dodidInput').addClass('active');
                    $('.dodid_input_control').removeAttr('disabled').addClass('input_control');
                }

            });


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

            $('#add_prescription').addClass('optical_glass');
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


            $(document).delegate('#product_type .switchery-default', 'click', function (e) {

                $('#add_prescription').toggleClass('optical_glass');

                if(!$('#add_prescription').hasClass('optical_glass')){
                    $('.optic').css('display', 'none');
                    $('.prismaInput').removeClass('active');
                    $('.prismaInput input').attr('disabled', 'true').removeClass('input_control').removeClass('is-invalid');
                    $('.addcontroller select').removeClass('input_control').removeClass('is-invalid').attr('disabled', 'true');

                    var obj_sph = JSON.parse($('#sph_lens').val());
                    showjsondata('select[name="rsph"]', obj_sph);
                    showjsondata('select[name="lsph"]', obj_sph);

                    var obj_cyl = JSON.parse($('#cyl_lens').val());
                    showjsondata('select[name="rcyl"]', obj_cyl);
                    showjsondata('select[name="lcyl"]', obj_cyl);

                    var obj_axis = JSON.parse($('#axis_lens').val());
                    showjsondata('select[name="raxis"]', obj_axis);
                    showjsondata('select[name="laxis"]', obj_axis);

                }else{
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
                }

            });

            $('#product_type_image .switchery-default').toggleClass('optic');
            $('#product_type_image .switchery-default').parent().toggleClass('optic');
            $('.lathe_group').css('display', 'block');

            $(document).delegate('#product_type_image .switchery-default', 'click', function (e) {

                $(this).toggleClass('optic');
                $(this).parent().toggleClass('optic');

                if($(this).hasClass('optic')){
                    $('.lathe_group').css('display', 'block');
                }else{
                    $('.lathe_group').css('display', 'none');
                }

            });

            function ErrorData(result) {
                $.toast({
                    heading: result,
                    position: 'bottom-right',
                    loaderBg:'#862e15',
                    bgColor: '#e74e1d',
                    textColor: '#fff',
                    hideAfter: 5000,
                    stack: 6
                });
            }

            function SuccessData(result) {
                $.toast({
                    heading: result,
                    position: 'bottom-right',
                    loaderBg:'#008a66',
                    bgColor: '#00c292',
                    textColor: '#fff',
                    hideAfter: 5000,
                    stack: 6
                });
            }

            $(document).delegate('.form_prescription', 'submit', function (e) {

                var havinput = false;
                data_full = {};
                image_full = {};
                $(this).find('.input_control').each(function () {
                    if(!$(this).val()){
                        $(this).addClass('is-invalid');
                        havinput = true;
                    }else{
                        $(this).removeClass('is-invalid');
                    }
                });

                var counter = 0;
                $(this).find('input').each(function () {
                    if($(this).attr('name') === "two_eyes"){
                        if($('.dodidInput').hasClass('active')){
                            data_full[$(this).attr('name')] = 1;
                        }else{
                            data_full[$(this).attr('name')] = 0;
                        }
                    }else if($(this).attr('name') === "prisma"){
                        if($('.prismaInput').hasClass('active')){
                            data_full[$(this).attr('name')] = 1;
                        }else{
                            data_full[$(this).attr('name')] = 0;
                        }
                    }else if($(this).attr('name') === "product_type"){
                        if($('#add_prescription').hasClass('optical_glass')){
                            data_full[$(this).attr('name')] = 'optical_glass';
                        }else{
                            data_full[$(this).attr('name')] = 'lens';
                        }
                    }else if($(this).attr('name') === "product_type_image"){
                        if($('#product_type_image').hasClass('optic')){
                            data_full[$(this).attr('name')] = 'optical_glass';
                        }else{
                            data_full[$(this).attr('name')] = 'lens';
                        }
                    }else if($(this).attr('name') === "lathe"){
                        if($('.box_lathe_btn').hasClass('open_sub')){
                            data_full[$(this).attr('name')] = 1;
                        }else{
                            data_full[$(this).attr('name')] = 0;
                        }
                    }else if($(this).attr('name') === "prescription_archive"){
                        if($(this).attr('checked')){
                            data_full[$(this).attr('name')] = $(this).val();
                        }
                    }else if($(this).attr('name') === "image[]"){
                        image_full[counter] = $(this).val();
                        counter++;
                    }else{
                        data_full[$(this).attr('name')] = $(this).val();
                    }
                });


                $(this).find('select').each(function () {
                    data_full[$(this).attr('name')] = $(this).val();
                });

                if(image_full){
                    data_full["images"] = image_full;
                }


                if(havinput) return false;

                $(this).find('button[type="submit"]').attr("disabled", 'disabled').html('لطفا صبر کنید ، در حال بارگذاری ...');
                var action = $(this).attr('action');
                var liveitem = $(this);

                var jsonString = JSON.stringify(data_full);

                $('#presentation_box_op .loader_bo').removeClass('hidden');
                $.ajax({
                    method: 'POST',
                    url: action,
                    data: {json : jsonString, edit : $('#presentation_edit').val()}
                }).done(function(result){

                    $('#presentation_box_op .loader_bo').addClass('hidden');
                    if(result.staus === 'success'){

                        liveitem.find('button[type="submit"]').html('ذخیره شد');
                        $('#presentation_box_op').removeClass('active');

                        //$('#refresh_box').attr("disabled", 'disabled');

                        $.toast({
                            heading: 'نسخه شما با موفقیت ذخیره شد.',
                            position: 'bottom-right',
                            loaderBg:'#008a66',
                            bgColor: '#00c292',
                            textColor: '#fff',
                            hideAfter: 5000,
                            stack: 6
                        });

                        //window.history.pushState("", "", "{{--{{ url('order/complete') }}--}}/" + result.order_key);
                        $("#order_key").val(result.order_key);
                        GetProduct();

                    }else{
                        liveitem.find('button[type="submit"]').removeAttr("disabled").html('گام بعدی');
                        ErrorData(result.error);
                    }
                }).error(function (e) {
                    $('#presentation_box_op .loader_bo').addClass('hidden');
                });

                return false;

            });


            @if(@$followUp['follow'])
                $('.form_prescription_follow').submit();
            @endif


            $(document).delegate('.delete-warning', 'click', function (e) {
                e.preventDefault();
                var hreflink = $(this).attr('href');
                swal({
                    title: "",
                    text: "آیا از حذف این نسخه مطمئن هستید ؟",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: "خیر نیازی نیست",
                    confirmButtonText: "بله اطمینان دارم",
                    closeOnConfirm: false
                }, function(){
                    window.location.href = hreflink;
                });
            });

            $(document).delegate('.archive_selector', 'click', function (e) {
                e.preventDefault();
                $('.archive_selector').removeClass('active');
                $('.archive_selector').find('input').removeAttr('checked');
                $(this).addClass('active');
                $(this).find('input').attr('checked', 'checked');
            });

            $(document).delegate('#refresh_box', 'click', function (e) {
                e.preventDefault();

                var liveitem = $(this);

                liveitem.find('span').html('در حال بروز رسانی ...');

                $.ajax({
                    method: 'POST',
                    url: '{{ url('order/new/prescription-refresh') }}',
                    data: {}
                }).done(function(result){

                    $('.refresh_main').html(result);
                    liveitem.find('span').html('بروز رسانی');

                    $.toast({
                        heading: 'آرشیو نسخه های بایگانی شده بروز رسانی شد.',
                        position: 'bottom-right',
                        loaderBg:'#008a66',
                        bgColor: '#00c292',
                        textColor: '#fff',
                        hideAfter: 5000,
                        stack: 6
                    });

                });

            });


            function GetProduct() {

                @if(!@$followUp['product'])
                    $('#products_box_op').addClass('active');
                @endif

                $('#products_box_op .loader_bo').removeClass('hidden');

                $.ajax({
                    method: 'GET',
                    url: '{{ url('order/new/get_products') }}',
                    data: {order_key : $("#order_key").val()}
                }).done(function(result){

                    $('#products_box_op .loader_bo').addClass('hidden');
                    $('#product_archive').html(result);
                    FilterProduct();

                }).error(function (e) {
                    $('#products_box_op .loader_bo').addClass('hidden');
                });

            }

            function FilterProduct() {

                $('.slider_min_max').each(function () {

                    var sliderMin = $(this).find('.min').attr('data-default');
                    var sliderMinele = $(this).find('.min');
                    var sliderMax = $(this).find('.max').attr('data-default');
                    var sliderMaxele = $(this).find('.max');
                    var sliderAmountele = $(this).find('.amount');
                    var sliderid = $(this).find('.slider-range').attr('id');

                    if(sliderid === 'slider-range-price'){
                        $(this).find('.slider-range').slider({
                            range: true,
                            min: parseInt(sliderMin),
                            max: parseInt(sliderMax),
                            step: 1000,
                            values: [ parseInt(sliderMin), parseInt(sliderMax) ],
                            slide: function( event, ui ) {
                                sliderAmountele.val( ui.values[ 0 ] + " - " + ui.values[ 1 ] + ' تومان' );
                                sliderMinele.val(ui.values[ 0 ]);
                                sliderMaxele.val(ui.values[ 1 ]);
                            }
                        });
                        sliderAmountele.val( $(this).find('.slider-range').slider( "values", 0 ) + " - " + $(this).find('.slider-range').slider( "values", 1 ) + ' تومان'  );
                    }else{
                        $(this).find('.slider-range').slider({
                            range: true,
                            min: parseInt(sliderMin),
                            max: parseInt(sliderMax),
                            values: [ parseInt(sliderMin), parseInt(sliderMax) ],
                            slide: function( event, ui ) {
                                sliderAmountele.val( ui.values[ 0 ] + " - " + ui.values[ 1 ] );
                                sliderMinele.val(ui.values[ 0 ]);
                                sliderMaxele.val(ui.values[ 1 ]);
                            }
                        });
                        sliderAmountele.val( $(this).find('.slider-range').slider( "values", 0 ) + " - " + $(this).find('.slider-range').slider( "values", 1 ) );
                    }

                    sliderMinele.val($(this).find('.slider-range').slider( "values", 0 ));
                    sliderMaxele.val($(this).find('.slider-range').slider( "values", 1 ));

                });

            }

            $(document).delegate('#products_box_op .pagination a', 'click', function (e) {
                e.preventDefault();

                $('#load a').css('color', '#dfecf6');
                $('#load').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="/images/loading.gif" />');

                $('#products_box_op .loader_bo').removeClass('hidden');
                var url = $(this).attr('href');
                $.ajax({
                    url : url
                }).done(function (data) {
                    $('#products_box_op .loader_bo').addClass('hidden');
                    $('#product_archive').html(data);
                    FilterProduct();
                }).error(function (e) {
                    $('#products_box_op .loader_bo').addClass('hidden');
                });
                //window.history.pushState("", "", url);
            });


            $(document).delegate('#fav_product', 'click', function (e) {
                e.preventDefault();

                $('#products_box_op').addClass('active');
                $.ajax({
                    method: 'GET',
                    url: '{{ url('order/new/get_products') }}',
                    data: {json : $("#filterSages").val(), fav_product : "yes", order_key : $("#order_key").val()}
                }).done(function(result){

                    $('#product_archive').html(result);
                    FilterProduct();

                });

            });

            $(document).delegate('#show_all', 'click', function (e) {
                e.preventDefault();

                GetProduct();

            });

            $(document).delegate('#search_product', 'click', function (e) {
                e.preventDefault();

                $('#products_box_op .loader_bo').removeClass('hidden');
                $('#products_box_op').addClass('active');
                $.ajax({
                    method: 'GET',
                    url: '{{ url('order/new/get_products') }}',
                    data: {json : $("#filterSages").val(), search_name : $('#search_name').val(), order_key : $("#order_key").val()}
                }).done(function(result){

                    $('#products_box_op .loader_bo').addClass('hidden');
                    $('#product_archive').html(result);
                    FilterProduct();

                }).error(function (e) {
                    $('#products_box_op .loader_bo').addClass('hidden');
                });


            });

            $(document).delegate('.controll_filter', 'submit', function (e) {

                var data_full = {};

                $(this).find('input').each(function () {
                    data_full[$(this).attr('name')] = $(this).val();
                });

                $(this).find('select').each(function () {
                    data_full[$(this).attr('name')] = $(this).val();
                });

                $(this).find('button[type="submit"]').attr("disabled", 'disabled').html('لطفا صبر کنید ، در حال بارگذاری ...');
                var action = $(this).attr('action');

                var jsonString = JSON.stringify(data_full);

                $('#products_box_op .loader_bo').removeClass('hidden');
                $.ajax({
                    method: 'GET',
                    url: action,
                    data: {json : jsonString, search_name : $('#search_name').val(), order_key : $("#order_key").val()}
                }).done(function(result){
                    $('#products_box_op .loader_bo').addClass('hidden');
                    $('#product_archive').html(result);
                    FilterProduct();
                }).error(function (e) {
                    $('#products_box_op .loader_bo').addClass('hidden');
                });

                return false;

            });



            function GetSeller() {

                $('#seller_box_op .loader_bo').removeClass('hidden');
                $('#seller_box_op').addClass('active');
                $.ajax({
                    method: 'GET',
                    url: '{{ url('order/new/get_seller') }}',
                    data: {order_key : $("#order_key").val()}
                }).done(function(result){

                    $('#seller_box_op .loader_bo').addClass('hidden');
                    $('#seller_box_op .main_step').html(result);

                }).error(function (e) {
                    $('#seller_box_op .loader_bo').addClass('hidden');
                });


            }

            $(document).delegate('.add_to_card', 'click', function (e) {
                e.preventDefault();

                $('.add_to_card').removeClass('active');
                var data_link = $(this).attr('href');
                $(this).addClass('active');

                $('#products_box_op .loader_bo').removeClass('hidden');

                $.ajax({
                    method: 'GET',
                    url: data_link,
                    data: {order_key : $("#order_key").val()}
                }).done(function(result){

                    $('#products_box_op .loader_bo').addClass('hidden');
                    if(result.staus === "success"){

                        $('#products_box_op').removeClass('active');
                        $.toast({
                            heading: 'محصول به لیست سفارشات شما اضافه شد.',
                            position: 'bottom-right',
                            loaderBg:'#008a66',
                            bgColor: '#00c292',
                            textColor: '#fff',
                            hideAfter: 5000,
                            stack: 6
                        });

                        @if(!@$followUp['follow'])
                            GetSeller();
                        @else
                            $('#lathe_box_op').addClass('active');
                        @endif
                    }else{
                        ErrorData(result.error);
                    }

                }).error(function (e) {
                    $('#products_box_op .loader_bo').addClass('hidden');
                });


            });

            $(document).delegate('.select_seller', 'click', function (e) {
                e.preventDefault();

                $('#seller_box_op .loader_bo').removeClass('hidden');
                $('.select_seller').removeClass('active');
                $(this).addClass('active');
                $.ajax({
                    method: 'POST',
                    url: '{{ url('order/new/seller') }}',
                    data: {order_key : $("#order_key").val(), select_key : $(this).attr('data-key')}
                }).done(function(result){

                    $('#seller_box_op .loader_bo').addClass('hidden');
                    if(result.staus === "success"){

                        $('#seller_box_op').removeClass('active');


                        if(result.type_product === "lens"){
                            $('#lathe_box_op').css('display', 'none');
                            Getdatetime();
                        }else{
                            $('#lathe_box_op').css('display', 'block');
                            GetService();
                        }


                        $.toast({
                            heading: 'عامل با موفقیت اضافه شد.',
                            position: 'bottom-right',
                            loaderBg:'#008a66',
                            bgColor: '#00c292',
                            textColor: '#fff',
                            hideAfter: 5000,
                            stack: 6
                        });

                    }else{
                        ErrorData(result.error);
                    }

                }).error(function (e) {
                    $('#seller_box_op .loader_bo').addClass('hidden');
                });

            });


            function GetService() {

                $('#lathe_box_op .loader_bo').removeClass('hidden');
                $('#lathe_box_op').addClass('active');
                $.ajax({
                    method: 'GET',
                    url: '{{ url('order/new/get_service') }}',
                    data: {order_key : $("#order_key").val()}
                }).done(function(result){
                    $('#lathe_box_op .loader_bo').addClass('hidden');

                    $('#lathe_box_op .main_step').html(result);

                }).error(function (e) {
                    $('#lathe_box_op .loader_bo').addClass('hidden');
                });

            }

            $(document).delegate('.select_labrator', 'click', function (e) {
                e.preventDefault();

                $('.select_labrator').removeClass('active');
                $(this).addClass('active');

                var data_full = [];
                var counter = 0;
                $(this).parent().parent().find('input').each(function () {
                    if($(this).attr('name') === "service[]"){
                        if(this.checked === true){
                            data_full[counter] = $(this).val();
                            counter++;
                        }
                    }
                });

                var jsonString = JSON.stringify(data_full);

                if(counter <= 0){
                    ErrorData('لطفا خدمات مورد نیاز را انتخاب کنید');
                    return false;
                }

                $('#lathe_box_op .loader_bo').removeClass('hidden');

                $.ajax({
                    method: 'POST',
                    url: '{{ url('order/new/service') }}',
                    data: {order_key : $("#order_key").val(), json : jsonString, select_key : $(this).attr('data-key')}
                }).done(function(result){

                    $('#lathe_box_op .loader_bo').addClass('hidden');

                    if(result.staus === "success"){

                        $('#lathe_box_op').removeClass('active');

                        Getdatetime();

                        $.toast({
                            heading: 'عامل با موفقیت اضافه شد.',
                            position: 'bottom-right',
                            loaderBg:'#008a66',
                            bgColor: '#00c292',
                            textColor: '#fff',
                            hideAfter: 5000,
                            stack: 6
                        });

                    }else{
                        ErrorData(result.error);
                    }

                }).error(function (e) {
                    $('#lathe_box_op .loader_bo').addClass('hidden');
                });

            });

            $(document).delegate('#no_service', 'click', function (e) {
                e.preventDefault();

                $('#lathe_box_op .loader_bo').removeClass('hidden');

                var jsonString = JSON.stringify([]);
                $.ajax({
                    method: 'POST',
                    url: '{{ url('order/new/service') }}',
                    data: {order_key : $("#order_key").val(), json : jsonString, select_key : 0}
                }).done(function(result){

                    $('#lathe_box_op .loader_bo').addClass('hidden');

                    if(result.staus === "success"){

                        $('#lathe_box_op').removeClass('active');
                        Getdatetime();

                    }else{
                        ErrorData(result.error);
                    }

                }).error(function (e) {
                    $('#lathe_box_op .loader_bo').addClass('hidden');
                });

            });

            $(document).delegate('#lathe', 'change', function (e) {
                e.preventDefault();

                $(this).parent().parent().parent().toggleClass('open_sub');


                if(!$('.box_lathe_btn').hasClass('open_sub')){
                    $('.box_lathe_btn .lathe_main_cox input.input_control').attr('disabled', 'true').removeClass('input_control').removeClass('is-invalid');
                }else{
                    $('.box_lathe_btn .lathe_main_cox input.input_control').addClass('input_control').removeAttr('disabled');
                }


            });

            $(document).delegate('#lathe_vizit', 'change', function (e) {
                e.preventDefault();

                console.log('sss');
                $(this).parent().parent().parent().toggleClass('open_sub');


                if(!$('.box_lathe_btn_image').hasClass('open_sub')){
                    $('.box_lathe_btn_image .lathe_main_cox input.input_control').attr('disabled', 'true').removeClass('input_control').removeClass('is-invalid');
                }else{
                    $('.box_lathe_btn_image .lathe_main_cox input.input_control').addClass('input_control').removeAttr('disabled');
                }


            });

            function Getdatetime() {

                $('#data_box_op').addClass('active');
                $.ajax({
                    method: 'GET',
                    url: '{{ url('order/new/get_date') }}',
                    data: {order_key : $("#order_key").val()}
                }).done(function(result){

                    $('#data_box_op .main_step').html(result);

                });

            }

            $(document).delegate('.type_shipping', 'click', function (e) {
                e.preventDefault();

                $('.type_shipping').removeClass('active');
                $('.type_shipping input').removeAttr('checked');
                $('.type_shipping_main .tab').removeClass('active');

                var dataTab = $(this).find('input').attr('id');
                $(this).addClass('active');
                $('.type_shipping_main .' + dataTab).addClass('active');
                $(this).find('#' + dataTab).prop("checked", true);


            });


            $(document).delegate('#date_controller', 'submit', function (e) {

                var type_shipping = $('input[name="type_shipping"]:checked').val();

                var havinput = false;
                data_full = {};
                $(this).find('.type_shipping_main .active .input_control').each(function () {
                    if(!$(this).val()){
                        $(this).addClass('is-invalid');
                        havinput = true;
                    }else{
                        $(this).removeClass('is-invalid');
                    }
                });

                $(this).find('.type_shipping_main .active .input_control').each(function () {
                    data_full[$(this).attr('name')] = $(this).val();
                });



                if(havinput) return false;

                $(this).find('button[type="submit"]').attr("disabled", 'disabled').html('لطفا صبر کنید ، در حال بارگذاری ...');

                var action = $(this).attr('action');
                var liveitem = $(this);

                var jsonString = JSON.stringify(data_full);

                $('#data_box_op .loader_bo').removeClass('hidden');

                $.ajax({
                    method: 'POST',
                    url: action,
                    data: {order_key : $("#order_key").val(), type_shipping : type_shipping, json : jsonString}
                }).done(function(result){

                    $('#data_box_op .loader_bo').addClass('hidden');

                    if(result.staus === 'success'){

                        liveitem.find('button[type="submit"]').html('ذخیره شد');

                        $.toast({
                            heading: 'زمان سفارش شما با موفقیت انتخاب شد.',
                            position: 'bottom-right',
                            loaderBg:'#008a66',
                            bgColor: '#00c292',
                            textColor: '#fff',
                            hideAfter: 5000,
                            stack: 6
                        });

                        $('#submit_full_form').removeAttr("disabled");


                    }else{
                        liveitem.find('button[type="submit"]').removeAttr("disabled").html('گام بعدی');
                        ErrorData(result.error);
                    }
                }).error(function (e) {
                    $('#data_box_op .loader_bo').addClass('hidden');
                });

                return false;

            });

            $(document).delegate('#submit_full_form', 'click', function (e) {

                var liveitem = $(this);
                $(this).attr("disabled", 'disabled').html('لطفا صبر کنید ، در حال بارگذاری ...');

                $.ajax({
                    method: 'POST',
                    url: '{{ url('order/new/submit_full') }}',
                    data: {order_key : $("#order_key").val()}
                }).done(function(result){


                    if(result.staus === 'success'){


                        window.location.href = result.link;


                    }else{
                        liveitem.removeAttr("disabled").html('ثبت سفارش');
                        ErrorData(result.error);
                    }
                });

                return false;

            });

            /*$('.accardeon').click(function (e) {
                e.preventDefault();

                if(!$(this).parent().parent().find('a.loock').hasClass('closed')){
                    $(this).parent().parent().parent().parent().parent().toggleClass('active');
                }else{
                    ErrorData('این بخش قفل است.');
                }

            });*/

            $('.loock').click(function (e) {
                e.preventDefault();

                var liveitem = $(this);
                $.ajax({
                    method: 'POST',
                    url: '{{ url('order/new/lock-controll') }}',
                    data: {order_key : $("#order_key").val(), event : $(this).parent().parent().parent().parent().parent().attr('id')}
                }).done(function(result){

                    if(result.staus === 'success'){

                        liveitem.parent().parent().parent().parent().parent().removeClass('active');

                        if(result.actien === "closed"){
                            liveitem.addClass('closed').removeClass('open');
                        }else{
                            liveitem.addClass('open').removeClass('closed');
                        }

                        SuccessData(result.message);

                    }else{
                        ErrorData(result.error);
                    }
                });

            });

            /*$('.edit').click(function (e) {
                e.preventDefault();

                if(!$(this).parent().parent().find('a.loock').hasClass('closed')){
                    $(this).parent().parent().parent().parent().parent().addClass('active');
                }else{
                    ErrorData('این بخش قفل است.');
                }

            });*/



            /*$(document).delegate('.item_prescription .invite', 'click', function (e) {
                e.preventDefault();
                $(this).parent().parent().toggleClass('open');
            });*/

            $(document).delegate('#presentation_box_op .clear', 'click', function (e) {
                e.preventDefault();

                if(!$(this).parent().parent().find('a.loock').hasClass('closed')){

                    if($("#order_key").val()){

                    }

                    $('#presentation_box_op option:selected', this).remove();
                    $("#presentation_box_op select").val(null).trigger("change");

                    $('#presentation_box_op #gallery_place_holder').html('');
                    $('#presentation_box_op #gallery_results_wrapper').html('');
                    $('#presentation_box_op textarea').val('');
                    $('#presentation_box_op input').val('');
                    $('#presentation_box_op button[type="submit"]').html('گام بعدی').removeAttr('disabled');
                    $('#refresh_box').removeAttr('disabled');
                    $('#presentation_box_op .archive_selector').removeClass('active').find('input').removeAttr('checked');

                }else{
                    ErrorData('این بخش قفل است.');
                }

            });

            $(document).delegate('#presentation_box_op .edit', 'click', function (e) {
                e.preventDefault();

                if(!$(this).parent().parent().find('a.loock').hasClass('closed')){

                    $('#presentation_box_op button[type="submit"]').html('گام بعدی').removeAttr('disabled');
                    $('#refresh_box').removeAttr('disabled');

                    $(this).parent().parent().parent().parent().parent().addClass('active');
                    var prescription_archive = $(".archive_selector.active input[name='prescription_archive']").val();
                    if(prescription_archive){

                        $('#presentation_box_op .loader_bo').removeClass('hidden');

                        $.ajax({
                            method: 'POST',
                            url: '{{ url('order/get-prescription') }}',
                            data: {key : prescription_archive}
                        }).done(function(result){

                            $('#presentation_box_op .loader_bo').addClass('hidden');
                            if(result.error){
                                ErrorData(result.error);
                                return '';
                            }

                            $('#new_loader_presentation').html(result);
                            $('#presentation_edit').val(prescription_archive);

                        }).error(function (e) {
                            $('#presentation_box_op .loader_bo').addClass('hidden');
                        });

                    }

                }else{
                    ErrorData('این بخش قفل است.');
                }

            });

            $(document).delegate('#presentation_box_op .accardeon', 'click', function (e) {
                e.preventDefault();

                if(!$(this).parent().parent().find('a.loock').hasClass('closed')) {
                    $('#presentation_box_op').toggleClass('active');
                }else{
                    ErrorData('این بخش قفل است.');
                }

            });

            $(document).delegate('#products_box_op .clear', 'click', function (e) {
                e.preventDefault();

                if(!$(this).parent().parent().find('a.loock').hasClass('closed')) {
                    if ($('#product_archive').html()) {

                        $('#products_box_op').addClass('active');

                        GetProduct();

                    } else {
                        ErrorData('لطفا ابتدا مراحل قبلی را تگمیل کنید');
                    }
                }else{
                    ErrorData('این بخش قفل است.');
                }

            });

            $(document).delegate('#products_box_op .edit', 'click', function (e) {
                e.preventDefault();

                if(!$(this).parent().parent().find('a.loock').hasClass('closed')) {
                    if ($('#product_archive').html()) {

                        $('#products_box_op').addClass('active');

                    } else {
                        ErrorData('لطفا ابتدا مراحل قبلی را تگمیل کنید');
                    }
                }else{
                    ErrorData('این بخش قفل است.');
                }

            });

            $(document).delegate('#products_box_op .accardeon', 'click', function (e) {
                e.preventDefault();

                if(!$(this).parent().parent().find('a.loock').hasClass('closed')) {
                    if ($('#product_archive').html()) {

                        $('#products_box_op').toggleClass('active');

                    } else {
                        ErrorData('لطفا ابتدا مراحل قبلی را تگمیل کنید');
                    }
                }else{
                    ErrorData('این بخش قفل است.');
                }

            });

            $(document).delegate('#seller_box_op .clear', 'click', function (e) {
                e.preventDefault();

                if(!$(this).parent().parent().find('a.loock').hasClass('closed')) {
                    if ($('#seller_box_op #load_seller').html()) {

                        $('#seller_box_op').addClass('active');

                        GetSeller();

                    } else {
                        ErrorData('لطفا ابتدا مراحل قبلی را تگمیل کنید');
                    }
                }else{
                    ErrorData('این بخش قفل است.');
                }

            });

            $(document).delegate('#seller_box_op .edit', 'click', function (e) {
                e.preventDefault();

                if(!$(this).parent().parent().find('a.loock').hasClass('closed')) {
                    if ($('#seller_box_op #load_seller').html()) {

                        $('#seller_box_op').addClass('active');

                    } else {
                        ErrorData('لطفا ابتدا مراحل قبلی را تگمیل کنید');
                    }
                }else{
                    ErrorData('این بخش قفل است.');
                }

            });

            $(document).delegate('#seller_box_op .accardeon', 'click', function (e) {
                e.preventDefault();

                if(!$(this).parent().parent().find('a.loock').hasClass('closed')) {
                    if ($('#seller_box_op #load_seller').html()) {

                        $('#seller_box_op').toggleClass('active');

                    } else {
                        ErrorData('لطفا ابتدا مراحل قبلی را تگمیل کنید');
                    }
                }else{
                    ErrorData('این بخش قفل است.');
                }

            });

            $(document).delegate('#lathe_box_op .clear', 'click', function (e) {
                e.preventDefault();

                if(!$(this).parent().parent().find('a.loock').hasClass('closed')) {
                    if ($('#lathe_box_op #load_seller').html()) {

                        $('#lathe_box_op').addClass('active');

                        GetService();

                    } else {
                        ErrorData('لطفا ابتدا مراحل قبلی را تگمیل کنید');
                    }
                }else{
                    ErrorData('این بخش قفل است.');
                }

            });

            $(document).delegate('#lathe_box_op .edit', 'click', function (e) {
                e.preventDefault();

                if(!$(this).parent().parent().find('a.loock').hasClass('closed')) {
                    if ($('#lathe_box_op #load_seller').html()) {

                        $('#lathe_box_op').addClass('active');

                    } else {
                        ErrorData('لطفا ابتدا مراحل قبلی را تگمیل کنید');
                    }
                }else{
                    ErrorData('این بخش قفل است.');
                }

            });

            $(document).delegate('#lathe_box_op .accardeon', 'click', function (e) {
                e.preventDefault();

                if(!$(this).parent().parent().find('a.loock').hasClass('closed')) {
                    if ($('#lathe_box_op #load_seller').html()) {

                        $('#lathe_box_op').toggleClass('active');

                    } else {
                        ErrorData('لطفا ابتدا مراحل قبلی را تگمیل کنید');
                    }
                }else{
                    ErrorData('این بخش قفل است.');
                }

            });

            $(document).delegate('#data_box_op .clear', 'click', function (e) {
                e.preventDefault();

                if(!$(this).parent().parent().find('a.loock').hasClass('closed')) {
                    if ($('#load_date').html()) {

                        $('#data_box_op').addClass('active');

                        Getdatetime();

                    } else {
                        ErrorData('لطفا ابتدا مراحل قبلی را تگمیل کنید');
                    }
                }else{
                    ErrorData('این بخش قفل است.');
                }

            });

            $(document).delegate('#data_box_op .edit', 'click', function (e) {
                e.preventDefault();

                if(!$(this).parent().parent().find('a.loock').hasClass('closed')) {
                    if ($('#load_date').html()) {

                        $('#data_box_op').addClass('active');

                    } else {
                        ErrorData('لطفا ابتدا مراحل قبلی را تگمیل کنید');
                    }
                }else{
                    ErrorData('این بخش قفل است.');
                }

            });

            $(document).delegate('#data_box_op .accardeon', 'click', function (e) {
                e.preventDefault();

                if(!$(this).parent().parent().find('a.loock').hasClass('closed')) {
                    if ($('#load_date').html()) {

                        $('#data_box_op').toggleClass('active');

                    } else {
                        ErrorData('لطفا ابتدا مراحل قبلی را تگمیل کنید');
                    }
                }else{
                    ErrorData('این بخش قفل است.');
                }

            });








            $(document).delegate('.normal_receipt_date #getboxday', 'change', function (e) {
                e.preventDefault();

                $('.normal_receipt_time #getboxtime').attr("disabled", 'disabled').html('');
                $('.normal_get_date #sendboxday').attr("disabled", 'disabled').html('');
                $('.normal_get_time #sendboxtime').attr("disabled", 'disabled').html('');

                if(!$(this).val()) return false;

                $('.normal_receipt_date #getboxday').attr("disabled", 'disabled');


                $.ajax({
                    method: 'POST',
                    url: "{{ url('order/delivery_normal/receipt/time') }}",
                    data: {order_key : $("#order_key").val(), date : $(this).val()}
                }).done(function(result){

                    $('.normal_receipt_date #getboxday').removeAttr("disabled");
                    $('.normal_receipt_time #getboxtime').removeAttr("disabled").html(result);

                });

            });

            $(document).delegate('.normal_receipt_time #getboxtime', 'change', function (e) {
                e.preventDefault();

                $('.normal_get_date #sendboxday').attr("disabled", 'disabled').html('');
                $('.normal_get_time #sendboxtime').attr("disabled", 'disabled').html('');

                if(!$(this).val()) return false;

                $('.normal_receipt_time #getboxtime').attr("disabled", 'disabled');


                $.ajax({
                    method: 'POST',
                    url: "{{ url('order/delivery_normal/get/date') }}",
                    data: {order_key : $("#order_key").val(), receipt_date : $('.normal_receipt_date #getboxday').val(), receipt_time : $(this).val()}
                }).done(function(result){

                    $('.normal_receipt_time #getboxtime').removeAttr("disabled");
                    $('.normal_get_date #sendboxday').removeAttr("disabled").html(result);

                });

            });

            $(document).delegate('.normal_get_date #sendboxday', 'change', function (e) {
                e.preventDefault();

                $('.normal_get_time #sendboxtime').attr("disabled", 'disabled').html('');
                if(!$(this).val()) return false;

                $('.normal_get_date #sendboxday').attr("disabled", 'disabled');


                $.ajax({
                    method: 'POST',
                    url: "{{ url('order/delivery_normal/get/time') }}",
                    data: {order_key : $("#order_key").val(), receipt_date : $('.normal_receipt_date #getboxday').val(), receipt_time : $('.normal_receipt_time #getboxtime').val(), get_date : $(this).val()}
                }).done(function(result){

                    $('.normal_get_date #sendboxday').removeAttr("disabled");
                    $('.normal_get_time #sendboxtime').removeAttr("disabled").html(result);

                });

            });

            $(document).delegate('.box_timer_set', 'click', function (e) {
                e.preventDefault();

                $('.box_timer_set').removeClass('active');
                $(this).addClass('active');

                var getdate = $(this).attr('data-getdate');
                var gettime = $(this).attr('data-gettime');

                var senddate = $(this).attr('data-senddate');
                var sendtime = $(this).attr('data-sendtime');

                $('#get_time').val(gettime);
                $('#get_date').val(getdate);

                $('#send_time').val(sendtime);
                $('#send_date').val(senddate);

            });


        })
    </script>



@endsection
