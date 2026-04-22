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

    // Make the $post object a global variable so it can be used by the different sub functions.
    global $post;

	?>
    <div style="display:block; width:95%; float:left; border-bottom: 1px solid #696969; padding:10px 0; margin: 0 0 20px 0;">
        <img style="float:right;" src="<?php echo str_replace('/php/', '', plugin_dir_url( __FILE__ ))?>/assets/images/lccc-logo.png" border="0">
        <h1 style="float:left; padding: 20px 0 0 0;">Stories Conversion/Import</h1>
    </div>
    <div style="display:block; width:95%; float:left; padding:10px 0;">
<?php

$lc_preview_import = $_GET['lc_previewimport'];
$lc_stories_import = $_GET['lc_storiesimport'];
$lc_story_import = $_GET['lc_storyimport'];

if( empty( $lc_preview_import ) && empty( $lc_stories_import ) && empty( $lc_story_import )){
// List of Stories to Preview

    $lc_args = array(
        'post_type'         => 'post',
        'posts_per_page'    => -1,
        'post_status'       => 'publish',
    );

    $lcpreviewposts = get_posts( $lc_args );
    $lc_post_count = count($lcpreviewposts);

echo '<table class="wp-list-table widefat fixed striped table-view-list posts">';
echo '  <thead>';
echo '      <tr>';
echo '          <td class="column-cb check-column">&nbsp;</td>';
echo '          <th scope="col" class="column-title" id="title">Title</th>';
echo '          <th scope="col" class="column-date" id="date">Date</th>';
echo '          <th scope="col" class="column-modified" id="modified">Last Modified</th>';
echo '      </tr>';
echo '  </thead>';
echo '  <tbody>';

    for ($x = 0; $x <= $lc_post_count-1; $x++) {

        $lc_date = new DateTime($lcpreviewposts[$x]->post_date);
        $lc_mod_date = new DateTime($lcpreviewposts[$x]->post_modified);
        $lc_post_id = $lcpreviewposts[$x]->ID;

        echo '<tr>';
        echo '  <th class="check-column">&nbsp;</th>';
        echo '  <td class="title column-title has-row-actions column-primary page-title" data-name="Title"><strong>' . $lcpreviewposts[$x]->post_title . '</strong><a href="' . add_query_arg( 'lc_previewimport', $lc_post_id) . '">Preview Story</a> | <a href="' . add_query_arg( 'lc_storyimport', $lc_post_id) . '">Import Story</a></td>';
        echo '  <td class="date column-date" data-name="Date">Published<br/>' . $lc_date->format('Y/m/d g:s a') . '</td>';
        echo '  <td class="modified column-modified" data-name="Last Modified">' . $lc_mod_date->format('Y/m/d') . '</td>';
        echo '</tr>';
    }

echo '  </tbody>';
echo '</table>';
echo '</div>';

}elseif( !empty( $lc_preview_import ) ){

        $lc_args = array(
        'post_type'         => 'post',
        'p'                 => $lc_preview_import,
        'post_status'       => 'publish',
    );

    $lcpreview_post = get_posts( $lc_args );

    echo '<a href="https://lorainccc.lndo.site/stories/wp-admin/edit.php?post_type=lccc_stories&page=lc-stories-import">Return to Stories Import List</a><br/>';
    echo '<h2>Post Info</h2>';
    echo '<pre>';
    var_dump($lcpreview_post);
    echo '</pre>';

    $lc_flexible_content = get_field('content_options', $lc_preview_import);
    
    echo '<h2>ACF Flexible Content</h2>';
    echo '<pre>';
    var_dump($lc_flexible_content);
    echo '</div>';

}elseif ( !empty( $lc_story_import ) ){

    $lcimport_post = get_post( $lc_story_import );

    $lc_excerpt_content = get_field('post_intro_text', $lc_story_import);
    $lc_flexible_content = get_field('content_options', $lc_story_import);
    $lc_single_post_content = '';

        if( is_countable( $lc_flexible_content ) ){
        $lc_single_post_content .= '<!-- wp:lccc-foundation-blocks/lc-grid-container-block -->' . PHP_EOL;
        $lc_single_post_content .= '<div class="wp-block-lccc-foundation-blocks-lc-grid-container-block grid-container"><!-- wp:lccc-foundation-blocks/lc-grid-margin-block -->' . PHP_EOL;
        $lc_single_post_content .= '<div class="wp-block-lccc-foundation-blocks-lc-grid-margin-block grid-x grid-x-margin"><!-- wp:lccc-foundation-blocks/lc-cell-block -->' . PHP_EOL;
        $lc_single_post_content .= '<div class="wp-block-lccc-foundation-blocks-lc-cell-block cell">';

        if(count($lc_flexible_content) > 1){
            for($i = 0; $i < count($lc_flexible_content); $i++){

                switch($lc_flexible_content[$i]['acf_fc_layout']){

                case "content_block":
                    $lc_single_post_content .= '<!-- wp:paragraph -->' . PHP_EOL;
                    $lc_single_post_content .= '<p>' . strip_tags(str_replace("<p>", "", str_replace("</p>", "", $lc_flexible_content[$i]['content_column'])), '<a><p>') . '</p>' . PHP_EOL;
                    $lc_single_post_content .=  '<!-- /wp:paragraph -->' . PHP_EOL;
                    break;

                case "centered_content_block":
                    $lc_single_post_content .= '<!-- wp:paragraph -->' . PHP_EOL;
                    $lc_single_post_content .= '<p>' . strip_tags(str_replace("<p>", "", str_replace("</p>", "", $lc_flexible_content[$i]['centered_content_content'])), '<a><p>') . '</p>' . PHP_EOL;
                    $lc_single_post_content .=  '<!-- /wp:paragraph -->' . PHP_EOL;
                    break;
                case "image_video_spotlight":

                    if( $lc_flexible_content[$i]['spotlight_media_type'] == 'image' ){
                        $lc_spotlight_image = $lc_flexible_content[$i]['spotlight_image'];
                        
                        //echo '<pre>';
                        //var_dump($lc_spotlight_image);
                        //echo '</pre>';

                        $lc_single_post_content .=  '<!-- wp:image {"id":' . $lc_spotlight_image['ID'] . ',"sizeSlug":"full","linkDestination":"none"} -->' . PHP_EOL;
                        $lc_single_post_content .= '<figure class="wp-block-image size-full"><img src="' . $lc_spotlight_image['url'] . '" alt="' . $lc_spotlight_image['alt'] . '" class="wp-image-' . $lc_spotlight_image['ID'] .  '" /></figure>' . PHP_EOL;
                        $lc_single_post_content .=  '<!-- /wp:image -->' . PHP_EOL;
                    }

                    break;

                case "full_width_section_header":
                    
                    switch($lc_flexible_content[$i]['full_width_header_type']){
                        
                        case "h2":
                            $lc_single_post_content .= '</div>' . PHP_EOL;
                            $lc_single_post_content .= '<!-- /wp:lccc-foundation-blocks/lc-cell-block --></div>' . PHP_EOL;
                            $lc_single_post_content .= '<!-- /wp:lccc-foundation-blocks/lc-grid-margin-block --></div>' . PHP_EOL;            
                            $lc_single_post_content .= '<!-- /wp:lccc-foundation-blocks/lc-grid-container-block -->' . PHP_EOL;

                            $lc_single_post_content .= '<!-- wp:lccc-foundation-blocks/lc-full-width-block -->' . PHP_EOL;
                            $lc_single_post_content .= '<div class="wp-block-lccc-foundation-blocks-lc-full-width-block lc-full-row">' . PHP_EOL;

                            $lc_single_post_content .=  '<!-- wp:heading -->' . PHP_EOL;
                            $lc_single_post_content .=  '<h2 class="wp-block-heading">' . $lc_flexible_content[$i]['full_width_header'] . '</h2>' . PHP_EOL;
                            $lc_single_post_content .=  '<!-- /wp:heading -->' . PHP_EOL;

                            $lc_single_post_content .= '</div>' . PHP_EOL;
                            $lc_single_post_content .= '<!-- /wp:lccc-foundation-blocks/lc-full-width-block -->' . PHP_EOL;                                                        

                            $lc_single_post_content .= '<!-- wp:lccc-foundation-blocks/lc-grid-container-block -->' . PHP_EOL;
                            $lc_single_post_content .= '<div class="wp-block-lccc-foundation-blocks-lc-grid-container-block grid-container"><!-- wp:lccc-foundation-blocks/lc-grid-margin-block -->' . PHP_EOL;
                            $lc_single_post_content .= '<div class="wp-block-lccc-foundation-blocks-lc-grid-margin-block grid-x grid-x-margin"><!-- wp:lccc-foundation-blocks/lc-cell-block -->' . PHP_EOL;
                            $lc_single_post_content .= '<div class="wp-block-lccc-foundation-blocks-lc-cell-block cell">' . PHP_EOL;

                        break;

                        case "h3":
                            $lc_single_post_content .= '</div>' . PHP_EOL;
                            $lc_single_post_content .= '<!-- /wp:lccc-foundation-blocks/lc-cell-block --></div>' . PHP_EOL;
                            $lc_single_post_content .= '<!-- /wp:lccc-foundation-blocks/lc-grid-margin-block --></div>' . PHP_EOL;            
                            $lc_single_post_content .= '<!-- /wp:lccc-foundation-blocks/lc-grid-container-block -->' . PHP_EOL;

                            $lc_single_post_content .= '<!-- wp:lccc-foundation-blocks/lc-full-width-block -->' . PHP_EOL;
                            $lc_single_post_content .= '<div class="wp-block-lccc-foundation-blocks-lc-full-width-block lc-full-row">' . PHP_EOL;

                            $lc_single_post_content .=  '<!-- wp:heading {"level":3} -->' . PHP_EOL;
                            $lc_single_post_content .=  '<h3>' . $lc_flexible_content[$i]['full_width_header'] . '</h3>' . PHP_EOL;
                            $lc_single_post_content .=  '<!-- /wp:heading -->' . PHP_EOL;

                            $lc_single_post_content .= '</div>' . PHP_EOL;
                            $lc_single_post_content .= '<!-- /wp:lccc-foundation-blocks/lc-full-width-block -->' . PHP_EOL;                                                        

                            $lc_single_post_content .= '<!-- wp:lccc-foundation-blocks/lc-grid-container-block -->' . PHP_EOL;
                            $lc_single_post_content .= '<div class="wp-block-lccc-foundation-blocks-lc-grid-container-block grid-container"><!-- wp:lccc-foundation-blocks/lc-grid-margin-block -->' . PHP_EOL;
                            $lc_single_post_content .= '<div class="wp-block-lccc-foundation-blocks-lc-grid-margin-block grid-x grid-x-margin"><!-- wp:lccc-foundation-blocks/lc-cell-block -->' . PHP_EOL;
                            $lc_single_post_content .= '<div class="wp-block-lccc-foundation-blocks-lc-cell-block cell">' . PHP_EOL;

                         break;
                    }
                    
                    break;                
                }
            }



        }else{
            //echo $lc_flexible_content[0]['content_column'];
        }
        $lc_single_post_content .= '</div>' . PHP_EOL;
        $lc_single_post_content .= '<!-- /wp:lccc-foundation-blocks/lc-cell-block --></div>' . PHP_EOL;
        $lc_single_post_content .= '<!-- /wp:lccc-foundation-blocks/lc-grid-margin-block --></div>' . PHP_EOL;            
        $lc_single_post_content .= '<!-- /wp:lccc-foundation-blocks/lc-grid-container-block -->' . PHP_EOL;
    }

    $lc_single_post = array(
        'menu_order' => $lcimport_post->menu_order,
        'comment_status' => ( $lcimport_post->comment_status == 'open' ? 'open' : 'closed' ),
        'ping_status' => ( $lcimport_post->ping_status == 'open' ? 'open' : 'closed' ),
        'post_author' => $lcimport_post->post_author,
        'post_category' => ( isset( $lcimport_post->post_category ) ? $lcimport_post->post_category : array() ),
        'tax_input'=> ( isset( $lcimport_post->tax_input ) ? $lcimport_post->tax_input : array() ),
        'post_name' => $lcimport_post->post_name,
        'post_content' => $lc_single_post_content,
        'post_excerpt' => $lc_excerpt_content,
        'post_parent' => $lcimport_post->parent_id,
        'post_password' => $lcimport_post->post_password,
        'post_status' => 'draft',
        'post_title' => $lcimport_post->post_title,
        'post_type' => 'lccc_stories',
        'post_date'     => $lcimport_post->post_date,
        'post_date_gmt' => $lcimport_post->post_date_gmt,
        'tags_input' => ( isset( $lcimport_post->tags_input ) ? $lcimport_post->tags_input : array() ),
        'page_template' => 'default',
        'thumbnail' => $lcimport_post->thumbnail   
    );

    // Insert Post into Database
    $lc_newId = wp_insert_post($lc_single_post);
    // Add Post Meta from post type
    
    $lc_fields_to_exclude = array('post_banner_image', '_post_banner_image', 'post_banner_background_type', '_post_banner_background_type', 'banner_vertical_positioning', '_banner_vertical_positioning', 'post_intro_text', '_post_intro_text', 'related_posts', '_related_posts');

    $custom = get_post_custom($lcimport_post->ID);
    foreach ($custom as $ckey => $cvalue) {
        if ( $ckey != '_edit_lock' && $ckey != '_edit_last' && $ckey != '_lc_publishedId' && !in_array( $ckey, $lc_fields_to_exclude )) {
            if (!str_starts_with($ckey, 'content_options') && !str_starts_with($ckey, '_content_options')){
                foreach ($cvalue as $mvalue) {
                    add_post_meta($lc_newId, $ckey, $mvalue, true);
                }
            }
        }
    } 

    // LCCC Field: lc_stories_banner_bg_type_field | image : video                   ACF Field: post_banner_background_type
    $lc_bg_type = get_post_meta($lcimport_post->ID, 'post_banner_background_type', false);
    add_post_meta($lc_newId, 'lc_stories_banner_bg_type_field', $lc_bg_type);

    if( $lc_bg_type == "image" ){

        // LCCC Field: lc_image_attachment_id                                        ACF Field: post_banner_image
        $lc_image_attach_id = get_post_meta($lcimport_post->ID, 'post_banner_image', false);
        add_post_meta($lc_newId, 'lc_image_attachment_id', $lc_image_attach_id);
    
        //Video BG not being used.
    }elseif( $lc_bg_type == "video" ){

        // LCCC Field: lc_video_attachment_id                                        ACF Field: video_bg
        $lc_video_attach_id = get_post_meta($lcimport_post->ID, 'video_bg', false);
        add_post_meta($lc_newId, 'lc_video_attachment_id', $lc_video_attach_id);

        // LCCC Field: lc_poster_image_attachment_id                                 ACF Field: video_poster_image
        $lc_video_poster_image_id = get_post_meta($lcimport_post->ID, 'video_poster_image', false);
        add_post_meta($lc_newId, 'lc_poster_image_attachment_id', $lc_video_poster_image_id);
    }

    // LCCC Field: lc_stories_banner_vertical_align_field | top : middle : bottom    ACF Field: banner_vertical_positioning
    $lc_bg_position = get_post_meta($lcimport_post->ID, 'banner_vertical_positioning', false);
    add_post_meta($lc_newId, 'lc_stories_banner_vertical_align_field', $lc_bg_position);

    //Transfer ACF Related Posts field to our Custom Field
    $lc_related_posts = get_post_meta($lcimport_post->ID, 'related_posts', false);
    $lc_new_related_posts = [];

    if(count($lc_related_posts) > 0){
        
        //echo "<pre>";
        //var_dump($lc_related_posts);
        //echo "</pre>";

        for($i = 0; $i < count($lc_related_posts); $i++){
             array_push($lc_new_related_posts, $lc_related_posts[$i]);
        }
        
        //echo "<pre>";
        //var_dump($lc_new_related_posts);
        //echo "</pre>";

    }
    add_post_meta($lc_newId, 'lc_related_post_list', $lc_new_related_posts);

    // Retain former Stories Post ID
    add_post_meta($lc_newId, '_lc_story_post_id', $lc_story_import);

    echo 'Post Imported - <a href="post.php?post=' . $lc_newId . '&action=edit">Edit - ' . $lcimport_post->post_title . '</a>';

}elseif( !empty( $lc_stories_import ) ){

//Begin All Stories Import

    $lc_args = array(
        'post_type'         => 'post',
        'p'                 => 4996,
        //'posts_per_page'    => -1,
        'post_status'       => 'publish',
    );

    // Stores Post ID and Title of what was imported.
    $lc_imported_posts = '';

    $lcposts = get_posts( $lc_args );

    foreach ( $lcposts as $post ){

    $lc_excerpt_content = get_field('post_intro_text');

    $lc_flexible_content = get_field('content_options');

    $lc_post_content = '';

    //echo '<pre>';
    //var_dump($lc_flexible_content);
    //echo '</pre>';

    if( is_countable( $lc_flexible_content ) ){
        $lc_post_content .= '<!-- wp:lccc-foundation-blocks/lc-grid-container-block -->' . PHP_EOL;
        $lc_post_content .= '<div class="wp-block-lccc-foundation-blocks-lc-grid-container-block grid-container"><!-- wp:lccc-foundation-blocks/lc-grid-margin-block -->' . PHP_EOL;
        $lc_post_content .= '<div class="wp-block-lccc-foundation-blocks-lc-grid-margin-block grid-x grid-x-margin"><!-- wp:lccc-foundation-blocks/lc-cell-block -->' . PHP_EOL;
        $lc_post_content .= '<div class="wp-block-lccc-foundation-blocks-lc-cell-block cell">';

        if(count($lc_flexible_content) > 1){
            for($i = 0; $i < count($lc_flexible_content); $i++){

                switch($lc_flexible_content[$i]['acf_fc_layout']){

                case "content_block":
                    $lc_post_content .= '<!-- wp:paragraph -->' . PHP_EOL;
                    $lc_post_content .= '<p>' . strip_tags(str_replace("<p>", "", str_replace("</p>", "", $lc_flexible_content[$i]['content_column'])), '<a><p>') . '</p>' . PHP_EOL;
                    $lc_post_content .=  '<!-- /wp:paragraph -->' . PHP_EOL;
                    break;

                case "centered_content_block":
                    $lc_post_content .= '<!-- wp:paragraph -->' . PHP_EOL;
                    $lc_post_content .= '<p>' . strip_tags(str_replace("<p>", "", str_replace("</p>", "", $lc_flexible_content[$i]['centered_content_content'])), '<a><p>') . '</p>' . PHP_EOL;
                    $lc_post_content .=  '<!-- /wp:paragraph -->' . PHP_EOL;
                    break;
                case "image_video_spotlight":

                    if( $lc_flexible_content[$i]['spotlight_media_type'] == 'image' ){
                        $lc_spotlight_image = $lc_flexible_content[$i]['spotlight_image'];
                        
                        //echo '<pre>';
                        //var_dump($lc_spotlight_image);
                        //echo '</pre>';

                        $lc_post_content .=  '<!-- wp:image {"id":' . $lc_spotlight_image['ID'] . ',"sizeSlug":"full","linkDestination":"none"} -->' . PHP_EOL;
                        $lc_post_content .= '<figure class="wp-block-image size-full"><img src="' . $lc_spotlight_image['url'] . '" alt="' . $lc_spotlight_image['alt'] . '" class="wp-image-' . $lc_spotlight_image['ID'] .  '" /></figure>' . PHP_EOL;
                        $lc_post_content .=  '<!-- /wp:image -->' . PHP_EOL;
                    }

                    break;

                case "full_width_section_header":
                    
                    switch($lc_flexible_content[$i]['full_width_header_type']){
                        
                        case "h2":
                            $lc_post_content .= '</div>' . PHP_EOL;
                            $lc_post_content .= '<!-- /wp:lccc-foundation-blocks/lc-cell-block --></div>' . PHP_EOL;
                            $lc_post_content .= '<!-- /wp:lccc-foundation-blocks/lc-grid-margin-block --></div>' . PHP_EOL;            
                            $lc_post_content .= '<!-- /wp:lccc-foundation-blocks/lc-grid-container-block -->' . PHP_EOL;

                            $lc_post_content .= '<!-- wp:lccc-foundation-blocks/lc-full-width-block -->' . PHP_EOL;
                            $lc_post_content .= '<div class="wp-block-lccc-foundation-blocks-lc-full-width-block lc-full-row">' . PHP_EOL;

                            $lc_post_content .=  '<!-- wp:heading -->' . PHP_EOL;
                            $lc_post_content .=  '<h2 class="wp-block-heading">' . $lc_flexible_content[$i]['full_width_header'] . '</h2>' . PHP_EOL;
                            $lc_post_content .=  '<!-- /wp:heading -->' . PHP_EOL;

                            $lc_post_content .= '</div>' . PHP_EOL;
                            $lc_post_content .= '<!-- /wp:lccc-foundation-blocks/lc-full-width-block -->' . PHP_EOL;                                                        

                            $lc_post_content .= '<!-- wp:lccc-foundation-blocks/lc-grid-container-block -->' . PHP_EOL;
                            $lc_post_content .= '<div class="wp-block-lccc-foundation-blocks-lc-grid-container-block grid-container"><!-- wp:lccc-foundation-blocks/lc-grid-margin-block -->' . PHP_EOL;
                            $lc_post_content .= '<div class="wp-block-lccc-foundation-blocks-lc-grid-margin-block grid-x grid-x-margin"><!-- wp:lccc-foundation-blocks/lc-cell-block -->' . PHP_EOL;
                            $lc_post_content .= '<div class="wp-block-lccc-foundation-blocks-lc-cell-block cell">' . PHP_EOL;

                        break;

                        case "h3":
                            $lc_post_content .= '</div>' . PHP_EOL;
                            $lc_post_content .= '<!-- /wp:lccc-foundation-blocks/lc-cell-block --></div>' . PHP_EOL;
                            $lc_post_content .= '<!-- /wp:lccc-foundation-blocks/lc-grid-margin-block --></div>' . PHP_EOL;            
                            $lc_post_content .= '<!-- /wp:lccc-foundation-blocks/lc-grid-container-block -->' . PHP_EOL;

                            $lc_post_content .= '<!-- wp:lccc-foundation-blocks/lc-full-width-block -->' . PHP_EOL;
                            $lc_post_content .= '<div class="wp-block-lccc-foundation-blocks-lc-full-width-block lc-full-row">' . PHP_EOL;

                            $lc_post_content .=  '<!-- wp:heading {"level":3} -->' . PHP_EOL;
                            $lc_post_content .=  '<h3>' . $lc_flexible_content[$i]['full_width_header'] . '</h3>' . PHP_EOL;
                            $lc_post_content .=  '<!-- /wp:heading -->' . PHP_EOL;

                            $lc_post_content .= '</div>' . PHP_EOL;
                            $lc_post_content .= '<!-- /wp:lccc-foundation-blocks/lc-full-width-block -->' . PHP_EOL;                                                        

                            $lc_post_content .= '<!-- wp:lccc-foundation-blocks/lc-grid-container-block -->' . PHP_EOL;
                            $lc_post_content .= '<div class="wp-block-lccc-foundation-blocks-lc-grid-container-block grid-container"><!-- wp:lccc-foundation-blocks/lc-grid-margin-block -->' . PHP_EOL;
                            $lc_post_content .= '<div class="wp-block-lccc-foundation-blocks-lc-grid-margin-block grid-x grid-x-margin"><!-- wp:lccc-foundation-blocks/lc-cell-block -->' . PHP_EOL;
                            $lc_post_content .= '<div class="wp-block-lccc-foundation-blocks-lc-cell-block cell">' . PHP_EOL;

                         break;
                    }
                    
                    break;                
                }
            }



        }else{
            //echo $lc_flexible_content[0]['content_column'];
        }
        $lc_post_content .= '</div>' . PHP_EOL;
        $lc_post_content .= '<!-- /wp:lccc-foundation-blocks/lc-cell-block --></div>' . PHP_EOL;
        $lc_post_content .= '<!-- /wp:lccc-foundation-blocks/lc-grid-margin-block --></div>' . PHP_EOL;            
        $lc_post_content .= '<!-- /wp:lccc-foundation-blocks/lc-grid-container-block -->' . PHP_EOL;
    }



    $lc_StoryPost = array(
        'menu_order' => $post->menu_order,
        'comment_status' => ( $post->comment_status == 'open' ? 'open' : 'closed' ),
        'ping_status' => ( $post->ping_status == 'open' ? 'open' : 'closed' ),
        'post_author' => $post->post_author,
        'post_category' => ( isset( $post->post_category ) ? $post->post_category : array() ),
        'tax_input'=> ( isset( $post->tax_input ) ? $post->tax_input : array() ),
        'post_name' => $post->post_name,
        'post_content' => $lc_post_content,
        'post_excerpt' => $lc_excerpt_content,
        'post_parent' => $post->parent_id,
        'post_password' => $post->post_password,
        'post_status' => 'draft',
        'post_title' => $post->post_title,
        'post_type' => 'lccc_stories',
        'post_date'     => $post->post_date,
        'post_date_gmt' => $post->post_date_gmt,
        'tags_input' => ( isset( $post->tags_input ) ? $post->tags_input : array() ),
        'page_template' => 'default',
        'thumbnail' => $post->thumbnail   
    );

    // Insert Post into Database
    $lc_newId = wp_insert_post($lc_StoryPost);


    // Add Post Meta from post type
    
    $lc_fields_to_exclude = array('post_banner_image', '_post_banner_image', 'post_banner_background_type', '_post_banner_background_type', 'banner_vertical_positioning', '_banner_vertical_positioning', 'post_intro_text', '_post_intro_text', 'related_posts', '_related_posts');

    $custom = get_post_custom($post->ID);
    foreach ($custom as $ckey => $cvalue) {
        if ( $ckey != '_edit_lock' && $ckey != '_edit_last' && $ckey != '_lc_publishedId' && !in_array( $ckey, $lc_fields_to_exclude )) {
            if (!str_starts_with($ckey, 'content_options') && !str_starts_with($ckey, '_content_options')){
                foreach ($cvalue as $mvalue) {
                    add_post_meta($lc_newId, $ckey, $mvalue, true);
                }
            }
        }
    } 

    /*
        

        

        
        

        
    */

    // LCCC Field: lc_stories_banner_bg_type_field | image : video                   ACF Field: post_banner_background_type
    $lc_bg_type = get_post_meta($post->ID, 'post_banner_background_type', false);
    add_post_meta($lc_newId, 'lc_stories_banner_bg_type_field', $lc_bg_type);

    if( $lc_bg_type == "image" ){

        // LCCC Field: lc_image_attachment_id                                        ACF Field: post_banner_image
        $lc_image_attach_id = get_post_meta($post->ID, 'post_banner_image', false);
        add_post_meta($lc_newId, 'lc_image_attachment_id', $lc_image_attach_id);
    
        //Video BG not being used.
    }elseif( $lc_bg_type == "video" ){

        // LCCC Field: lc_video_attachment_id                                        ACF Field: video_bg
        $lc_video_attach_id = get_post_meta($post->ID, 'video_bg', false);
        add_post_meta($lc_newId, 'lc_video_attachment_id', $lc_video_attach_id);

        // LCCC Field: lc_poster_image_attachment_id                                 ACF Field: video_poster_image
        $lc_video_poster_image_id = get_post_meta($post->ID, 'video_poster_image', false);
        add_post_meta($lc_newId, 'lc_poster_image_attachment_id', $lc_video_poster_image_id);
    }

    // LCCC Field: lc_stories_banner_vertical_align_field | top : middle : bottom    ACF Field: banner_vertical_positioning
    $lc_bg_position = get_post_meta($post->ID, 'banner_vertical_positioning', false);
    add_post_meta($lc_newId, 'lc_stories_banner_vertical_align_field', $lc_bg_position);
    
    //Transfer ACF Related Posts field to our Custom Field
    $lc_related_posts = get_post_meta($post->ID, 'related_posts', false);
    $lc_new_related_posts = [];

    if(count($lc_related_posts) > 0){
        
        //echo "<pre>";
        //var_dump($lc_related_posts);
        //echo "</pre>";

        for($i = 0; $i < count($lc_related_posts); $i++){
             array_push($lc_new_related_posts, $lc_related_posts[$i]);
        }
        
        //echo "<pre>";
        //var_dump($lc_new_related_posts);
        //echo "</pre>";

    }
    add_post_meta($lc_newId, 'lc_related_post_list', $lc_new_related_posts);

    // Retain former Stories Post ID
    add_post_meta($lc_newId, '_lc_story_post_id', $post->ID);


    //echo '<textarea id="debug" rows="5" cols="300">';
    //echo $lc_post_content;
    //echo '</textarea>'; 

    $lc_imported_posts .= $post->ID . ' | ' . $post->post_title . '<br/>';
        
    }

    wp_reset_postdata();
    
    echo '<h2>Posts Imported</h2>';
    echo '<p>';
    echo $lc_imported_posts;
    echo '</p>';
    echo '</div>';
    }
}
echo '</div>';