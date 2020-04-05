<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="{{ (@$request->type == 'import') ? "active" : "" }}"><a href="#add_prescription" aria-controls="add_prescription" role="tab" data-toggle="tab">وارد کردن نسخه جدید</a></li>
    <li role="presentation" class="{{ (@$request->type != 'import') ? "active" : "" }}"><a href="#image_prescription" aria-controls="image_prescription" role="tab" data-toggle="tab">اپلود تصویر نسخه</a></li>
    <li role="presentation"><a href="#archive_prescription" aria-controls="archive_prescription" role="tab" data-toggle="tab">نسخه های بایگانی شده</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    <div role="tabpanel" class="tab-pane {{ (@$request->type == 'import') ? "active" : "" }} {{ (@$request->type_product == 'optical_glass') ? 'optical_glass' : '' }}" id="add_prescription">

        @include('site.order.new-step.step-one-import')

    </div>
    <div role="tabpanel" class="tab-pane {{ (@$request->type != 'import') ? "active" : "" }}" id="image_prescription">

        @include('site.order.new-step.step-one-image')

    </div>
    <div role="tabpanel" class="tab-pane" id="archive_prescription">

        @include('site.order.new-step.step-one-list')

    </div>
</div>

<script src="{!! asset('assets/adminui/plugins/bower_components/select2/select2.min.js') !!}"></script>
<script src="{!! asset('assets/adminui/plugins/bower_components/switchery/dist/switchery.min.js') !!}"></script>
<script src="{!! asset('assets/adminui/plugins/bower_components/datepicker/v2/persian-date.js') !!}"></script>
<script src="{!! asset('assets/adminui/plugins/bower_components/datepicker/v2/persian-datepicker.js') !!}"></script>
<script>
    $('.js-switch').each(function() {
        new Switchery($(this)[0], $(this).data());
    });
    $(".observer").persianDatepicker({
        initialValue: false,
        format: 'YYYY/MM/DD'
    });
    $("select").select2();
</script>