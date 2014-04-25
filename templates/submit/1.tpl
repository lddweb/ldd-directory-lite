

    <form id="add_business_form" action="{{form_action}}" method="post">
        <input type="hidden" name="current_page" value="{{page}}" />

        <div class="lddbd_input_holder">
            <label for="name">Business Name</label>
            <input id="name" type="text" name="ldd[name]" value="{{name}}" />
        </div>

        <div class="lddbd_input_holder">
            <label for="description">Description</label>
            <textarea id="desc" name="ldd[desc]">{{desc}}</textarea>
        </div>

        <div class="lddbd_input_holder">
            <label for="contact">Contact Name</label>
            <input id="contact" type="text" name="ldd[contact]" value="{{contact}}" />
        </div>


        <div class="lddbd_input_holder">
            <label for="email">Contact Email</label>
            <input id="email" type="text" name="ldd[email]" value="{{email}}" />
        </div>

        <div class="lddbd_input_holder">
            <label for="address_country">Country</label>
            {{country_dropdown}}
        </div>

        <input type="submit" id="ldd_add_business" class="button-primary" value="{{next}}" />
    </form>

