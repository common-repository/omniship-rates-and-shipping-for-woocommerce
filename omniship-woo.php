<?php
/*
Plugin Name: OmniShip Rates and Shipping for WooCommerce
Plugin URI: https://transport-logic.com
Description: OmniShip dynamic rates and shipping labels directly from WooCommerce.
Version: 1.1.9
Author: TSchwartz
Author URI: https://www.linkedin.com/in/tonyschwartz/
License: GPLv2
*/

if (!defined('OmniShip_plugin_path'))
    define('OmniShip_plugin_path', plugin_dir_path(__FILE__));

include OmniShip_plugin_path . 'templates/includeCheckout.php';

// Order Edit Includes

include OmniShip_plugin_path . 'templates/includeBillingAddressArea.php';
include OmniShip_plugin_path . 'templates/includeShippingAddressArea.php';
include OmniShip_plugin_path . 'templates/settingsPage.php';

?>