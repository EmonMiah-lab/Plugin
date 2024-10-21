<?php

add_action( 'rest_api_init', 'my_custom_post_type_add_rest_route');

function my_custom_post_type_add_rest_route(){

    register_rest_route( 'setup_my_custom_post_type/v1', '/setup', array(
        'methods'       => WP_REST_Server::EDITABLE,
        'callback'      => 'my_custom_post_type_update_data',
        'permission_callback' => '__return_true',
    ));

}

/* < ========== Add My Custom Post Type Data ================> */

function my_custom_post_type_update_data($request){

    global $wpdb;

    $headers = $request->get_headers();
    $nonce   = $headers['x_wp_nonce'][0];

    if(!$nonce && !wp_verify_nonce($nonce, 'wp_rest')){
        return new WP_REST_Response( "There're something Wrong!" );
    }

    $params = $request->get_params();
    $post_name  = sanitize_text_field($params['post_type_name']);
    $category_name = sanitize_text_field($params['category_name']);
    $tag_name = sanitize_text_field($params['tag_name']);
    $id   = 1;


    $myPost = new My_Custom_Post_Type();
    $table_name = $myPost->reset_my_custom_post_type_table_name();

    $wpdb->query( 
        $wpdb->prepare( 
            "UPDATE `$table_name` SET `post_name` = %s, `category_name` = %s, `tag_name` = %s WHERE id = %d", $post_name, $category_name, $tag_name, $id
        )
    );

    return new WP_REST_Response( "Your custom post type was changed successfully!" );

}
