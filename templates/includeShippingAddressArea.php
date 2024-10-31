<?php
// Shipping Address Area

add_action('woocommerce_admin_order_data_after_shipping_address', 'display_custom_fields_in_admin_order', 20, 1);

function display_custom_fields_in_admin_order($order)
{
   
    $omniship_labelurl = get_post_meta($order->get_id() , '_omniship_labelurl', true);
	$omniship_tracking = get_post_meta($order->get_id() , '_omniship_tracking', true);

	$omniship_orderid = $order->get_id();
	$user_id = new WC_Customer( $omniship_orderid  );
    foreach ($order->get_items('shipping') as $item_id => $shipping_item_obj)
    {
        $shipping_item_data = $shipping_item_obj->get_data();
		$shipping_data_method_title = $shipping_item_data['method_title'];
		$omniship_quote_id =  $shipping_item_obj->get_meta("OmniShipQuoteID");

    }
	$omniship_shipmethod ="03";
    if ($shipping_data_method_title == "UPS Ground") $omniship_shipmethod = "03";
    if ($shipping_data_method_title == "UPS 2nd Day Air") $omniship_shipmethod = "02";
    if ($shipping_data_method_title == "UPS Next Day Air Saver") $omniship_shipmethod = "13";
    if ($shipping_data_method_title == "UPS Next Day Air") $omniship_shipmethod = "01";

    if ($omniship_quote_id || get_option('liveRates')=='')
    {
        include OmniShip_plugin_path . 'templates/style.php';
       // echo '<p><strong>'.__('OmniShip Quote ID').':</strong> ' . $omniship_quote_id . '</p>';

	   $body = array(
		'OmniShipCustomerID' => get_option('OmniShipClientID')
		);

		$url = "https://omnishipproxy.herokuapp.com/https://omniship.herokuapp.com/config";

		$headers = array(
			'Authorization' => 'Basic U3RyM0FQSTpTdHIzQVBJOldUSExPNFQ0UEYyNklERDE=',
			'Content-Type' => 'application/json',
			'accessKey' => get_option('accessKey'),
			'X-Requested-With' => 'XMLHttpRequest' 
		);
	
		$args = array(
			'headers' => $headers,
			'body'        => json_encode($body),
			'method'      => 'POST',
			'data_format' => 'body',
		);
		
		$response= wp_remote_post( $url, $args );
		$response = str_replace("'", '"', $response);
		$resp = json_decode($response["body"], true);

        $options = '';

        echo '<div class="form dimension-field">
				<form method="POST" id="requestForm">
                <span class="heading">Package Information</span>
                <span class="edit d-none">Edit</span>
                <div class="omniShipfields">
					<select class="omnishipSelect" id="dimension" name="dimension" onchange="OmniShipUpdateDims();">';


        foreach ($resp["packages"] as $row => $innerArray)
        {
            $desc = $innerArray["Description"];
            $length = $innerArray["length"];
            $width = $innerArray["width"];
            $height = $innerArray["height"];

            echo '<option data-name="' . sanitize_text_field($desc) . '" data-height="' . sanitize_text_field($height) . '" data-width="' . sanitize_text_field($width) . '" data-length="' . sanitize_text_field($length) . '">' . sanitize_text_field($desc) . ' ' . sanitize_text_field($height) . ' x ' . sanitize_text_field($length) . ' x ' . sanitize_text_field($width) . '</option>';

		}
		
		$Destination = array(
			'Name' => sanitize_text_field($order->shipping_first_name . ' ' . $order->shipping_last_name),
			'Street' => sanitize_text_field($order->shipping_address_1),
			'City' => sanitize_text_field($order->shipping_city),
			'State' => sanitize_text_field($order->shipping_state),
			'Zip' => sanitize_text_field($order->shipping_postcode),
			'Country' => sanitize_text_field($order->shipping_country)
		);
		$dest = json_encode($Destination);
		
		$Origin = array(
			'Name' => get_option('company'),
			'Street' => get_option( 'woocommerce_store_address' ),
			'City' => get_option( 'woocommerce_store_city' ),
			'State' =>  get_option('state'),
			'Zip' => get_option('zipcode'),
			'Country' => get_option('country'),
		);
		$origin = json_encode($Origin);

		echo '     </select>
		
                    <label for="weight" class="omnishipLabel">Weight*</label>
                    <input type="text" id="weight" class="omnishipText" name="weight"/>
                    <label for="height" class="omnishipLabel">Height*:</label>
                    <input type="text" id="height" class="omnishipText" name="height"/>
                    <label for="length" class="omnishipLabel">Length*:</label>
                    <input type="text" id="length" class="omnishipText" name="length"/>
                    <label for="width" class="omnishipLabel">Width:</label>
					<input type="text" id="width" class="omnishipText" name="width"/>';
		echo '<select class="omnishipSelect" id="method" name="method" >';
		echo '<option value="03">UPS Ground</option>
		<option value="02">UPS Second Day</option>
		<option value="01">UPS Next Day</option>
		</select>
					
					<label for="tracking" class="omnishipLabelPost hidden">Tracking:</label>
                    <label for="tracking"  id="tracking" class="omnishipTextPost " name="tracking">' . sanitize_text_field($omniship_tracking) . '</label>
           			
                </div>
				<div><img src="" id="labelImg" onclick="OmniShipPrintLabel()" class="hidden" /></div>

				<button type="button" onclick="OmniShipPostButton()" class="omnishipButton">Make Label</button>
            </div>

            <input type="hidden" name="OmniShipCustomerID" value="' . get_option('OmniShipClientID') . '">
            <input type="hidden" name="isProduction" value="'. get_option('isProduction') .'">
            <input type="hidden" name="ShippingType" value="'. get_option('shippingType') .'">
            <input type="hidden" name="CacheClear" value="false">
            <input type="hidden" name="configURL" value="https://omniship.transport-logic.com/config">
			</form>';

        echo " <script type=\"text/javascript\">
			function OmniShipGetSelectedOption(sel) {
				var opt;
				for ( var i = 0, len = sel.options.length; i < len; i++ ) {
					opt = sel.options[i];
					if ( opt.selected === true ) {
						break;
					}
				}
				return opt;
			}

			function OmniShipUpdateDims() {
				var opt = OmniShipGetSelectedOption(document.getElementById('dimension'));
				var height = opt.dataset.height;
				var width =opt.dataset.width
				var length = opt.dataset.length
				document.getElementById(\"height\").value =height;
				document.getElementById(\"width\").value =width;
				document.getElementById(\"length\").value =length;
			}
			OmniShipUpdateDims();";


		
        echo '
		   function OmniShipPostButton(){ 			
			var data = JSON.stringify({"OmniShipCustomerID":"' . get_option('OmniShipClientID') .'",
									   "isProduction":' . get_option('isProduction') .',
									   "shippingMethod": document.getElementById("method").value,
									   "OmniShipQuote":"' . sanitize_text_field($omniship_quote_id) . '",
									   "orderNo":"' . sanitize_text_field($omniship_orderid) . '",
									   "weight":document.getElementById("weight").value,
									   "height":document.getElementById("height").value,
									   "length":document.getElementById("length").value,
									   "width":document.getElementById("width").value,
									   "Destination" : ' . sanitize_text_field($dest) . ',
									   "Origin" : ' . sanitize_text_field($origin) . '
									}	
									);

			var xhr = new XMLHttpRequest();
			
			xhr.addEventListener("readystatechange", function() {
			  if(this.readyState === 4) {
				OmniShippostProcessLabel(this.responseText);
			  }
			});

			xhr.open("POST", "https://omnishipproxy.herokuapp.com/https://omniship.transport-logic.com/getLabelFromPage");
			xhr.setRequestHeader("Authorization", "Basic U3RyM0FQSTpTdHIzQVBJOldUSExPNFQ0UEYyNklERDE=");
			xhr.setRequestHeader("Content-Type", "application/json");
			xhr.setRequestHeader("accessKey", "' . get_option('accessKey') .'");
			xhr.setRequestHeader("x-requested-with", "XMLHttpRequest");

			xhr.send(data);

			};

			function OmniShipchangeViewMode(){
				document.querySelectorAll(".omnishipText").forEach(function(el) {
				   el.style.display = "none";
				});
				document.querySelectorAll(".omnishipLabel").forEach(function(el) {
				   el.style.display = "none";
				});
				document.querySelectorAll(".omnishipButton").forEach(function(el) {
				   el.style.display = "none";
				});
				document.querySelectorAll(".omnishipSelect").forEach(function(el) {
				   el.style.display = "none";
				});
				document.querySelectorAll(".omnishipLabelPost").forEach(function(el) {
				   el.classList.remove("hidden");
				});
				document.querySelectorAll(".omnishipTextPost").forEach(function(el) {
                    el.classList.remove("hidden");
                 });
                document.getElementById("labelImg").classList.remove("hidden");
				
			}
			
			function OmniShipPrintLabel(){
				var url=document.getElementById("labelURL").innerHTML;
				var win = window.open("", "Print Label", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=800,height=1400,top="+(200)+",left="+(screen.width-640));
				win.document.body.innerHTML = "<html><body><img width=\''.get_option('labelWidth').'\' src=\'"+url+"\' ></body></html>";
				setTimeout(function () { win.print(); }, 600);
				win.onfocus = function () { setTimeout(function () { win.close(); }, 800); }
			}


			function OmniShippostProcessLabel(response){
				var resp=JSON.parse(response);
				OmniShipchangeViewMode();
				document.getElementById("tracking").innerText=resp["trackingNumber"];
				document.getElementById("labelURL").innerText=resp["url"];
				document.getElementById("labelImg").src=resp["url"];
				
				
				var data = JSON.stringify({"meta_data":[{"key":"_omniship_tracking","value":resp["trackingNumber"]},{"key":"_omniship_labelurl","value":resp["url"]}]});

				var xhr = new XMLHttpRequest();
				//xhr.withCredentials = true;
				console.log(data);
				xhr.addEventListener("readystatechange", function() {
				  if(this.readyState === 4) {
					console.log(this.responseText);
				  }
				});
				var apiurl="';
				echo  wc_get_cart_url();
				echo '";
				var orderid="';
				echo $omniship_orderid;
				echo '";

				console.log(apiurl);
				apiurl = apiurl.replace("/cart","/");
				console.log(apiurl);
				apiurl = apiurl+"wp-json/wc/v3/orders/";
				console.log(apiurl);
				apiurl = apiurl + orderid + "?filter%5Bmeta%5D=true";
				console.log(apiurl);
				xhr.open("POST",apiurl);
				xhr.setRequestHeader("Authorization", "Basic ' . get_option("wooAPIKey") . '");
				xhr.setRequestHeader("Content-Type", "application/json");
				xhr.send(data);
			};
			</script>
			';


        if (strlen($omniship_labelurl) > 1)
        {
            echo '<script>
            OmniShipchangeViewMode();
			document.getElementById("labelImg").src = "' . sanitize_text_field($omniship_labelurl) . '";
			</script>';
        }
    }
    

}


?>