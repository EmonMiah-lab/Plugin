<?php
    global $wpdb; 

    $myPost = new My_Custom_Post_Type();
    $table_name = $myPost->reset_my_custom_post_type_table_name();

   
    $post = $wpdb->get_results(
        "SELECT * FROM {$table_name} ORDER BY `id` DESC"
    );

   
    $post_name = !empty($post[0]->post_name) ? $post[0]->post_name:"" ;
    $category_name = !empty($post[0]->category_name) ? $post[0]->category_name:"" ;
    $tag_name = !empty($post[0]->tag_name) ? $post[0]->tag_name:"" ;
   

?>

<div class="container_box_my_custom_post_type">

       <h5>Setting Up A Cutom Post Type</h5>

       <form id="add_my_custom_post_type">
            <div class="message" style="display: none;"></div>
            <div class="error" style="display: none; color: red"></div>
            <div class="form_group">
                <Label>Post Type Name</Label>
                <input type="text" class="my_custom_post_type_name" name="my_custom_post_type_name" placeholder="Add a Custom post type name" value="<?php echo !empty($post[0]->post_name) ? $post[0]->post_name:"" ?>">
            </div>

            <div class="form_group">
                <Label>Post Type Category Name</Label>
                <input type="text" class="my_custom_post_type_category_name" name="my_custom_post_type_category_name" placeholder="Add a Custom post type Category name"  value="<?php echo !empty($post[0]->category_name) ? $post[0]->category_name:"" ?>">
            </div>

            <div class="form_group">
                <Label>Post Type Tag Name</Label>
                <input type="text" class="my_custom_post_type_tag_name" name="my_custom_post_type_tag_name" placeholder="Add a Custom post type Tag name"  value="<?php echo !empty($post[0]->tag_name) ? $post[0]->tag_name:"" ?>">
            </div>

            <input type="submit" class="add_my_custom_post_type_btn" value="Update">
       </form>
</div>

