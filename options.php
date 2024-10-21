<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

// Function to register settings
function custom_support_us_register_settings() {
	register_setting('custom_support_us_options_group', 'custom_support_us_paynow_api_key');
	register_setting('custom_support_us_options_group', 'custom_support_us_paynow_signature_key');
	register_setting('custom_support_us_options_group', 'custom_support_us_stripe_api_key');
	register_setting('custom_support_us_options_group', 'custom_support_us_paypal_client_id');
	register_setting('custom_support_us_options_group', 'custom_support_us_paypal_secret');
}
add_action('admin_init', 'custom_support_us_register_settings');

// Function to create the settings page
if (!function_exists('custom_support_us_settings_page')) {

	function custom_support_us_settings_page() {
		add_options_page(
			'Custom Support Us Settings',
			'Support Us',
			'manage_options',
			'custom-support-us-settings',
			'custom_support_us_settings_page_html'
		);
	}
}
add_action('admin_menu', 'custom_support_us_settings_page');

// Settings page HTML
function custom_support_us_settings_page_html() {
	?>
	<div class="wrap">
		<h1>Custom Support Us Settings</h1>
		<form method="post" action="options.php">
			<?php
			settings_fields('custom_support_us_options_group');
			do_settings_sections('custom_support_us_options_group');
			?>
			<h2>Paynow Settings</h2>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Paynow API Key</th>
					<td><input type="text" name="custom_support_us_paynow_api_key" value="<?php echo esc_attr(get_option('custom_support_us_paynow_api_key')); ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row">Paynow Signature Key</th>
					<td><input type="text" name="custom_support_us_paynow_signature_key" value="<?php echo esc_attr(get_option('custom_support_us_paynow_signature_key')); ?>" /></td>
				</tr>
			</table>

			<h2>Stripe Settings</h2>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Stripe API Key</th>
					<td><input type="text" name="custom_support_us_stripe_api_key" value="<?php echo esc_attr(get_option('custom_support_us_stripe_api_key')); ?>" /></td>
				</tr>
			</table>

			<h2>PayPal Settings</h2>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">PayPal Client ID</th>
					<td><input type="text" name="custom_support_us_paypal_client_id" value="<?php echo esc_attr(get_option('custom_support_us_paypal_client_id')); ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row">PayPal Secret</th>
					<td><input type="text" name="custom_support_us_paypal_secret" value="<?php echo esc_attr(get_option('custom_support_us_paypal_secret')); ?>" /></td>
				</tr>
			</table>

			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

