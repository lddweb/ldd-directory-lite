jQuery(function($) {

    $("#contact-form").submit(function(e) {
        e.preventDefault()
        var params = $('#contact-form').serialize()

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: params,
            dataType: 'json',
        }).done(function(response){

            var errmsg = $("#message-error")
            var successmsg = $("#message-success")

            if (1 == response.success) {
                $('#contact-form-wrap').fadeOut('fast');
                successmsg.html(response.msg).delay(200).fadeIn('fast').delay(6000).fadeOut('slow');
            }/* else {
                errmsg.html(response.msg).show().delay(6000).fadeOut('slow');
            }*/
        })

    })

})