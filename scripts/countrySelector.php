<script type="text/javascript">
function formElemCountry() {
	var selectedCountry = jQuery("#lddbd_address_country").val();
	
	if( selectedCountry == "United States" ) {
		jQuery("#selectedCountryForm").html(
			"<div class='lddbd_input_holder'>" +
			"<label for='name'>City / Town:</label> <input type='text' id='lddbd_address_city' name='address_city' /><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'>State:</label>" +
			"<select id='lddbd_address_state' name='address_state'>" +
		// Start the loop that generates the list of US states
			<?php $statesUSA_TextFile = plugin_dir_path( __FILE__ ) . 'states_USA.txt'; ?>
			<?php $statesUSA_List = fopen( $statesUSA_TextFile, 'r' ); ?>
			<?php while( !feof ( $statesUSA_List ) ) { ?>
			<?php $textLine = fgets( $statesUSA_List ); $textLine = trim( $textLine ); ?>
				"<option><?php echo $textLine; ?></option>" +
			<?php } ?>
			<?php fclose( $statesUSA_List ); ?>
		// End the loop that generates the list of US states
			"</select><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'>ZIP Code:</label> <input type='text' id='lddbd_address_zip' name='address_zip' />" +
			"</div>"
		);
	} else if ( selectedCountry == "Australia" ) {
		jQuery("#selectedCountryForm").html(
			"<div class='lddbd_input_holder'>" +
			"<label for='name'>City / Town:</label> <input type='text' id='lddbd_address_city' name='address_city' /><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'>State / Territory:</label>" +
			"<select id='lddbd_address_state' name='address_state'>" +
		// Start the loop that generates the list of Australian states/territories
			<?php $statesAUS_TextFile = plugin_dir_path( __FILE__ ) . 'states_AUS.txt'; ?>
			<?php $statesAUS_List = fopen( $statesAUS_TextFile, 'r' ); ?>
			<?php while( !feof ( $statesAUS_List ) ) { ?>
			<?php $textLine = fgets( $statesAUS_List ); $textLine = trim( $textLine ); ?>
				"<option><?php echo $textLine; ?></option>" +
			<?php } ?>
			<?php fclose( $statesAUS_List ); ?>
		// End the loop that generates the list of Australian states/territories
			"</select><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'>Postcode:</label> <input type='text' id='lddbd_address_zip' name='address_zip' />" +
			"</div>"
		);
	} else if ( selectedCountry == "Austria" || selectedCountry == "Belgium" || selectedCountry == "France" || selectedCountry == "Spain" || selectedCountry == "Germany" || selectedCountry == "Iceland" || selectedCountry == "Netherlands" || selectedCountry == "Norway" || selectedCountry == "Portugal" || selectedCountry == "Sweden" || selectedCountry == "Switzerland" ) {
		jQuery("#selectedCountryForm").html(
			"<div class='lddbd_input_holder'>" +
			"<label for='name'>Postcode:</label> <input type='text' id='lddbd_address_zip' name='address_zip' /><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'>City / Town:</label> <input type='text' id='lddbd_address_city' name='address_city' />" +
			"</div>"
		);
	} else if ( selectedCountry == "Canada" ) {
		jQuery("#selectedCountryForm").html(
			"<div class='lddbd_input_holder'>" +
			"<label for='name'>City / Town:</label> <input type='text' id='lddbd_address_city' name='address_city' /><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'>Province / Territory:</label>" +
			"<select id='lddbd_address_state' name='address_state'>" +
		// Start the loop that generates the list of Canadian provinces/territories
			<?php $statesCAN_TextFile = plugin_dir_path( __FILE__ ) . 'states_CAN.txt'; ?>
			<?php $statesCAN_List = fopen( $statesCAN_TextFile, 'r' ); ?>
			<?php while( !feof ( $statesCAN_List ) ) { ?>
			<?php $textLine = fgets( $statesCAN_List ); $textLine = trim( $textLine ); ?>
				"<option><?php echo $textLine; ?></option>" +
			<?php } ?>
			<?php fclose( $statesCAN_List ); ?>
		// End the loop that generates the list of Canadian provinces/territories
			"</select><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'>Postal Code:</label> <input type='text' id='lddbd_address_zip' name='address_zip' />" +
			"</div>"
		);
	} else if ( selectedCountry == "Denmark" ) {
		jQuery("#selectedCountryForm").html(
			"<div class='lddbd_input_holder'>" +
			"<label for='name'>Postal Code and District:</label> <input type='text' id='lddbd_address_zip' name='address_zip' /><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'>City / Town / Village:</label> <input type='text' id='lddbd_address_city' name='address_city' />" +
			"</div>"
		);
	} else if ( selectedCountry == "Finland" ) {
		jQuery("#selectedCountryForm").html(
			"<div class='lddbd_input_holder'>" +
			"<label for='name'>Postal Code and District:</label> <input type='text' id='lddbd_address_zip' name='address_zip' />" +
			"</div>"
		);
	} else if ( selectedCountry == "Ireland" ) {
		jQuery("#selectedCountryForm").html(
			"<div class='lddbd_input_holder'>" +
			"<label for='name'>City / Town:</label> <input type='text' id='lddbd_address_city' name='address_city' /><br />" +
			"</div>"
		);
	} else if ( selectedCountry == "Italy" ) {
		jQuery("#selectedCountryForm").html(
			"<div class='lddbd_input_holder'>" +
			"<label for='name'>Postcode:</label> <input type='text' id='lddbd_address_zip' name='address_zip' /><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'>City / Town:</label> <input type='text' id='lddbd_address_city' name='address_city' /><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'>Province:</label> <input type='text' id='lddbd_address_state' name='address_state' />" +
			"</div>"
		);
	} else if ( selectedCountry == "Malaysia" ) {
		jQuery("#selectedCountryForm").html(
			"<div class='lddbd_input_holder'>" +
			"<label for='name'>City / Town:</label> <input type='text' id='lddbd_address_city' name='address_city' /><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'>State / Province:</label>" +
			"<select id='lddbd_address_state' name='address_state'>" +
		// Start the loop that generates the list of Malaysian states
			<?php $statesMAL_TextFile = plugin_dir_path( __FILE__ ) . 'states_MAL.txt'; ?>
			<?php $statesMAL_List = fopen( $statesMAL_TextFile, 'r' ); ?>
			<?php while( !feof ( $statesMAL_List ) ) { ?>
			<?php $textLine = fgets( $statesMAL_List ); $textLine = trim( $textLine ); ?>
				"<option><?php echo $textLine; ?></option>" +
			<?php } ?>
			<?php fclose( $statesMAL_List ); ?>
		// End the loop that generates the list of Malaysian states
			"</select><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'>Postal Code:</label> <input type='text' id='lddbd_address_zip' name='address_zip' />" +
			"</div>"
		);
	} else if ( selectedCountry == "Mexico" ) {
		jQuery("#selectedCountryForm").html(
			"<div class='lddbd_input_holder'>" +
			"<label for='name'>Postal Code:</label> <input type='text' id='lddbd_address_zip' name='address_zip' /><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'>City / Town:</label> <input type='text' id='lddbd_address_city' name='address_city' /><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'>Province:</label> <input type='text' id='lddbd_address_state' name='address_state' />" +
			"</div>"
		);
	} else if ( selectedCountry == "United Kingdom" ) {
		jQuery("#selectedCountryForm").html(
			"<div class='lddbd_input_holder'>" +
			"<label for='name'>City / Town:</label> <input type='text' id='lddbd_address_city' name='address_city' /><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'>Postcode:</label> <input type='text' id='lddbd_address_zip' name='address_zip' />" +
			"</div>"
		);
	} else {
		jQuery("#selectedCountryForm").html( "" );
	}
}
jQuery("#lddbd_address_country").change(formElemCountry);
formElemCountry();
</script>