<?php
// Add the menu for settings
function custom_support_us_add_admin_menu() {
	add_menu_page(
		'Custom Support Us Settings', // Page title
		'Support Us Settings',         // Menu title
		'manage_options',              // Capability
		'custom-support-us',           // Menu slug
		'custom_support_us_settings_page' // Callback function
	);
}
add_action('admin_menu', 'custom_support_us_add_admin_menu');

// Render the settings page
function custom_support_us_settings_page() {
	?>
    <div class="wrap">
        <h1>Custom Support Us Settings</h1>
        <form method="post" action="../options.php">
			<?php
			settings_fields('custom_support_us_options_group');
			do_settings_sections('custom-support-us');
			submit_button();
			?>
        </form>
    </div>
	<?php
}

// Register the settings fields
function custom_support_us_settings_init() {
	register_setting('custom_support_us_options_group', 'custom_support_us_paynow_api_key');
	register_setting('custom_support_us_options_group', 'custom_support_us_paynow_signature_key');
	register_setting('custom_support_us_options_group', 'custom_support_us_stripe_api_key');
	register_setting('custom_support_us_options_group', 'custom_support_us_paypal_client_id');
	register_setting('custom_support_us_options_group', 'custom_support_us_paypal_secret');

	// New settings for PayPal URLs
	register_setting('custom_support_us_options_group', 'custom_support_us_paypal_return_url');
	register_setting('custom_support_us_options_group', 'custom_support_us_paypal_cancel_url');

	// Add a section to the settings page
	add_settings_section('custom_support_us_section', 'Payment Gateway Settings', null, 'custom-support-us');

	// Add the fields to the section
	add_settings_field('custom_support_us_paynow_api_key', 'Paynow API Key', 'custom_support_us_paynow_api_key_render', 'custom-support-us', 'custom_support_us_section');
	add_settings_field('custom_support_us_paynow_signature_key', 'Paynow Signature Key', 'custom_support_us_paynow_signature_key_render', 'custom-support-us', 'custom_support_us_section');
	add_settings_field('custom_support_us_stripe_api_key', 'Stripe API Key', 'custom_support_us_stripe_api_key_render', 'custom-support-us', 'custom_support_us_section');
	add_settings_field('custom_support_us_paypal_client_id', 'PayPal Client ID', 'custom_support_us_paypal_client_id_render', 'custom-support-us', 'custom_support_us_section');
	add_settings_field('custom_support_us_paypal_secret', 'PayPal Secret', 'custom_support_us_paypal_secret_render', 'custom-support-us', 'custom_support_us_section');

	// New fields for PayPal return and cancel URLs
	add_settings_field('custom_support_us_paypal_return_url', 'PayPal Return URL', 'custom_support_us_paypal_return_url_render', 'custom-support-us', 'custom_support_us_section');
	add_settings_field('custom_support_us_paypal_cancel_url', 'PayPal Cancel URL', 'custom_support_us_paypal_cancel_url_render', 'custom-support-us', 'custom_support_us_section');
}
add_action('admin_init', 'custom_support_us_settings_init');

// Render settings fields
function custom_support_us_paynow_api_key_render() {
	$api_key = get_option('custom_support_us_paynow_api_key');
	echo '<input type="text" name="custom_support_us_paynow_api_key" value="' . esc_attr($api_key) . '" size="50" />';
}

function custom_support_us_paynow_signature_key_render() {
	$signature_key = get_option('custom_support_us_paynow_signature_key');
	echo '<input type="text" name="custom_support_us_paynow_signature_key" value="' . esc_attr($signature_key) . '" size="50" />';
}

function custom_support_us_stripe_api_key_render() {
	$stripe_key = get_option('custom_support_us_stripe_api_key');
	echo '<input type="text" name="custom_support_us_stripe_api_key" value="' . esc_attr($stripe_key) . '" size="50" />';
}

function custom_support_us_paypal_client_id_render() {
	$paypal_client_id = get_option('custom_support_us_paypal_client_id');
	echo '<input type="text" name="custom_support_us_paypal_client_id" value="' . esc_attr($paypal_client_id) . '" size="50" />';
}

function custom_support_us_paypal_secret_render() {
	$paypal_secret = get_option('custom_support_us_paypal_secret');
	echo '<input type="text" name="custom_support_us_paypal_secret" value="' . esc_attr($paypal_secret) . '" size="50" />';
}

// New rendering functions for PayPal return and cancel URLs
function custom_support_us_paypal_return_url_render() {
	$return_url = get_option('custom_support_us_paypal_return_url');
	echo '<input type="text" name="custom_support_us_paypal_return_url" value="' . esc_attr($return_url) . '" size="50" placeholder="https://yourdomain.com/return" />';
}

function custom_support_us_paypal_cancel_url_render() {
	$cancel_url = get_option('custom_support_us_paypal_cancel_url');
	echo '<input type="text" name="custom_support_us_paypal_cancel_url" value="' . esc_attr($cancel_url) . '" size="50" placeholder="https://yourdomain.com/cancel" />';
}
