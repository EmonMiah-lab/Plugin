<?php
/**
 * Plugin Name: Custom Service Post Type
 * Description: A simple plugin to create a custom post type for services.
 * Version: 1.0
 * Author: Emon Miah
 */

// Hook into the 'init' action to register the custom post type
add_action('init', 'create_service_post_type');

// Function to create the "Service" custom post type
function create_service_post_type() {
    $labels = array(
        'name' => __('Services'),
        'singular_name' => __('Service'),
        'menu_name' => __('Services'),
        'name_admin_bar' => __('Service'),
        'add_new' => __('Add New'),
        'add_new_item' => __('Add New Service'),
        'new_item' => __('New Service'),
        'edit_item' => __('Edit Service'),
        'view_item' => __('View Service'),
        'all_items' => __('All Services'),
        'search_items' => __('Search Services'),
        'not_found' => __('No Services found.'),
        'not_found_in_trash' => __('No Services found in Trash.'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable'  => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'service'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'menu_icon'          => 'dashicons-hammer',
    );

    register_post_type('service', $args);
}
