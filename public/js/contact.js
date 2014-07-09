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
            console.log(response)
            if (1 == response.success) {
                $('#contact-form-wrap').fadeOut('fast');
                $("#contact-messages").append('<div class="alert alert-success" role="alert">' + response.msg + '</div>').hide().delay(200).fadeIn('fast').delay(6000).fadeOut('slow');
            } else {
                $("#contact-messages").append('<div class="alert alert-danger" role="alert">' + response.msg + '</div>').delay(3000).fadeOut('slow');
            }
        })

    })

})