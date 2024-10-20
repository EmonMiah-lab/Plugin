=== My E-Commerce Plugin ===
Contributors: Emon Miah
Donate link: https://yourwebsite.com/donate
Tags: ecommerce, products, shopping cart, paypal, stripe
Requires at least: 5.0
Tested up to: 6.3
Requires PHP: 7.2
Stable tag: 1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

My E-Commerce Plugin provides simple e-commerce functionality for selling products through a WordPress site. It includes product listings, shopping cart, and integration with PayPal.

== Description ==

My E-Commerce Plugin is a lightweight solution for adding e-commerce capabilities to your WordPress website. Easily manage products, display them on the front-end, and allow customers to purchase via PayPal.

**Features:**
- Custom post type for products
- Product meta fields for price
- Shopping cart with session support
- Basic PayPal checkout integration

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/my-ecommerce-plugin/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Create a new page and add the `[display_products]` shortcode to display your products.
4. To display the shopping cart, create a new page with the `[display_cart]` shortcode.

== Frequently Asked Questions ==

= How do I add a product? =
After activating the plugin, you will find a "Products" menu in the WordPress admin. From there, you can add new products with price and description.

= Can I integrate other payment gateways? =
Currently, the plugin supports PayPal by default, but you can extend it to include other payment gateways like Stripe by modifying the checkout process.

== Screenshots ==

1. Product listing example on the frontend.
2. Shopping cart display with items added.
3. PayPal checkout button.

== Changelog ==

= 1.0 =
* Initial release.

== Upgrade Notice ==

= 1.0 =
This is the first release of My E-Commerce Plugin.

== Frequently Asked Questions ==

= How do I add shortcodes for products? =
Use the `[display_products]` shortcode to display your products on any page or post.

= How can I customize the product display? =
You can override the CSS styles and modify the HTML output of the shortcodes directly in your theme files.

== License ==

This plugin is licensed under the GPLv2 or later. Please see the `LICENSE` file for more information.
