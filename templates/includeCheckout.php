<?php
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))))
{

function omniship_shipping_method_init()
{
    if (!class_exists('WC_OmniShip_Shipping_Method'))
    {
        class WC_OmniShip_Shipping_Method extends WC_Shipping_Method
        {
            /**
             * Constructor for your shipping class
             *
             * @access public
             * @return void
             */
            public function __construct()
            {
                $this->id = 'omniship_shipping_method'; // Id for your shipping method. Should be uunique.
                $this->method_title = __('OmniShip'); // Title shown in admin
                $this->method_description = __('OmniShip'); // Description shown in admin
                $this->enabled = "yes"; // This can be added as an setting but for this example its forced enabled
                $this->title = "OmniShip"; // This can be added as an setting but for this example its forced.
                $this->init();
            }

            /**
             * Init your settings
             *
             * @access public
             * @return void
             */
            function init()
            {
                // Load the settings API
                $this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
                $this->init_settings(); // This is part of the settings API. Loads settings you previously init.
                // Save settings in admin if you have any defined
                add_action('woocommerce_update_options_shipping_' . $this->id, array(
                    $this,
                    'process_admin_options'
                ));
            }

            /**
             * calculate_shipping function.
             *
             * @access public
             * @param mixed $package
             * @return void
             */
            public function calculate_shipping($package = Array())
            {
                global $woocommerce;
                $items = $woocommerce
                    ->cart
                    ->get_cart();
                $totalPrice = 0;
                $totalWeight = 0;
                foreach ($items as $item => $values)
                {
                    $_product = wc_get_product($values['data']->get_id());
                    $price = get_post_meta($values['product_id'], '_price', true);
                    $totalPrice = $totalPrice + ($values['quantity'] * $price);
                    if ($_product->has_weight())
                    {
                        $totalWeight = $totalWeight + ($_product->get_weight() * $values['quantity']);
                    }
                }
                $dest = $package['destination'];

                $customer = $package['user'];
                $customerid = $customer['ID'];
                $firstName = get_user_meta($customerid, 'shipping_first_name', true);
                $lastName = get_user_meta($customerid, 'shipping_last_name', true);
                $phone = get_user_meta($customerid, 'billing_phone_number', true);

                $city=$dest['city'];
                $address=$dest['address'];
                $postcode=$dest['postcode'];
                $state=$dest['state'];
                $country=$dest["country"];

                if (strlen($postcode)<5){
                    $city=get_option('city');
                    $address=get_option( 'woocommerce_store_address' );
                    $postcode=get_option('zipcode');
                    $state=get_option('state');
                    $country=get_option('country');
                }

                $Origin = array(
                    'Name' => get_option('company'),
                    'Street' => get_option( 'woocommerce_store_address' ),
                    'City' => get_option( 'woocommerce_store_city' ),
                    'State' =>  get_option('state'),
                    'Zip' => get_option('zipcode'),
                    'Country' => get_option('country'),
                );
                $Destination = array(
                    'Name' => sanitize_text_field($firstName) . ' ' . sanitize_text_field($lastName),
                    'Street' => sanitize_text_field($address),
                    'City' => sanitize_text_field($city),
                    'State' => sanitize_text_field($state),
                    'Zip' => sanitize_text_field($postcode),
                    'Country' => sanitize_text_field($country),
                );
                $Items = array(
                    'Length' => 16,
                    'Width' => 16,
                    'Height' => 16,
                    'Weight' => sanitize_text_field($totalWeight),
                    'PieceCount' => 1,
                    'Description' => 'Merchandise',
                );
    
                $body = array(
                    'OmniShipCustomerID' => get_option('OmniShipClientID'),
                    'isProduction' => get_option('isProduction'),
                    'ValueOfGoods' => sanitize_text_field($totalPrice),
                    'ShippingType' => get_option('shippingType'),
                    'ContactName' => sanitize_text_field($firstName) . ' ' . sanitize_text_field($lastName),
                    'CacheClear' => 'False',
                    'PhoneNumber' =>sanitize_text_field(sanitize_text_field($phone)),
                    'Origin' => $Origin,
                    'Destination' => $Destination,
                    'Items' => array(
                        $Items
                    ),
                );

                $url = "https://omniship.transport-logic.com/rate";

                $headers = array(
                    'Authorization' => 'Basic U3RyM0FQSTpTdHIzQVBJOldUSExPNFQ0UEYyNklERDE=',
                    'Content-Type' => 'application/json',
                    'accessKey' => get_option('accessKey')
                );
               
                $args = array(
                    'headers' => $headers,
                    'body'        => json_encode($body),
                    'method'      => 'POST',
                    'data_format' => 'body',
                );
                
                $response= wp_remote_post( $url, $args );
                $resp = json_decode($response["body"], true);
                $options = $resp['options'];

                $metad = array(
                    'OmniShipQuoteID' => sanitize_text_field($resp["quote"]),
                );
             
                foreach ($options as $val)
                {
                    $rate = array(
                        'id' => sanitize_text_field($val["ServiceCode"]),
                        'label' => sanitize_text_field($val["ServiceName"]),
                        'cost' => sanitize_text_field($val["fee"]),
                        'meta_data' => $metad,

                    );
                    if (get_option('liveRates')=='on') {
                        $this->add_rate($rate);
                    }
                    
                }
                

            }
        }
    }
}



add_action('woocommerce_shipping_init', 'omniship_shipping_method_init');

function add_omniship_shipping_method($methods)
{
    $methods['omniship_shipping_method'] = 'WC_OmniShip_Shipping_Method';

    return $methods;
}
add_filter('woocommerce_shipping_methods', 'add_omniship_shipping_method');
}
?>