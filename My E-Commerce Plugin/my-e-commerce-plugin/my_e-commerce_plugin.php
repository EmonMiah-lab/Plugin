<?php
/*
Plugin Name: My E-Commerce Plugin
Description: A basic e-commerce plugin for WordPress
Version: 1.0
Author: Emon Miah
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Add custom post type for products
function create_product_post_type() {
    register_post_type('product',
        array(
            'labels'      => array(
                'name'          => __('Products'),
                'singular_name' => __('Product'),
            ),
            'public'      => true,
            'has_archive' => true,
            'supports'    => array('title', 'editor', 'thumbnail', 'custom-fields'),
            'rewrite'     => array('slug' => 'products'),
        )
    );
}
add_action('init', 'create_product_post_type');



function add_product_meta_boxes() {
    add_meta_box(
        'product_price',
        'Product Price',
        'render_product_price_box',
        'product',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'add_product_meta_boxes');

function render_product_price_box($post) {
    $value = get_post_meta($post->ID, '_product_price', true);
    echo '<label for="product_price">Price</label>';
    echo '<input type="text" id="product_price" name="product_price" value="' . esc_attr($value) . '" />';
}

function save_product_meta_boxes($post_id) {
    if (array_key_exists('product_price', $_POST)) {
        update_post_meta(
            $post_id,
            '_product_price',
            $_POST['product_price']
        );
    }
}
add_action('save_post', 'save_product_meta_boxes');




function display_products_shortcode() {
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 10,
    );
    $query = new WP_Query($args);

    $output = '<div class="products">';
    while ($query->have_posts()) : $query->the_post();
        $price = get_post_meta(get_the_ID(), '_product_price', true);
        $output .= '<div class="product">';
        $output .= '<h2>' . get_the_title() . '</h2>';
        $output .= '<p>' . get_the_content() . '</p>';
        $output .= '<p>Price: ' . esc_html($price) . '</p>';
        $output .= '</div>';
    endwhile;
    wp_reset_postdata();

    $output .= '</div>';

    return $output;
}
add_shortcode('display_products', 'display_products_shortcode');




function add_to_cart() {
    if (isset($_POST['product_id'])) {
        $product_id = intval($_POST['product_id']);
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]++;
        } else {
            $_SESSION['cart'][$product_id] = 1;
        }
    }
}
add_action('init', 'add_to_cart');

function display_cart() {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return 'Your cart is empty.';
    }

    $output = '<div class="cart">';
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $product = get_post($product_id);
        $price = get_post_meta($product_id, '_product_price', true);
        $output .= '<p>' . $product->post_title . ' - ' . $quantity . ' x ' . esc_html($price) . '</p>';
    }
    $output .= '</div>';

    return $output;
}
add_shortcode('display_cart', 'display_cart');




function display_paypal_button($total) {
    $paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
    $paypal_id = 'your-paypal-business-id'; // Replace with your PayPal business ID
    $return_url = home_url('/thank-you'); // Replace with the actual URL you want users to return to after payment

    echo '<form action="' . esc_url($paypal_url) . '" method="post">';
    echo '<input type="hidden" name="cmd" value="_xclick">';
    echo '<input type="hidden" name="business" value="' . esc_attr($paypal_id) . '">';
    echo '<input type="hidden" name="item_name" value="E-commerce Purchase">';
    echo '<input type="hidden" name="amount" value="' . esc_attr($total) . '">';
    echo '<input type="hidden" name="currency_code" value="USD">';
    echo '<input type="submit" value="Pay with PayPal">';
    echo '</form>';
}



// Register custom taxonomy for product categories
function create_product_taxonomies() {
    register_taxonomy(
        'product_category',
        'product',
        array(
            'label' => __('Product Categories'),
            'rewrite' => array('slug' => 'product-category'),
            'hierarchical' => true,
        )
    );
}
add_action('init', 'create_product_taxonomies');



// Add a meta box for product variations
function add_product_variation_meta_box() {
    add_meta_box(
        'product_variations',
        'Product Variations',
        'render_product_variations_box',
        'product',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'add_product_variation_meta_box');

function render_product_variations_box($post) {
    $variations = get_post_meta($post->ID, '_product_variations', true);
    echo '<label for="product_variations">Variations (comma-separated, e.g., "Small,Medium,Large")</label>';
    echo '<input type="text" id="product_variations" name="product_variations" value="' . esc_attr($variations) . '" />';
}

function save_product_variations($post_id) {
    if (array_key_exists('product_variations', $_POST)) {
        update_post_meta(
            $post_id,
            '_product_variations',
            sanitize_text_field($_POST['product_variations'])
        );
    }
}
add_action('save_post', 'save_product_variations');



// Enqueue AJAX JavaScript
function enqueue_ajax_cart_script() {
    wp_enqueue_script(
        'ajax-cart',
        plugins_url('/js/ajax-cart.js', __FILE__),
        array('jquery'),
        null,
        true
    );
    wp_localize_script('ajax-cart', 'ajax_cart_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_ajax_cart_script');



// Handle AJAX Add to Cart
function handle_ajax_add_to_cart() {
    $product_id = intval($_POST['product_id']);

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }

    wp_send_json_success('Product added to cart');
}
add_action('wp_ajax_add_to_cart', 'handle_ajax_add_to_cart');
add_action('wp_ajax_nopriv_add_to_cart', 'handle_ajax_add_to_cart');



// Create a custom table for coupons when the plugin is activated
function create_coupons_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'coupons';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        code varchar(100) NOT NULL,
        discount decimal(10,2) NOT NULL,
        expiry_date date DEFAULT NULL,
        PRIMARY KEY (id),
        UNIQUE (code)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'create_coupons_table');

// Add coupon input field on the cart page
function display_coupon_field() {
    echo '<label for="coupon_code">Coupon Code</label>';
    echo '<input type="text" id="coupon_code" name="coupon_code" />';
    echo '<button type="button" id="apply_coupon_button">Apply Coupon</button>';
}
add_action('woocommerce_before_cart_totals', 'display_coupon_field');

// Handle coupon application
function apply_coupon() {
    global $wpdb;
    $coupon_code = sanitize_text_field($_POST['coupon_code']);

    $table_name = $wpdb->prefix . 'coupons';
    $coupon = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE code = %s AND (expiry_date IS NULL OR expiry_date >= CURDATE())", $coupon_code));

    if ($coupon) {
        $discount = $coupon->discount;
        $_SESSION['cart_discount'] = $discount;
        wp_send_json_success('Coupon applied! Discount: ' . $discount);
    } else {
        wp_send_json_error('Invalid coupon code.');
    }
}
add_action('wp_ajax_apply_coupon', 'apply_coupon');
add_action('wp_ajax_nopriv_apply_coupon', 'apply_coupon');



// Send order confirmation email
function send_order_confirmation_email($order_id, $customer_email) {
    $subject = 'Order Confirmation - ' . get_bloginfo('name');
    $message = 'Thank you for your order! Your order ID is ' . $order_id . '. We will notify you once your order is shipped.';
    $headers = array('Content-Type: text/html; charset=UTF-8');

    wp_mail($customer_email, $subject, $message, $headers);
}

// Call the function after an order is placed
function handle_order_completion($order_id) {
    $customer_email = 'customer@example.com'; // Replace with actual customer email
    send_order_confirmation_email($order_id, $customer_email);
}
add_action('woocommerce_thankyou', 'handle_order_completion');



// Create a product search form
function display_product_search_form() {
    ?>
    <form method="get" action="<?php echo esc_url(home_url('/')); ?>">
        <input type="text" name="s" placeholder="Search products...">
        <input type="hidden" name="post_type" value="product">
        <button type="submit">Search</button>
    </form>
    <?php
}
add_shortcode('product_search', 'display_product_search_form');

// Modify the query to search within the product post type
function product_search_filter($query) {
    if ($query->is_search && !is_admin()) {
        $query->set('post_type', 'product');
    }
    return $query;
}
add_filter('pre_get_posts', 'product_search_filter');






//-----------------------------------oders admin manu option-------------------

// Add an 'Orders' menu to the admin dashboard
function ecommerce_plugin_add_admin_menu() {
    add_menu_page(
        'Orders',             // Page title
        'Orders',             // Menu title
        'manage_options',      // Capability
        'ecommerce_orders',    // Menu slug
        'display_orders_page', // Callback function
        'dashicons-cart',      // Icon URL
        6                      // Position in the admin menu
    );
}
add_action('admin_menu', 'ecommerce_plugin_add_admin_menu');



// Display the 'Orders' page content
function display_orders_page() {
    global $wpdb;

    echo '<div class="wrap">';
    echo '<h1 class="wp-heading-inline">Orders</h1>';

    // Fetch orders from the custom database table (optional or can be customized to your needs)
    $table_name = $wpdb->prefix . 'ecommerce_orders';
    $orders = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC");

    // Check if there are any orders
    if (empty($orders)) {
        echo '<p>No orders found.</p>';
    } else {
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr>';
        echo '<th scope="col">Order ID</th>';
        echo '<th scope="col">Customer Name</th>';
        echo '<th scope="col">Email</th>';
        echo '<th scope="col">Total</th>';
        echo '<th scope="col">Status</th>';
        echo '</tr></thead>';

        echo '<tbody>';
        foreach ($orders as $order) {
            echo '<tr>';
            echo '<td>' . esc_html($order->id) . '</td>';
            echo '<td>' . esc_html($order->customer_name) . '</td>';
            echo '<td>' . esc_html($order->email) . '</td>';
            echo '<td>$' . esc_html($order->total) . '</td>';
            echo '<td>' . esc_html($order->status) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    }

    echo '</div>';
}



// Create a custom table for storing orders on plugin activation
function create_orders_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ecommerce_orders';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        customer_name varchar(255) NOT NULL,
        email varchar(100) NOT NULL,
        total decimal(10,2) NOT NULL,
        status varchar(50) DEFAULT 'pending',
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'create_orders_table');



// Insert a new order into the database
function insert_order($customer_name, $email, $total, $status = 'pending') {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ecommerce_orders';

    $wpdb->insert(
        $table_name,
        array(
            'customer_name' => sanitize_text_field($customer_name),
            'email' => sanitize_email($email),
            'total' => floatval($total),
            'status' => sanitize_text_field($status),
        )
    );

    return $wpdb->insert_id; // Return the order ID
}


// Update the status of an order
function update_order_status($order_id, $new_status) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ecommerce_orders';

    $wpdb->update(
        $table_name,
        array('status' => sanitize_text_field($new_status)),
        array('id' => intval($order_id))
    );
}
