jQuery(function($) {
    $("#submit-panels").responsiveSlides({
        auto:           false,
        pager:          true,
        nav:            true,
        navContainer:   '#submit-items',
        speed:          300,
        namespace:      'ldd-submit-listing',
    })
})


jQuery(document).ready(function($) {

    $("#country").change(function() {
        var newSubdivision = this.value
        var request = $.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                action: "dropdown_change",
                subdivision: newSubdivision,
            },
            beforeSend: function() {
                $(".submit-ajax-replace").hide();
            }
        })
        request.done(function( msg ) {
            var response = $.parseJSON( msg )
            $("#subdivision_control").html( response.input )
            $("#subdivision_label").text( response.sub )
            $("#post_code_label").text( response.code )
            $(".submit-ajax-replace").show();
        })
    })


    function getNavItem( currentItem ) {
        var tab_number = parseInt( currentItem.split("_s").pop() ) + 1
        var tab_class = currentItem.substring( 0, currentItem.length - 1 ) + tab_number
        return tab_class;
    }

    $('.row.hpt').hide()

    $('span.submit-error').closest('div').addClass('has-error has-feedback')

    // Append "submit-error" class to tabs
    $('li').has('span.submit-error').each(function( index ) {
        var tab_id = $(this).attr("id")
        var tab_number = parseInt( tab_id.split("_s").pop() ) + 1
        var tab_class = tab_id.substring( 0, tab_id.length - 1 ) + tab_number
        $( "." + tab_class).addClass("submit-error")
    })

    $('.form-control').focus(function(){
        if ( $(this).closest('div').hasClass('has-error') ) {

            var tab_id = $(this).closest('li').attr('id')
            var activeTab = getNavItem( tab_id )

            $("a." + activeTab).removeClass('submit-error')

            $(this).next('span').remove()
            $(this).closest('div').removeClass('has-error has-feedback')
            $(this).next('span').animate({height: 0, opacity: 0}, 'slow', function() {
                $(this).remove()
            })

        }
    })

    $('a.submit-error').click(function() {
        $(this).removeClass('submit-error')
    })



    var first_tab = $("#ldd-submit-listing1_s0")
    var last_tab  = $("#ldd-submit-listing1_s3")
    var prev_button = $(".ldd-submit-listing_nav.prev")
    var next_button = $(".ldd-submit-listing_nav.next")

    prev_button.hide();
    $('.ldd-submit-listing_nav').add('#submit-items a').click(function() {

        jQuery('html, body').animate({
            scrollTop: $('.l-breadcrumb').offset().top - topOffset
        }, 500)


            if ( first_tab.hasClass('ldd-submit-listing1_on') ) {
                prev_button.hide();
                next_button.show();
            } else if ( last_tab.hasClass('ldd-submit-listing1_on') ) {
                $('.submit-confirm:hidden').slideDown();
                prev_button.show();
                next_button.hide();
            } else {
                prev_button.show();
                next_button.show();
            }
        });

        if ( $("#submit-items li").hasClass('submit-error') ) {
            $('.submit-confirm:hidden').show();
        }

    // This doesn't make sense until the form is submitted via ajax!
    // Doesn't make it any less fun.
/*        var submitButton = jQuery('#submit-form-submit');

        submitButton.click(function () {
            jQuery('#submit-form-submit > i').addClass('fa-spin');
        });*/

    });
