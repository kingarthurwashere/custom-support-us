<?php

/**
 * Plugin Name: Custom Support Us
 * Plugin URI: https://github.com/kingarthurwashere/custom-support-us
 * Description: A custom "Support Us" plugin that allows donations via Paynow, Stripe, and PayPal.
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Version: 1.0
 * Author: King Arthur
 * Author URI: https://www.linkedin.com/in/arthur-nyasango-211402124/
 * License: GPL2
 */

// Define constants for paths
define('CUSTOM_SUPPORT_US_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Include Payment Libraries (Paynow, Stripe, PayPal)
require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

// Include the admin settings page
require_once plugin_dir_path(__FILE__) . 'admin/settings-page.php';
require_once plugin_dir_path(__FILE__) . 'options.php'; // Ensure options.php also uses include_once if necessary

// Use necessary namespaces
use Paynow\Paynow;
use Stripe\Stripe;
use Stripe\Exception\ApiErrorException;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;
use PayPal\Exception\PayPalConnectionException;

// Enqueue styles
function custom_support_us_enqueue_styles() {
	wp_enqueue_style('custom-support-us-style', plugins_url('assets/css/custom-support-us.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'custom_support_us_enqueue_styles');

// Register the shortcode for the payment form
function custom_support_us_form_shortcode() {
	ob_start();
	?>
    <form id="support-us-form" method="POST">
		<?php wp_nonce_field('custom_support_us_donation', 'custom_support_us_nonce'); ?>
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
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['custom_support_us_nonce']) && wp_verify_nonce($_POST['custom_support_us_nonce'], 'custom_support_us_donation')) {
		$payment_method = sanitize_text_field($_POST['payment-method']);
		$amount = floatval($_POST['donation-amount']);
		$email = sanitize_email($_POST['email']);

		switch ($payment_method) {
			case 'paynow':
				custom_support_us_process_paynow($amount, $email);
				break;

			case 'stripe':
				custom_support_us_process_stripe($amount, $email);
				break;

			case 'paypal':
				custom_support_us_process_paypal($amount, $email);
				break;
		}
	}
}

add_action('init', 'custom_support_us_process_payment');

function custom_support_us_process_paynow($amount, $email) {
	// Get Paynow API keys from options
	$apiKey = get_option('custom_support_us_paynow_api_key');
	$signatureKey = get_option('custom_support_us_paynow_signature_key');

	$paynow = new Paynow($apiKey, $signatureKey);

	$payment = $paynow->createPayment($amount, 'Payment for Support Us');
	$payment->add('item', 'Donation', $amount);

	$url = $payment->getPaymentUrl();

	// Redirect user to Paynow payment page
	if ($url) {
		header("Location: $url");
		exit();
	} else {
		// Handle error
		echo 'Error creating Paynow payment.';
	}
}

function custom_support_us_process_stripe($amount, $email) {
	// Get Stripe API key from options
	$stripeApiKey = get_option('custom_support_us_stripe_api_key');
	Stripe::setApiKey($stripeApiKey);

	try {
		// Create a payment intent
		$paymentIntent = \Stripe\PaymentIntent::create([
			'amount' => $amount * 100, // Amount in cents
			'currency' => 'usd', // Change this to your desired currency
			'payment_method_types' => ['card'],
			'receipt_email' => $email,
		]);

		// Return client secret for the frontend to complete the payment
		return $paymentIntent->client_secret;
	} catch (ApiErrorException $e) {
		// Handle error
		echo 'Error creating Stripe payment: ' . $e->getMessage();
	}
}

function custom_support_us_process_paypal($amount, $email) {
	// Get PayPal API context from options
	$clientId = get_option('custom_support_us_paypal_client_id');
	$clientSecret = get_option('custom_support_us_paypal_secret');

	$apiContext = new \PayPal\Rest\ApiContext(
		new \PayPal\Auth\OAuthTokenCredential(
			$clientId,
			$clientSecret
		)
	);

	// Create a new payment
	$payer = new Payer();
	$payer->setPaymentMethod('paypal');

	$amountObj = new Amount();
	$amountObj->setTotal($amount);
	$amountObj->setCurrency('USD');

	$transaction = new Transaction();
	$transaction->setAmount($amountObj);
	$transaction->setDescription('Donation for Support Us');

	$redirectUrls = new \PayPal\Api\RedirectUrls();
	$redirectUrls->setReturnUrl(get_option('custom_support_us_paypal_return_url'))
	             ->setCancelUrl(get_option('custom_support_us_paypal_cancel_url'));


	$payment = new Payment();
	$payment->setIntent('sale')
	        ->setPayer($payer)
	        ->setRedirectUrls($redirectUrls)
	        ->setTransactions([$transaction]);

	try {
		$payment->create($apiContext);
		// Redirect user to PayPal payment page
		header("Location: " . $payment->getApprovalLink());
		exit();
	} catch (PayPalConnectionException $ex) {
		// Handle error
		echo 'Error creating PayPal payment: ' . $ex->getMessage();
	}
}
