(function() {
    tinymce.PluginManager.add('mybutton', function( editor, url ) {
        editor.addButton( 'mybutton', {
            text: tinyMCE_object.button_name,
            icon: false,
			title:"Email Shortcodes",
            onclick: function() {
                editor.windowManager.open( {
                    title: tinyMCE_object.button_title,
                    body: [
                        
                       
                        {
                            type   : 'listbox',
                            name   : 'listbox',
                            label  : 'Select Short Code',
                            values : [
                                { text: 'Select', value: '' },
                                { text: 'Site Title', value: 'site_title' },
                                { text: 'Site URL', value: 'site_link' },

                                { text: '-------------'},
                                { text: 'Directory URL', value: 'diectory_link' },
                                { text: 'Listing Title', value: 'title' },
                                { text: 'Listing URL', value: 'link' },
                                { text: 'Listing Description', value: 'description' },
                                { text: 'Listing Category', value: 'listing_category' },
                                { text: 'Approve Link', value: 'approve_link' },
                                { text: 'Listing Aurthor', value: 'author' },
                                { text: 'Author Email', value: 'author_email' },
                                
                                { text: 'Contact Name', value: 'contact_name' },
                                { text: 'Contact Email', value: 'contact_email' },
                                { text: '-------------', value: '' },
                                { text: 'All Fields', value: 'all_fields' }
								
                            ],
                            value : 'shortcode' // Sets the default
                        },
                       

                    ],
                    onsubmit: function( e ) {
                        if(!e.data.listbox){ alert("please select a value"); exit;}
                        editor.insertContent( '{'+e.data.listbox+'}');
                    }
                });
            },
        });
    });
 
})();