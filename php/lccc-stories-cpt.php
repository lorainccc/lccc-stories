<?php

// Register Custom Post Type
function lc_stories_cpt() {

	$labels = array(
		'name'                  => _x( 'LCCC Stories', 'Post Type General Name', 'lorainccc' ),
		'singular_name'         => _x( 'LCCC Story', 'Post Type Singular Name', 'lorainccc' ),
		'menu_name'             => __( 'LCCC Stories', 'lorainccc' ),
		'name_admin_bar'        => __( 'LCCC Stories', 'lorainccc' ),
		'archives'              => __( 'Story Archives', 'lorainccc' ),
		'attributes'            => __( 'Story Attributes', 'lorainccc' ),
		'parent_item_colon'     => __( 'Parent Story:', 'lorainccc' ),
		'all_items'             => __( 'All Stories', 'lorainccc' ),
		'add_new_item'          => __( 'Add New Story', 'lorainccc' ),
		'add_new'               => __( 'Add New Story', 'lorainccc' ),
		'new_item'              => __( 'New Story', 'lorainccc' ),
		'edit_item'             => __( 'Edit Story', 'lorainccc' ),
		'update_item'           => __( 'Update Story', 'lorainccc' ),
		'view_item'             => __( 'View Story', 'lorainccc' ),
		'view_items'            => __( 'View Stories', 'lorainccc' ),
		'search_items'          => __( 'Search Stories', 'lorainccc' ),
		'not_found'             => __( 'Story Not found', 'lorainccc' ),
		'not_found_in_trash'    => __( 'Story Not found in Trash', 'lorainccc' ),
		'featured_image'        => __( 'Featured Image', 'lorainccc' ),
		'set_featured_image'    => __( 'Set featured image', 'lorainccc' ),
		'remove_featured_image' => __( 'Remove featured image', 'lorainccc' ),
		'use_featured_image'    => __( 'Use as featured image', 'lorainccc' ),
		'insert_into_item'      => __( 'Insert into story', 'lorainccc' ),
		'uploaded_to_this_item' => __( 'Uploaded to this story', 'lorainccc' ),
		'items_list'            => __( 'Stories list', 'lorainccc' ),
		'items_list_navigation' => __( 'Stories list navigation', 'lorainccc' ),
		'filter_items_list'     => __( 'Filter stories list', 'lorainccc' ),
	);
	$args = array(
		'label'                 => __( 'LCCC Story', 'lorainccc' ),
		'description'           => __( 'Collection of LCCC Stories', 'lorainccc' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'post-formats' ),
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-testimonial',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
		'show_in_rest'          => true,
        'rewrite'               => array(
            'slug'  => 'stories',
        )
	);
	register_post_type( 'lccc_stories', $args );

}
add_action( 'init', 'lc_stories_cpt', 0 );