
    <form id="add_business_form" action="{{form_action}}" method="post">
        <input type="hidden" name="current_page" value="{{page}}" />


        <div class="lddbd_input_holder">
            <label for="phone">Contact Phone</label>
            <input class="" type="text" id="lddbd_phone" name="phone"/>
        </div>

        <div class="lddbd_input_holder">
            <label for="fax">Contact Fax</label>
            <input type="text" id="lddbd_fax" name="fax">
        </div>

        <div class="lddbd_input_holder">
            <label for="url">Website</label>
            <input type="text" id="lddbd_url" name="url"/>
        </div>

        <div class="lddbd_input_holder">
            <label for="facebook">Facebook Page</label>
            <input type="text" id="lddbd_facebook" name="facebook"/>
        </div>

        <div class="lddbd_input_holder">
            <label for="twitter">Twitter Handle</label>
            <input type="text" id="lddbd_twitter" name="twitter"/>
        </div>

        <div class="lddbd_input_holder">
            <label for="linkedin">Linked In Profile</label>
            <input type="text" id="lddbd_linkedin" name="linkedin"/>
        </div>


        <div class="submit">
            <input type="submit" name="goback" value="{{back}}" />
            <input type="submit" id="ldd_add_business" class="button-primary" value="{{next}}" />
        </div>
    </form>
