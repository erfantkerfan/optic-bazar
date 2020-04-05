<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap Core CSS -->
    <link href="{!! asset('assets/adminui/bootstrap/dist/css/bootstrap.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/adminui/bootstrap/dist/css/bootstrap.rtl.min.css') !!}" rel="stylesheet">

    <link href="{!! asset('assets/adminui/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/adminui/plugins/bower_components/toast-master/css/jquery.toast.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/adminui/plugins/bower_components/morrisjs/morris.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/adminui/plugins/bower_components/sweetalert/sweetalert.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/adminui/plugins/bower_components/html5-editor/bootstrap-wysihtml5.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/adminui/plugins/bower_components/nestable/nestable.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/adminui/plugins/bower_components/bootstrap-select/bootstrap-select.min.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/adminui/plugins/bower_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/adminui/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') !!}" rel="stylesheet">

    <link href="{!! asset('assets/adminui/assets/css/animate.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/adminui/assets/css/style.css') !!}" rel="stylesheet">
    <link href="{!! asset('assets/adminui/assets/css/colors/default.css') !!}" id="theme"  rel="stylesheet">
    <link href="{!! asset('assets/adminui/assets/css/zm_style.css') !!}" id="theme"  rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="{!! asset('assets/adminui/plugins/bower_components/jquery/dist/jquery.min.js') !!}"></script>
    <script src="{!! asset('assets/adminui/plugins/bower_components/sweetalert/sweetalert.min.js') !!}"></script>

</head>
<body>

<div class="row">
    <div class="col-md-12 col-lg-12 col-sm-12">
        @yield('content')
    </div>
</div>

<!-- /#wrapper -->
<!-- jQuery -->
<script src="{!! asset('assets/adminui/bootstrap/dist/js/bootstrap.min.js') !!}"></script>
<script src="{!! asset('assets/adminui/bootstrap/dist/js/bootstrap.min.js') !!}"></script>
<script src="{!! asset('assets/adminui/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js') !!}"></script>
<script src="{!! asset('assets/adminui/assets/js/jquery.slimscroll.js') !!}"></script>
<script src="{!! asset('assets/adminui/assets/js/waves.js') !!}"></script>
<script src="{!! asset('assets/adminui/plugins/bower_components/waypoints/lib/jquery.waypoints.js') !!}"></script>
<script src="{!! asset('assets/adminui/plugins/bower_components/counterup/jquery.counterup.min.js') !!}"></script>
<script src="{!! asset('assets/adminui/plugins/bower_components/raphael/raphael-min.js') !!}"></script>
<script src="{!! asset('assets/adminui/plugins/bower_components/morrisjs/morris.js') !!}"></script>
<script src="{!! asset('assets/adminui/plugins/bower_components/html5-editor/wysihtml5-0.3.0.js') !!}"></script>
<script src="{!! asset('assets/adminui/plugins/bower_components/html5-editor/bootstrap-wysihtml5.js') !!}"></script>
<script src="{!! asset('assets/adminui/plugins/bower_components/nestable/jquery.nestable.js') !!}"></script>
<script src="{!! asset('assets/adminui/assets/js/custom.js') !!}"></script>
<script src="{!! asset('assets/adminui/assets/js/dashboard1.js') !!}"></script>
<script src="{!! asset('assets/adminui/plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js') !!}"></script>
<script src="{!! asset('assets/adminui/plugins/bower_components/jquery-sparkline/jquery.charts-sparkline.js') !!}"></script>
<script src="{!! asset('assets/adminui/plugins/bower_components/toast-master/js/jquery.toast.js') !!}"></script>
<script src="{!! asset('assets/adminui/plugins/bower_components/styleswitcher/jQuery.style.switcher.js') !!}"></script>

<script src="{!! asset('assets/adminui/plugins/bower_components/bootstrap-select/bootstrap-select.min.js') !!}"></script>
<script src="{!! asset('assets/adminui/plugins/bower_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') !!}"></script>
<script src="{!! asset('assets/adminui/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') !!}"></script>

<script>
        $(document).ready(function () {
            $('.textarea_editor').wysihtml5();
            $('.textarea_editor2').wysihtml5();
        });
    </script>
<script type="text/javascript">
        $( document ).ready(function() {

            var updateOutput = function(e) {
                var list   = e.length ? e : $(e.target)
            };

            $('.removemenu').click(function(e){
                $(this).parent().parent().remove();
            });


            $('.nestable2').nestable({group: 1}).on('change', updateOutput);

            updateOutput($('.nestable2').data('output', $('.nestable2-output')));
        });
    </script>
<script type="text/javascript">

    $(document).ready(function() {
        $('.sa-warning').click(function(e){
            e.preventDefault();
            var hreflink = $(this).attr('href');
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function(){
                window.location.href = hreflink;
            });
        });

        $('#phone_acces input[name="phone_cheack[]"]').click(function(){
            $(this).parent().parent().find('.box_phone').toggleClass("active");
        });

        $('#company_country').change(function(){
            var mainCat= $('#company_country').val();
            $.ajax({
                url:"<?php echo url('contry-cod?contry=') ?>" + mainCat,
                type:'GET',
                data:'',
                success:function(results){
                    //  alert(results);
                    $(".phone_cod").val(results);
                }
            });

        });

        $('.mydatepicker, #datepicker').datepicker({
            format: 'yyyy/mm/dd'
        });

    });

</script>

<!--Style Switcher -->


</body>
</html>
