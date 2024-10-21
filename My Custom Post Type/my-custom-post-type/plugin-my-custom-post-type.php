<?php
/**
 * Plugin Name:       My Custom Post Type
 * Description:       This Plugin will will be used to make a simple Custom Post Type for your Theme
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Emon Miah
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       my-custom-post-type
 */


if(!defined('ABSPATH')){
    die("You should not to be here!");
}

if(!class_exists('My_Custom_Post_Type')){

    class My_Custom_Post_Type{

        public function __construct()
        {
            if(!defined("MY_CUSTOM_POST_TYPE_PLUGIN_DIR_PATH")){
                define("MY_CUSTOM_POST_TYPE_PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));
            };

            if(!defined("MY_CUSTOM_POST_TYPE_PLUGIN_URL")){
                define("MY_CUSTOM_POST_TYPE_PLUGIN_URL", plugins_url()."/plugin-my-custom-post-type/");
            };


            add_action('init', [$this, 'my_custom_post_type_include_assets']);

            add_action('init', [$this, 'add_custom_post_type']);

            call_user_func_array([$this, 'initial_custom_func'], ['']);

            add_action( 'admin_menu', [$this, 'set_up_my_custom_post_type'] );

            register_activation_hook( __FILE__, [$this, 'activate_my_post_type_table'] );
            register_deactivation_hook( __FILE__, [$this, 'unactivate_my_post_type_table'] );

        }

        public function my_custom_post_type_include_assets(){

            $pages = ["my_custom_post_type"];

            $current_page = isset($_GET['page'])? $_GET['page'] :"";

            if(in_array($current_page, $pages)){

                wp_enqueue_style( "my_custom_post_type_main_css", MY_CUSTOM_POST_TYPE_PLUGIN_URL.'assets/css/my_custom_post_type.css?gh=abcdef', array(), '1.0.0', 'all' );

                wp_enqueue_script( "my_custom_post_type_main_js", MY_CUSTOM_POST_TYPE_PLUGIN_URL.'assets/js/my_custom_post_type.js?hg=abc', array('jquery'), '1.0.0', true );

                wp_localize_script( 'my_custom_post_type_main_js', 'rest_object',
                    array(
                        'resturl' => esc_url_raw(rest_url()),
                        'restnonce' => wp_create_nonce('wp_rest'),
                    )
                );

            }

        }



        public function set_up_my_custom_post_type(){
            add_menu_page(
                'My Custom Post Type',
                'My Custom Post Type',
                'manage_options',
                'my_custom_post_type',
                [$this, 'my_custom_post_type'],
                'dashicons-welcome-learn-more',
            );

        }


        public function my_custom_post_type(){
            include_once MY_CUSTOM_POST_TYPE_PLUGIN_DIR_PATH.'views/my-custom-post-type-input-form.php';
        }


        public function reset_my_custom_post_type_table_name(){
            global $wpdb;
            return $wpdb->prefix . "my_custom_post_type"; // wp_my_custom_post_type
        }


        public function activate_my_post_type_table(){

            global $wpdb;
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');


            if($wpdb->get_var('SHOW TABLES LIKE "'.$this->reset_my_custom_post_type_table_name().'"') == ""){

                $sql = "CREATE TABLE `".$this->reset_my_custom_post_type_table_name()."` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `post_name` text DEFAULT NULL,
                    `category_name` text DEFAULT NULL,
                    `tag_name` text DEFAULT NULL,
                    PRIMARY KEY (`id`)
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

                dbDelta( $sql );

            }

            $wpdb->query(
                $wpdb->prepare(
                    "INSERT INTO ". $this->reset_my_custom_post_type_table_name()." (`post_name`, `category_name`, `tag_name`) VALUES (%s, %s, %s)",  "News", "Categories", "Tags"
                )
            );

        }


/* <======== Deactivate Database Table =============> */

        public function unactivate_my_post_type_table(){
            global $wpdb;
            $wpdb->query("DROP TABLE IF EXISTS ".$this->reset_my_custom_post_type_table_name());

        }




/* <======== Add REST ROUTE Function =============> */

        public function initial_custom_func($request){

            include_once MY_CUSTOM_POST_TYPE_PLUGIN_DIR_PATH.'library/my-custom-post-type-rest-route.php';

        }

/* <======== Add My Custom Post Type =============> */

        public function  add_custom_post_type(){

            global $wpdb;

            $tablename = $this->reset_my_custom_post_type_table_name();

            $post = $wpdb->get_results(
                "SELECT * FROM {$tablename} ORDER BY `id` DESC"
            );

            $post_name = !empty($post[0]->post_name) ? $post[0]->post_name:"" ;
            $category_name = !empty($post[0]->category_name) ? $post[0]->category_name:"" ;
            $tag_name = !empty($post[0]->tag_name) ? $post[0]->tag_name:"" ;

            $singular = "";
            if(!empty($post_name)){
                $str_len = (int)strlen($post_name) - 1;
                if(strpos($post_name,"s",-$str_len) == $str_len){
                    $singular = rtrim($post_name,"s");
                }else{
                    $singular = $post_name;
                }
            }

            $labels = array(
                'name'                  => __( ucfirst($post_name)  , "my-custom-post-type"),
                'singular_name'         => __( ucfirst($singular) , "my-custom-post-type"),
                'menu_name'             => __( ucfirst($post_name) , "my-custom-post-type"),
                'name_admin_bar'        => __( ucfirst($post_name) , "my-custom-post-type"),
                'add_new'               => __( 'Add '.$singular, "my-custom-post-type" ),
                'add_new_item'          => __( 'Add new '.$singular, 'my-custom-post-type' ),
                'edit_item'             => __( 'Edit '.$singular, "my-custom-post-type"),
                'view_item'             => __( 'View '.$singular, "my-custom-post-type" ),
                'all_items'             => __( 'All '.$post_name, "my-custom-post-type" ),
                'search_items'          => __( 'Search '.$post_name, "my-custom-post-type" ),
                'parent_item_colon'     => __( 'Parent:'.$post_name, "my-custom-post-type" ),
                'not_found'             => __( 'No '.$post_name.' found.', "my-custom-post-type" ),

            );
            $args = array(
                'labels'             => $labels,
                'description'        => 'Add '.$post_name,
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => array( 'slug' => strtolower($post_name) ),
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => 60,
                'menu_icon'          => 'dashicons-welcome-learn-more',
                'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'  ),
                'taxonomies'         => array(),
                'show_in_rest'       => true
            );

            register_post_type(strtolower($post_name), $args );

            $args = array(
                'label'         => ucfirst($category_name),
                'public'        => true,
                'hierarchical'  => true,
                'query_var'     => true,
                'has_archive'   => true,
                'show_admin_column' => true,
                'show_in_menu' => true,
                "show_in_nav_menus" => true,
                'show_ui'           => true,
                "show_in_rest"  => true,
                'rewrite'       => array( 'slug' => strtolower($post_name)."_cat" ),

            );

            register_taxonomy(strtolower($post_name)."_cat", strtolower($post_name), $args);


            $args = array(
                'label'         => ucfirst($tag_name),
                'public'        => true,
                'hierarchical'  => false,
                'query_var'     => true,
                'has_archive'   => true,
                'show_admin_column' => true,
                'show_in_menu' => true,
                "show_in_nav_menus" => true,
                'show_ui'           => true,
                "show_in_rest"  => true,
                'rewrite'       => array( 'slug' => strtolower($post_name)."_tag" ),

            );

            register_taxonomy(strtolower($post_name)."_tag", strtolower($post_name), $args);
        }

    }

}

new My_Custom_Post_Type();




function my_custom_post_type_styles() {
    // Only enqueue the stylesheet on the front-end
    if ( is_singular( 'book' ) || is_post_type_archive( 'book' ) ) {
        wp_enqueue_style( 'my-custom-post-type-style', plugin_dir_url( __FILE__ ) . 'css/style.css' );
    }
}
add_action( 'wp_enqueue_scripts', 'my_custom_post_type_styles' );





function my_custom_post_type_admin_styles() {
    global $typenow;
    if ( $typenow === 'book' ) {
        wp_enqueue_style( 'my-custom-post-type-admin-style', plugin_dir_url( __FILE__ ) . 'css/admin-style.css' );
    }
}
add_action( 'admin_enqueue_scripts', 'my_custom_post_type_admin_styles' );
