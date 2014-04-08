
    <form id="add_business_form" action="{{form_action}}" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="current_page" value="{{page}}" />


        <div class="lddbd_input_holder">
            <label for="logo">Logo Image</label>
            <input class="" type="file" id="lddbd_logo" name="logo"/>
        </div>


        <div class="submit">
            <input type="submit" name="goback" value="{{back}}" />
            <input type="submit" id="ldd_add_business" class="button-primary" value="{{submit}}" />
        </div>
    </form>
