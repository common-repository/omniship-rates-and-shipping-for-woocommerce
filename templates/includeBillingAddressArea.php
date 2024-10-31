<?php


add_action('woocommerce_checkout_update_order_meta', function ($order_id, $posted)
{
    $order = wc_get_order($order_id);
    $order->update_meta_data('_omniship_quoteid', sanitize_text_field($_SESSION['quoteID']));
    $order->save();
}
, 10, 2);


add_action('woocommerce_admin_order_data_after_billing_address', 'OmniShipdisplaylabelURL', 20, 1);
function OmniShipdisplaylabelURL($order)
{
    $omniship_labelurl = get_post_meta($order->get_id() , '_omniship_labelurl', true);
    echo ' 
    <label for="labelURL" class="omnishipLabelPost hidden">Label:</label>
    <label for="labelURL" id="labelURL" class="omnishipTextPost hidden" name="labelURL"/>' . sanitize_text_field($omniship_labelurl). '</label>';
}

?>