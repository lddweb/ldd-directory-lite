
    <form id="add_business_form" action="{{form_action}}" method="post">
        <input type="hidden" name="current_page" value="{{page}}" />

        <div class='lddbd_input_holder'>
            <label for='name'>Business Name</label>
            <input class='' type='text' id='lddbd_name' name='name'/>
        </div>

        <div class='lddbd_input_holder'>
            <label for='description'>Description</label>
            <textarea id='lddbd_description' name='description'></textarea>
        </div>

        <div class='lddbd_input_holder'>
            <label for='contact'>Contact Name</label>
            <input class='' type='text' id='lddbd_contact' name='contact'/>
        </div>

        <div class='lddbd_input_holder'>
            <label for='email'>Contact Email</label>
            <input class='' type='text' id='lddbd_email' name='email'/>
        </div>

        <div class='lddbd_input_holder'>
            <label for='address_country'>Country</label>
            <input type='text' id='lddbd_address_zip' name='address_zip' />
        </div>


        <div class='submit'>
            <input type="submit" id="ldd_add_business" class="button-primary" value="{{next}}" />
        </div>
    </form>
