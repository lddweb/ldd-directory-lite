jQuery(function() {
    var $info = jQuery("#modal-content");
    $info.dialog({
        'dialogClass'   : 'wp-dialog',
        'modal'         : true,
        'autoOpen'      : false,
        'closeOnEscape' : true,
        'buttons'       : {
            "Close": function() {
                jQuery(this).dialog('close');
            }
        }
    });
    jQuery(".page_item a").click(function(e) {
        e.preventDefault();
        var $str = jQuery(this).parent().attr("class");
        var $id = $str.substring( $str.indexOf( "item-" ) + 5 );
        jQuery("#directory_page").val( $id );
        $info.dialog('close');
    })
    jQuery("#open-modal").click(function(event) {
        event.preventDefault();
        $info.dialog('open');
    });
});