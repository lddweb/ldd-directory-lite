jQuery(document).ready(function(){    


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

