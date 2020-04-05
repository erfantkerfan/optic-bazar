$(document).ready(function() {

    $(function() {
        $(".preloader").fadeOut();
    });

    $(".zm_slider").owlCarousel({

        pagination : false,
        navigation : true, // Show next and prev buttons
        slideSpeed : 300,
        autoPlay : true,
        paginationSpeed : 400,
        items : 1,
        navigationText: [
            "<span class='spriteleft'><i class='fa fa-angle-left'></i></span>",
            "<span class='spriteright'><i class='fa fa-angle-right'></i></span>"
        ]

    });

    $(".zm_slider_items").owlCarousel({

        pagination : false,
        navigation : true, // Show next and prev buttons
        slideSpeed : 400,
        autoPlay : true,
        paginationSpeed : 400,
        items : 1,
        navigationText: [
            "<span class='spriteleft'><i class='fa fa-angle-left'></i></span>",
            "<span class='spriteright'><i class='fa fa-angle-right'></i></span>"
        ]

    });

    $(".zm_slider_product").owlCarousel({

        pagination : false,
        navigation : true, // Show next and prev buttons
        slideSpeed : 400,
        autoPlay : true,
        paginationSpeed : 400,
        items : 5,
        navigationText: [
            "<span class='spriteleft'><i class='fa fa-angle-left'></i></span>",
            "<span class='spriteright'><i class='fa fa-angle-right'></i></span>"
        ]

    });


    $('input.zm_comma_number').keyup(function(event) {
        // skip for arrow keys
        if(event.which >= 37 && event.which <= 40) return;
        // format number
        $(this).val(function(index, value) {
            return value
                .replace(/\D/g, "");
        });
    });



    $(".inputs").keyup(function () {
        if (this.value.length == this.maxLength) {
            var $next = $(this).attr('formcontrolname');
            if ($next !== "Submit"){
                $('.' + $next).focus();
            }
            else {
                $(this).blur();
                $('#verification_submit').submit();
            }
        }
    });

    $('.tab_brand_controll ul li a').click(function (e) {
        e.preventDefault();
        $('.tab_brand').removeClass('active');
        $('.tab_brand_controll ul li').removeClass('active');
        $(this).parent().addClass('active');

        var linkid = $(this).attr('href');
        $(linkid).addClass('active');
    });

});
