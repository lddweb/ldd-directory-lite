<form action="{{form_action}}" method="post">
    <input type="hidden" name="current_page" value="{{page}}" />

    <div class="lddbd_input_holder">
        <label for="ldd[street]">Street</label>
        <input id="street" type="text" name="ldd[street]" value="{{street}}" />
    </div>

    <div class="lddbd_input_holder">
        <label for="ldd[city]">City / Town:</label>
        <input id="city" type="text" name="ldd[city]" value="{{city}}" />
    </div>

    <div class="lddbd_input_holder">
        <label for="ldd[subdivision]">State:</label>
        {{subdivision_dropdown}}
    </div>

    <div class="lddbd_input_holder">
        <label for="ldd[zip">Zip/Postal:</label>
        <input id="zip" type="text" name="ldd[zip]" value="{{zip}}" />
    </div>


    <input type="submit" name="goback" value="{{back}}" />
    <input type="submit" id="ldd_add_business" class="button-primary" value="{{next}}" />
</form>
