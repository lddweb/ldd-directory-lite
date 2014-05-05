<form class="directory-search cf">
    <input type="hidden" name="action" value="search_directory">
    <input id="search-directory-input" name="s" type="text" placeholder="Search the directory..." required />
    <button type="submit">Search</button>
</form>

<script>
    jQuery(document).ready(function() {
        jQuery('input#search-directory-input').searchbox({
            url: '{{ajaxurl}}',
            param: 'q',
            dom_id: '#search-directory-results',
            delay: 250,
            loading_css: '#search-loading'
        });
    });
</script>