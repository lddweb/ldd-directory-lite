<form class="section-wrapper directory-search cf">
    <input type="text" placeholder="Search here..." required />
    <button type="submit">Search</button>
</form>

<div class="section-wrapper cf">

    <div class="directory-nav cf">
        <a href="" class="button left view-all">Expand All</a>
        <a href="{{url}}?submit=true" class="button add right">Submit Listing</a>
    </div>

    <div class="directory-cat cf">
        <ul class="top-level">
            {{categories}}
        </ul>
    </div>

</div>


<script>
    jQuery(document).ready( function() {
        var $all = jQuery('a.view-all');
        var $children = jQuery('ul.children');

        $all.show();
        $children.hide();

        $all.click( function(e) {
            e.preventDefault();

            $children.slideToggle();
            if ( 'Expand All' == $all.text() ) {
                $all.text('Collapse All');
            } else {
                $all.text('Expand All');
            }

        });

    });
</script>