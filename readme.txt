=== Custom Support Us ===
Contributors: Kingarthurwashere
Tags: payment, donation, stripe, paypal, paynow
Requires at least: 5.0
Tested up to: 6.3
Requires PHP: 7.2
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A custom "Support Us" plugin that allows donations via Paynow, Stripe, and PayPal.

== Description ==

The Custom Support Us plugin enables website owners to collect donations via three payment gateways: Paynow (for Zimbabwe), Stripe, and PayPal. It provides a simple form for users to choose their preferred method and make contributions to support your cause.

== Installation ==

1. Upload the `custom-support-us` plugin to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to the 'Support Us' settings in the WordPress admin dashboard to configure your API keys for Paynow, Stripe, and PayPal.
4. Use the `[support_us_form]` shortcode on any page to display the donation form.

== Frequently Asked Questions ==

= How do I add the donation form to a page? =
Simply use the `[support_us_form]` shortcode on any page or post where you want the form to appear.

= How do I configure my payment gateways? =
After activating the plugin, visit the 'Support Us' settings in the WordPress admin area to enter your API keys for Paynow, Stripe, and PayPal.

= Can I use this plugin without an SSL certificate? =
No, for security reasons, you must have SSL (https) enabled on your site to use this plugin with Stripe and PayPal.

== Changelog ==

= 1.0.0 =
* Initial release with support for Paynow, Stripe, and PayPal payments.

== Upgrade Notice ==

= 1.0.0 =
* Initial release.

== License ==

This plugin is licensed under the GPL2 license. You may modify or distribute it under the same license.
