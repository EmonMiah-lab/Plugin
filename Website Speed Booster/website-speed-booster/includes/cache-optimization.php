<?php

// Enable browser caching
function enable_browser_caching() {
    if (!is_admin()) {
        header("Cache-Control: public, max-age=31536000");
    }
}

add_action('send_headers', 'enable_browser_caching');

// GZIP Compression
function enable_gzip_compression() {
    if ( !ini_get('zlib.output_compression') && !headers_sent() ) {
        ob_start('ob_gzhandler');
    }
}

add_action('init', 'enable_gzip_compression');
