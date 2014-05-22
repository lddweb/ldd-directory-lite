<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>Nothing Found!</strong> We couldn't find any results for the search term you entered. Please try another search.
</div>

<script>
    jQuery(document).ready(function($) {
        nothingFound = $(".alert button")
        nothingFound.click(function() {
            $("input#directory-search").val("")
            $("input#directory-search").focus()
            $("#search-loading").hide()
            $(".directory-content").show()
        })
    })
</script>