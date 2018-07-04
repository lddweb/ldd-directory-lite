
    jQuery(document).ready(function(){
    jQuery(".placeholder_button").click(function(){
        tb_show('Upload a logo', 'media-upload.php?referer=wptuts-settings&type=image&TB_iframe=true&post_id=0',false);
        
        return false;
    })
    window.send_to_editor = function(html) {
       
        
		// html returns a link like this:
		// <a href="{server_uploaded_image_url}"><img src="{server_uploaded_image_url}" alt="" title="" width="" height"" class="alignzone size-full wp-image-125" /></a>
        var image_url = jQuery(html).attr('src');  
        
      

		//alert(html);
        jQuery('.appreance-text').val(image_url);
        jQuery('.ldd_pl_noimage').text("Image selected, please save changes.")
		tb_remove();
		
		// $('#uploaded_logo').val('uploaded');
		
	}
})
jQuery(document).ready(function(){
    jQuery(".placeholder_button_delete").click(function(){
        var loc = window.location.href;
        window.location.href = loc+"&ldd_act=del_ph";

    })
    })
