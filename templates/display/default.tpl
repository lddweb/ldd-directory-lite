
<form class="directory-search cf">
    <input type="text" placeholder="Search here..." required />
    <button type="submit">Search</button>
</form>

<div id="lddbd_business_directory">
    <div id="lddbd_business_directory_head">


        <div id="lddbd_navigation_holder">
            <a href="javascript:void(0);" id="lddbd_listings_category_button" class="lddbd_navigation_button">Categories</a>
            <a href="javascript:void(0);" id="lddbd_all_listings_button" class="lddbd_navigation_button">All Listings</a>
            <a href="{{url}}?submit=true" id="lddbd_add_business_button" class="lddbd_navigation_button">Submit Listing</a>
        </div>
    </div>

    <div id="lddbd_business_directory_body">
        <div id="lddbd_business_directory_list">
            {{body}}
        </div>
    </div>
</div>

