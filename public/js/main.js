jQuery(document).ready(function(){    

/*jQuery(function(){
    jQuery('.ldd_header_view').hover(
	function(){
		
            jQuery(this+ '.ldd_tooltip').show();
        },
        function(){
            jQuery(this+ '.ldd_tooltip').hide();   
        }
    )   
});
*/



    jQuery(".ldd_search .show_search").click(function(){

        if(jQuery(this).hasClass('fa-search-plus'))
        {
            jQuery(this).removeClass("fa-search-plus");
            jQuery(this).addClass("fa-search-minus");            

            jQuery(".ldd_main_search_box").css("display","block");
        }
        else
        {
            jQuery(this).removeClass("fa-search-minus");
            jQuery(this).addClass("fa-search-plus");

            jQuery(".ldd_main_search_box").css("display","none");
        }
    
    });

    jQuery(".ldd-dropdown-toggle").dropdown();


});



var heights = jQuery(".type-grid.grid-item").map(function ()
{
    return jQuery(this).height();
}).get(),

 maxHeight = Math.max.apply(null, heights);
 maxdiv = maxHeight+10;
//alert(maxHeight);

//jQuery(".js-isotope2 .grid-item").css("height",maxHeight+"px");
//Remove height attribute for 2 column
jQuery(".js-isotope2 .col-md-6").removeAttr("style");

jQuery(".masonry-cols3").mpmansory({
    childrenClass: 'type-grid', // default is a div
    columnClasses: 'padding', //add classes to items
    breakpoints: {
        lg: 4,
        md: 4,
        sm: 6,
        xs: 12
    },
    distributeBy: {
        order: false,
        height: false,
        attr: 'data-order',
        attrOrder: 'asc'
    },
    onload: function(items) {}
});

