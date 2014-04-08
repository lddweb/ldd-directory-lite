
    <form id="add_business_form" action="{{form_action}}" method="post">
        <input type="hidden" name="current_page" value="{{page}}" />


        <div class="lddbd_input_holder">
            <label for="address_street">Street</label>
            <input type="text" id="lddbd_address_street" name="address_street">
        </div>

        <div class="lddbd_input_holder">
            <label for="name">City / Town:</label>
            <input type="text" id="lddbd_address_city" name="address_city" /><br />
        </div>

        <div class="lddbd_input_holder">
            <label for="name">State:</label>
            <input type="text" id="lddbd_address_state" name="address_city" /><br />
        </div>

        <div class="lddbd_input_holder">
            <label for="name">ZIP Code:</label>
            <input type="text" id="lddbd_address_zip" name="address_zip" />
        </div>


        <div class="submit">
            <input type="submit" name="goback" value="{{back}}" />
            <input type="submit" id="ldd_add_business" class="button-primary" value="{{next}}" />
        </div>
    </form>
