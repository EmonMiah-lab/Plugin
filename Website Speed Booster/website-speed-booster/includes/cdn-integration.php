<?php

// Function to rewrite URLs to CDN
function cdn_url_rewrite($url) {
    $cdn_url = 'https://your-cdn-url.com';
    return str_replace(home_url(), $cdn_url, $url);
}

// Apply the filter to assets like images, CSS, and JS
function apply_cdn_to_assets($content) {
    $content = cdn_url_rewrite($content);
    return $content;
}

add_filter('wp_get_attachment_url', 'cdn_url_rewrite'); // For media files
add_filter('style_loader_src', 'cdn_url_rewrite'); // For CSS files
add_filter('script_loader_src', 'cdn_url_rewrite'); // For JS files
