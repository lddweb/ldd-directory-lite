<div class="search-section">
    <form class="directory-search cf">
        <input type="hidden" name="action" value="search_directory">
        <input id="search-directory-input" name="s" type="text" placeholder="{$placeholder}" required />
        <button type="submit">{$search_text}</button>
    </form>

    <div id="search-loading"></div>
    <div id="search-directory-results"></div>
</div>

<script>
    jQuery(document).ready(function() {
        jQuery('input#search-directory-input').searchbox({
            url: '{$ajaxurl}',
            dom_id: '#search-directory-results',
            delay: 250,
            loading_css: '#search-loading'
        });
    });
</script>