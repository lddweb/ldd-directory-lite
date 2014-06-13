jQuery(document).ready(function($){
    $('#appearance_panel_background').wpColorPicker();
    $('#appearance_panel_foreground').wpColorPicker();

    var uninstallCheck = $("input[id=lite-debug_uninstall]");
    var warningStack = $("p.warning");
    uninstallCheck.change( function() {
        var closestTD = $(this).closest('td');
        if ( uninstallCheck.is(":checked") ) {
            closestTD.css( 'background-color', '#DA4453' )
            warningStack.css({
                'color': '#fff',
                'font-weight': '700'
            })
        } else {
            closestTD.css( 'background-color', 'transparent' )
            warningStack.css({
                'color': 'inherit',
                'font-weight': 'inherit'
            })
        }
    })
});