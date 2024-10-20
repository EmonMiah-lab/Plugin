<?php

function minify_html($buffer) {
    $search = array(
        '/\>[^\S ]+/s',  // remove whitespaces after tags
        '/[^\S ]+\</s',  // remove whitespaces before tags
        '/(\s)+/s'       // shorten multiple whitespace sequences
    );
    $replace = array(
        '>',
        '<',
        '\\1'
    );
    return preg_replace($search, $replace, $buffer);
}

function start_html_minify() {
    ob_start('minify_html');
}

function end_html_minify() {
    ob_end_flush();
}

add_action('get_header', 'start_html_minify');
add_action('wp_footer', 'end_html_minify');
