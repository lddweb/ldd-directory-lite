<style>
    .entry .business-directory ul li:before {
        content: '';
    }
    html>body .entry .business-directory ul {
        text-indent: 0;
    }
</style>

<section class="business-directory directory-home cf">

    <nav class="lite-nav above-header center cf">
        <ul>
            <li><a href="{$url}?show=submit&t=listing">Submit Listing</a></li>
        </ul>
    </nav>

    {$search_form}

    <div class="directory-wrap">
        <nav class="lite-nav below-header center cf" style="display:none;">
            <ul class="top-level">
                <li class="cat-item cat-item-2"><a href="http://local.wordpress.dev/dir/?show=category&t=arts-entertainment" title="View all posts filed under Arts &amp; Entertainment">Arts &amp; Entertainment</a></li>
                <li class="cat-item cat-item-5"><a href="http://local.wordpress.dev/dir/?show=category&t=computers-electronics" title="View all posts filed under Computers &amp; Electronics">Computers &amp; Electronics</a></li>
                <li class="cat-item cat-item-9"><a href="http://local.wordpress.dev/dir/?show=category&t=food-dining" title="View all posts filed under Food &amp; Dining">Food &amp; Dining</a></li>
                <li class="cat-item cat-item-22"><a href="http://local.wordpress.dev/dir/?show=category&t=general" title="View all posts filed under General">General</a></li>
                <li class="cat-item cat-item-13"><a href="http://local.wordpress.dev/dir/?show=category&t=home-garden" title="View all posts filed under Home &amp; Garden">Home &amp; Garden</a></li>
                <li class="cat-item cat-item-23"><a href="http://local.wordpress.dev/dir/?show=category&t=miscellaneous" title="View all posts filed under Miscellaneous">Miscellaneous</a></li>
                <li class="cat-item cat-item-21"><a href="http://local.wordpress.dev/dir/?show=category&t=test-category" title="View all posts filed under Test Category">Test Category</a></li>
            </ul>
        </nav>


        <section class="directory-content">

            <ul class="category-tree category-home">
                {$categories}
            </ul>

        </section>


        {$featured_listings_open}
        {$featured_listings}
        {$featured_listings_close}

    </div>


</section>



<script>
    // This is all part of the mobile menu... that we may not even use.
    var lite_breakpoint = 640;
    var shrunk = false;

    jQuery(document).ready(function() {
        if ( jQuery(window).width() < lite_breakpoint  && jQuery(".lite-nav.below-header .current").length == 0 ) {
            jQuery('.lite-nav.below-header li:first-child').addClass('current');
        }
    });

    jQuery(window).resize(function() {
        if ( jQuery(window).width() < lite_breakpoint  && jQuery(".lite-nav.below-header .current").length == 0 ) {
            shrunk = true;
            jQuery('.lite-nav.below-header li:first-child').addClass('current');
        }
        if ( jQuery(window).width() > lite_breakpoint && shrunk ) {
            shrunk = false;
            jQuery('.lite-nav.below-header li').removeClass('current');
        }
    });
</script>
