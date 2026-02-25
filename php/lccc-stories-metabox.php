<?php

/*
 *	Code adapted from https://www.smashingmagazine.com/2011/10/create-custom-post-meta-boxes-wordpress
 *	Created Februaray 2026.
 *
 */

 /* Fire our meta box setup function on the post editor screen. */
add_action( 'load-post.php', 'lc_stories_meta_boxes_setup' );
add_action( 'load-post-new.php', 'lc_stories_meta_boxes_setup' );

/* Meta box setup function */
function lc_stories_meta_boxes_setup() {
 /* Add meta boxes on the 'add_meta_boxes' hook. */
 add_action( 'add_meta_boxes', 'lc_add_stories_meta_box' );

 /* Save post meta on the 'save_post' hook. */
 add_action( 'save_post', 'lc_stories_save_info', 10, 2 );
}

/* Create one or meta boxes to be displayed on the post editor screen */
function lc_add_stories_meta_box() {
 add_meta_box(
  'lc_stories_metabox',                                             // Unique ID (ID of Div Tag ** Note: DO NOT NAME same as field(s) below **)
  esc_html__( 'LCCC Stories Custom Fields', 'lorainccc' ),          // Title & Text Domain
  'lc_show_stories_meta_box',                                       // Callback function
  'lccc_stories',                                                   // Admin Page or Post Type
  'normal',                                                         // Context (Position)
  'default'                                                         // Priority
 );
}

