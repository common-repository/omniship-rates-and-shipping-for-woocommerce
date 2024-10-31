<?php
// create custom plugin settings menu
add_action('admin_menu', 'omniship_plugin_create_menu');

function omniship_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('OmniShip Settings', 'OmniShip Settings', 'administrator', __FILE__, 'omniship_plugin_settings_page' , plugins_url('images/omnishipLogo.png', __FILE__) );

	//call register settings function
	add_action( 'admin_init', 'register_omniship_plugin_settings' );
}


function register_omniship_plugin_settings() {
	//register our settings
	register_setting( 'omniship-plugin-settings-group', 'OmniShipClientID' );
	register_setting( 'omniship-plugin-settings-group', 'isProduction' );
    register_setting( 'omniship-plugin-settings-group', 'company' );
    register_setting( 'omniship-plugin-settings-group', 'state' );
    register_setting( 'omniship-plugin-settings-group', 'country' );    
	register_setting( 'omniship-plugin-settings-group', 'zipcode' );
    register_setting( 'omniship-plugin-settings-group', 'shippingType' );
    register_setting( 'omniship-plugin-settings-group', 'accessKey' );
    register_setting( 'omniship-plugin-settings-group', 'labelWidth' );
    register_setting( 'omniship-plugin-settings-group', 'liveRates' );
    register_setting( 'omniship-plugin-settings-group', 'wooAPIKey' );
}

function omniship_plugin_settings_page() {
?>
<div style="margin-bottom:30px;">
	<p>OmniShip by <a href="https://www.transport-logic.com">Transport Logic</a></p>	
	<p>Questions: <a href="mailto:info@transport-logic.com">info@transport-logic.com</a></p>
</div> 
<div class="wrap">
<h1>OmniShip Plugin Settings Page</h1>

	
<form method="post" action="options.php">
    <?php settings_fields( 'omniship-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'omniship-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">OmniShipClientID</th>
        <td><input type="text" name="OmniShipClientID" value="<?php echo esc_attr( get_option('OmniShipClientID') ); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Access Key</th>
        <td><input type="text" name="accessKey" style="width:535px;" value="<?php echo esc_attr( get_option('accessKey') ); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">isProduction (true/false)</th>
        <td><input type="text" name="isProduction" value="<?php echo esc_attr( get_option('isProduction') ); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Company Name</th>
        <td><input type="text" name="company" value="<?php echo esc_attr( get_option('company') ); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">State (2 Character)</th>
        <td><input type="text" name="state" value="<?php echo esc_attr( get_option('state') ); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Country (3 Character) - USA</th>
        <td><input type="text" name="country" value="<?php echo esc_attr( get_option('country') ); ?>" /></td>
        </tr>
		<tr valign="top">
        <th scope="row">Zip Code</th>
        <td><input type="text" name="zipcode" value="<?php echo esc_attr( get_option('zipcode') ); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Shipping Type</th>
        <td><input type="text" name="shippingType" value="<?php echo esc_attr( get_option('shippingType') ); ?>" /></td>
        </tr>    
        <tr valign="top">
        <th scope="row">Label Width (450px)</th>
        <td><input type="text" name="labelWidth" value="<?php echo esc_attr( get_option('labelWidth') ); ?>" /></td>
        </tr>
        <th scope="row">Live Rates Displayed</th>
        <td><input type="checkbox" name="liveRates" <?php if (esc_attr(get_option('liveRates'))) { echo 'checked';} ?> /></td>
        </tr>
        <th scope="row">WooCommerce API Key</th>
        <td><input type="text" name="wooAPIKey" style="width:535px;"  value="<?php echo esc_attr( get_option('wooAPIKey') ); ?>" /></td>
        </tr>
       
   
    <?php submit_button(); ?>

</form>
</div>
<?php } ?>