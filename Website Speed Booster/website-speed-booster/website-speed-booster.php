<?php
/*
Plugin Name: Website Speed Booster
Description: A plugin to improve website speed with lazy load, CDN integration, and optimization features.
Version: 1.0
Author: Emon Miah
*/

defined('ABSPATH') or die('No script kiddies please!');

// Include necessary files for features
require_once(plugin_dir_path(__FILE__) . 'includes/lazy-load.php');
require_once(plugin_dir_path(__FILE__) . 'includes/cdn-integration.php');
require_once(plugin_dir_path(__FILE__) . 'includes/cache-optimization.php');

// Initialization hook
function speed_booster_init() {
    // Code to initialize optimization (if any)
}
add_action('init', 'speed_booster_init');