/* Display the post meta box */
function lc_show_stories_meta_box( $object, $box ) { ?>

<?php wp_nonce_field( basename( __FILE__ ), 'lc_stories_post_nonce' ); ?>



<div style="display:block; width:95%; padding: 5px;">
    <h2>Story Post Banner</h2>
    <div style="display:inline-block; width:50%; margin: 3px">
    <label for="lc_stories_banner_bg_type_field">
            <?php _e( "Story Banner Background Type: ", "lorainccc" ); ?>
    </label>
    </div>
    <div style="display:inline-block; width:50%; margin: 3px">
        <input type="radio" id="image" name="lc_stories_banner_bg_type_field" value="image" <?php  ?> >
        <label for="image">Image</label>&nbsp;&nbsp;&nbsp;
        <input type="radio" id="video" name="lc_stories_banner_bg_type_field" value="video">
        <label for="video">Video</label>
    </div>
    <div id="lc_story_image_fields" style="display:none; width:95%; padding: 5px;">
        <h2>Image</h2>
            <div style="display:inline-block; width:100%; margin: 3px">    
                <button type="button" aria-haspopup="dialog" id="lc-image-selector" class="lc-components-button lc-media-selector is-next-40px-default-size">Select image</button>
                <div class='image-preview-wrapper' style="width: 220px; margin: 5px; text-align:center;">
                    <img id='lc-image-preview' src='<?php echo wp_get_attachment_url( get_option( 'lc_story_image_id' ) ); ?>' height='100'>
                </div>
            <input type='hidden' name='lc_image_attachment_id' id='image_attachment_id' value='<?php echo get_option( 'lc_story_image_id');?>'>
            <button type="button" id="lc-image-remove" class="lc-components-button lc-media-selector is-next-40px-default-size">Remove image</button>
        </div>
    </div>
    <div id="lc_story_video_fields" style="display:none; width:95%; padding: 5px;">
        <h2>Video</h2>
        <div style="display:inline-block; width:100%; margin: 3px">
            <button type="button" aria-haspopup="dialog" id="lc-video-selector" class="lc-components-button lc-media-selector is-next-40px-default-size">Select video</button>
            <div class='image-preview-wrapper' style="margin: 5px;">
                <img id='image-preview' src='<?php echo wp_get_attachment_url( get_option( 'lc_story_video_id' ) ); ?>' height='100'>
            </div>
            <input type='hidden' name='lc_video_attachment_id' id='video_attachment_id' value='<?php echo get_option( 'lc_story_video_id');?>' />
            <button type="button" id="lc-video-remove" class="lc-components-button lc-media-selector is-next-40px-default-size">Remove video</button>
        </div>
        <h2>Video Poster Image</h2>
        <div style="display:inline-block; width:100%; margin: 3px">    
            <button type="button" aria-haspopup="dialog" id="lc-poster-image-selector" class="lc-components-button lc-media-selector is-next-40px-default-size">Select image</button>
                <div class='image-preview-wrapper' style="width: 220px; margin: 5px; text-align:center;">
                <img id='lc-poster-image-preview' src='<?php echo wp_get_attachment_url( get_option( 'lc_poster_image_id' ) ); ?>' height='100'>
            </div>
            <input type='hidden' name='lc_poster_image_attachment_id' id='image_attachment_id' value='<?php echo get_option( 'lc_poster_image_id');?>' />
            <button type="button" id="lc-poster-image-remove" class="lc-components-button lc-media-selector is-next-40px-default-size">Remove image</button>
        </div>
    </div>
    <div style="width:95%; padding: 5px;">
        <label for="lc_stories_banner_vertical_align_field">
            <?php _e( "Vertical Alignment: ", "lorainccc" ); ?>
        </label>
        <input type="radio" id="top" name="lc_stories_banner_vertical_align_field" value="top" <?php  ?> >
        <label for="image">Top</label>&nbsp;&nbsp;&nbsp;
        <input type="radio" id="middle" name="lc_stories_banner_vertical_align_field" value="middle">
        <label for="video">Middle</label>&nbsp;&nbsp;&nbsp;
        <input type="radio" id="bottom" name="lc_stories_banner_vertical_align_field" value="bottom">
        <label for="video">Bottom</label>
    </div>
</div>
<div style="display:block; width:95%; padding: 5px;">
    <div id="lc_related_items_alert" class="lc_alert lc_error">
        <p>Related Post list cannot contain more than 3 items.</p> <a class="lc_close_button" href="#" id="lc_close_alert">
        <span class="material-symbols-outlined align-right">cancel</span></a>
    </div>
    <h2>Related Posts</h2>
    <div style="display:inline-block; width:48%; margin: 3px">
        <ul id="lc-post-list" class="lc-select">
            <?php

                $lc_stories_args = array(
                    'post_type'         => 'post',
                    'posts_per_page'    => -1,
                    'post_status'       => 'publish',
                );

                $lcposts = get_posts( $lc_stories_args );

                foreach ( $lcposts as $post ){
                    echo '<li data-id="' . $post->ID . '">' . $post->post_title . '</li>';
                };

            ?>
        </ul>
    </div>
    <div style="display:inline-block; width:48%; margin: 3px">
        <ul id="lc-related-list" class="lc-select">

        </ul>
        <input type="hidden" name="lc_related_post_list" id="lc_related_post_list" value='<?php echo get_option( 'lc_related_posts');?>' />
    </div>
</div>

<script>
jQuery(document).ready(function($){
    // This event handler is triggered when a radio button in the "option" group changes
    $('input[name="lc_stories_banner_bg_type_field"]').change(function(){
        if ($(this).val() == "image") {
            // If the "yes" radio button is checked, show the extra field
            $("#lc_story_image_fields").show();
            $("#lc_story_video_fields").hide();
        } else if ($(this).val() == "video") {
            // Otherwise, hide it
            $("#lc_story_image_fields").hide();
            $("#lc_story_video_fields").show();        
        
        } else {
            $("#lc_story_image_fields").hide();
            $("#lc_story_video_fields").hide();        
        };
        
        $('input[name="lc_stories_banner_bg_type_field"]:checked').change();
    }
);

    // Manually trigger the change event on page load
    // to set the initial visibility based on the default checked radio button
    $('input[name="option"]:checked').change();
});
</script>

<?php
}

/* Save the meta box's post metadata */
function lc_stories_save_info( $post_id, $post ) {

}

function update_meta_values( $post_id, $meta_key, $new_meta_value, $meta_value ) {

  /* If a new meta value was added and there was no previous value, add it. */
 if ( $new_meta_value && '' == $meta_value ){
   add_post_meta( $post_id, $meta_key, $new_meta_value, true );
   add_post_meta( $post_id, $meta_key, $new_meta_value, true );

  /* If the new meta value was added and there was no previous value, add it. */
   }elseif ( $new_meta_value && $new_meta_value != $meta_value ){
  update_post_meta( $post_id, $meta_key, $new_meta_value );

  /* If there is no new meta value but an old value exists, delete it. */
  }elseif ( '' == $new_meta_value && $meta_value ){
  delete_post_meta( $post_id, $meta_key, $meta_value );

  }
}