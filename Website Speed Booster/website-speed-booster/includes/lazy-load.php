<?php

// Lazy load for images
function lazy_load_images($content) {
    if (!is_feed() || !is_preview()) {
        $content = preg_replace('/<img(.*?)src=/', '<img$1data-src=', $content);
        $content = preg_replace('/<img(.*?)class=/', '<img$1class="lazyload ', $content);
    }
    return $content;
}

add_filter('the_content', 'lazy_load_images');

// Enqueue lazy load script (LazySizes.js)
function enqueue_lazyload_script() {
    wp_enqueue_script('lazyload', 'https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.0/lazysizes.min.js', array(), null, true);
}

add_action('wp_enqueue_scripts', 'enqueue_lazyload_script');
