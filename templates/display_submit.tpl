
    <form id='add_business_form' action='{{form_action}}' method='POST' enctype='multipart/form-data' target='lddbd_submission_target'>
        <div class='lddbd_input_holder'>
            <label for='name'>Business Name</label>
            <input class='' type='text' id='lddbd_name' name='name'/>
        </div>

        <div class='lddbd_input_holder'>
            <label for='description'>Description</label>
            <textarea id='lddbd_description' name='description'></textarea>
        </div>

        <div class='lddbd_input_holder'>
            <label for='address_street'>Street</label>
            <input type='text' id='lddbd_address_street' name='address_street'>
        </div>

        <div class='lddbd_input_holder'>
            <label for='name'>City / Town:</label>
            <input type='text' id='lddbd_address_city' name='address_city' /><br />
        </div>

        <div class='lddbd_input_holder'>
            <label for='name'>State:</label>
            <input type='text' id='lddbd_address_state' name='address_city' /><br />
        </div>

        <div class='lddbd_input_holder'>
            <label for='name'>ZIP Code:</label>
            <input type='text' id='lddbd_address_zip' name='address_zip' />
        </div>

        <div class='lddbd_input_holder'>
            <label for='address_country'>Country</label>
            <input type='text' id='lddbd_address_zip' name='address_zip' />
        </div>

        <div class='lddbd_input_holder'>
            <label for='phone'>Contact Phone</label>
            <input class='' type='text' id='lddbd_phone' name='phone'/>
        </div>

        <div class='lddbd_input_holder'>
            <label for='fax'>Contact Fax</label>
            <input type='text' id='lddbd_fax' name='fax'>
        </div>

        <div class='lddbd_input_holder'>
            <label for='email'>Contact Email</label>
            <input class='' type='text' id='lddbd_email' name='email'/>
        </div>

        <div class='lddbd_input_holder'>
            <label for='contact'>Contact Name</label>
            <input class='' type='text' id='lddbd_contact' name='contact'/>
        </div>

        <div class='lddbd_input_holder'>
            <label for='url'>Website</label>
            <input type='text' id='lddbd_url' name='url'/>
        </div>

        <div class='lddbd_input_holder'>
            <label for='facebook'>Facebook Page</label>
            <input type='text' id='lddbd_facebook' name='facebook'/>
        </div>

        <div class='lddbd_input_holder'>
            <label for='twitter'>Twitter Handle</label>
            <input type='text' id='lddbd_twitter' name='twitter'/>
        </div>

        <div class='lddbd_input_holder'>
            <label for='linkedin'>Linked In Profile</label>
            <input type='text' id='lddbd_linkedin' name='linkedin'/>
        </div>



        <div class='lddbd_input_holder'>
            <label for='logo'>Logo Image</label>
            <input class='' type='file' id='lddbd_logo' name='logo'/>
        </div>

        {{display_categories}}

        <div class='lddbd_input_holder'>
            <label for='login'>Login</label>
            <input class='' type='text' id='lddbd_login' name='login'/>
        </div>

        <div class='lddbd_input_holder'>
            <label for='password'>Password</label>
            <input class='' type='text' id='lddbd_password' name='password'/>
        </div>

        <input type='hidden' id='lddbd_action' name='action' value='add'/>

        <div class='submit'>
            <input id='lddbd_cancel_listing' type='button' class='button-primary' value='Cancel' />
            <input type='submit' id='ldd_add_business' class='button-primary' value='Submit Listing' />
        </div>
    </form>
