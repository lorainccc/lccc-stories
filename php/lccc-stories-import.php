<?php

function lc_add_stories_import_menu_page() {

  add_submenu_page(
  'edit.php?post_type=lccc_stories',									        // Parent Slug (Page to nest under)
  __( 'Stories Import', 'lorainccc' ),                      	                // Page Title
  'Stories Import',                                                      	    // Menu Title
  'edit_pages',                                                      	        // Capabilities
  'lc-stories-import',                                                     	    // Menu Slug
  'lc_stories_import'                                                     	    // Function
 );
}

add_action( 'admin_menu', 'lc_add_stories_import_menu_page' );

// Render out Page Templates List

function lc_stories_import(){
	?>
    <div style="display:block; width:95%; float:left; border-bottom: 1px solid #696969; padding:10px 0; margin: 0 0 20px 0;">
        <img style="float:right;" src="<?php echo str_replace('/php/', '', plugin_dir_url( __FILE__ ))?>/assets/images/lccc-logo.png" border="0">
        <h1 style="float:left; padding: 20px 0 0 0;">Stories Import</h1>
    </div>
    <div style="display:block; width:95%; float:left; padding:10px 0;">
<?php

//Begin Story Import

    global $post;

    $lc_args = array(
        'post_type'         => 'post',
        'p'                => 4788,
        'posts_per_page'    => 1,
        'post_status'       => 'publish',
    );

    $lcposts = get_posts( $lc_args );

    foreach ( $lcposts as $post ){

    echo "<pre>";

        echo "Title: " . $post->post_title . '<br/>';

        $lc_excerpt_content = get_field('post_intro_text');

        echo "Excerpt: " . $lc_excerpt_content. '<br/>';

        $lc_flexible_content = get_field('content_options');

        // echo '<pre>';
        // var_dump($lc_flexible_content);
        // echo '</pre>';

        if( is_countable( $lc_flexible_content ) ){
            if(count($lc_flexible_content) > 1){
                for($i = 0; $i < count($lc_flexible_content); $i++){
                    switch($lc_flexible_content[$i]['acf_fc_layout']){

                    case "content_block":
                        echo '<!-- wp:paragraph -->';
                        echo '<p>' . str_replace("<p>", "", str_replace("</p>", "", $lc_flexible_content[$i]['content_column'])) . '</p>';
                        echo '<!-- /wp:paragraph -->';
                        break;

                    case "image_video_spotlight":

                        if($lc_flexible_content[$i]['spotlight_media_type'] == 'video' ){
                            echo $lc_flexible_content[$i]['spotlight_video_embed'];
                        }

                        if( $lc_flexible_content[$i]['spotlight_media_type'] == 'image' ){
                            echo '<!-- wp:image -->';
                            echo $lc_flexible_content[$i]['spotlight_image'];

                            echo '<!-- /wp:image -->';
                        }

                        break;

                    case "full_width_section_header":
                        
                        switch($lc_flexible_content[$i]['full_width_header_type']){
                            
                            case "h2":
                                echo '<!-- wp:heading -->';
                                echo '<h2 class="wp-block-heading">' . $lc_flexible_content[$i]['full_width_header'] . '</h2>';
                                echo '<!-- /wp:heading -->';
                            break;

                            case "h3":
                                echo '<!-- wp:heading {"level":3} -->';
                                echo '<h3>' . $lc_flexible_content[$i]['full_width_header'] . '</h3>';
                                echo '<!-- /wp:heading -->';
                            break;
                        }
                        
                        break;                
                    }
                }
            }else{
                echo $lc_flexible_content[0]['content_column'];
            }
        echo "</pre>";
        }



        
    }

    wp_reset_postdata();
    
    echo '</div>';
};

