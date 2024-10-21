jQuery(document).ready(function($){

        
        $("#add_my_custom_post_type").on("submit",function(e){

            e.preventDefault();

            var post_type_name  = $(".my_custom_post_type_name").val();
            var category_name   = $(".my_custom_post_type_category_name").val();
            var tag_name   = $(".my_custom_post_type_tag_name").val();

            if(!post_type_name || !category_name || !tag_name){
                $(".error").html("Please correct input field!!!");
                $(".error").show();
                return false;
            }

            if(!confirm("Are You sure, You want to change existing CPT to new CPT ?")){
                    return false;
            }else{

                let formData = {
                    post_type_name: post_type_name,
                    category_name: category_name, 
                    tag_name: tag_name 
                }

                $.ajax({
                    method: 'POST',
                    url: rest_object.resturl + "setup_my_custom_post_type/v1/setup",
                    headers:{
                        'X-WP-Nonce':rest_object.restnonce
                    },
                    data: formData
                })
                .done(res=>{
                    $(".message").html(res);
                    $(".message").show();
                    $(".error").hide();
                    
                    setTimeout(function(){
                        $(".message").html("");
                        $(".message").hide();
                        window.location.reload();
                    },3000); 
                })

            }
        });

     
});

    

