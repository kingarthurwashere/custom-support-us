<?php

/*
Plugin Name: Custom Support Us
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A custom "Support Us" plugin that allows donations via Paynow, Stripe, and PayPal.
Version: 1.0
Author: King Arthur
Author URI: https://www.linkedin.com/in/arthur-nyasango-211402124/
License: A "Slug" license name e.g. GPL2
*/
// Define constants for paths
define('CUSTOM_SUPPORT_US_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Include Payment Libraries (Paynow, Stripe, PayPal)
require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

// Include the admin settings page
require_once plugin_dir_path(__FILE__) . 'admin/settings-page.php';
require_once plugin_dir_path(__FILE__) . 'options.php'; // Ensure options.php also uses include_once if necessary



// Enqueue styles
function custom_support_us_enqueue_styles() {
	wp_enqueue_style('custom-support-us-style', plugins_url('assets/css/custom-support-us.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'custom_support_us_enqueue_styles');

// Register the shortcode for the payment form
function custom_support_us_form_shortcode() {
	ob_start();
	?>
	<form id="support-us-form">
		<label for="donation-amount">Donation Amount (USD)</label>
		<input type="number" id="donation-amount" name="donation-amount" min="1" required>

		<label for="email">Your Email</label>
		<input type="email" id="email" name="email" required>

		<label for="payment-method">Choose Payment Method</label>
		<select id="payment-method" name="payment-method" required>
			<option value="paynow">Paynow</option>
			<option value="stripe">Stripe</option>
			<option value="paypal">PayPal</option>
		</select>

		<button type="submit">Donate Now</button>
	</form>
	<?php
	return ob_get_clean();
}
add_shortcode('support_us_form', 'custom_support_us_form_shortcode');

// Handle form submission and process payment based on the selected method
function custom_support_us_process_payment() {
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$payment_method = $_POST['payment-method'];
		$amount = $_POST['donation-amount'];
		$email = $_POST['email'];

		switch ($payment_method) {
			case 'paynow':
				// Process Paynow payment
				custom_support_us_process_paynow($amount, $email);
				break;

			case 'stripe':
				// Process Stripe payment
				custom_support_us_process_stripe($amount, $email);
				break;

			case 'paypal':
				// Process PayPal payment
				custom_support_us_process_paypal($amount, $email);
				break;
		}
	}
}
add_action('init', 'custom_support_us_process_payment');

// Placeholder functions for payment processing
function custom_support_us_process_paynow($amount, $email) {
	// Paynow payment logic here
}

function custom_support_us_process_stripe($amount, $email) {
	// Stripe payment logic here
}

function custom_support_us_process_paypal($amount, $email) {
	// PayPal payment logic here
}
