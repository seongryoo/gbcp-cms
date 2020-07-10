<?php

// Register opportunities post type
function gbcp_register_opp() {
  $labels = array(
    'name'                    => 'Opportunities',
    'singular_name'           => 'Opportunity',
    'menu_name'               => 'Opportunities',
    'name_admin_bar'          => 'Opportunity',
    'add_new'                 => 'Add New',
    'add_new_item'            => 'Add New Opportunity',
    'new_item'                => 'New Opportunity',
    'edit_item'               => 'Edit Opportunity',
    'view_item'               => 'View Opportunity',
    'all_items'               => 'All Opportunities',
    'search_items'            => 'Search Opportunities',
    'not_found'               => 'No opportunities found.',
    'not_found_in_trash'      => 'No opportunities found in Trash.',
    'archives'                => 'Opportunity archives',
    'filter_items_list'       => 'Filter opportunities list',
    'items_list_navigation'   => 'Opportunities list navigation',
    'items_list'              => 'Opportunities list',
  );

  $args = array(
    'labels'                  => $labels,
    'public'                  => true,
    'menu_icon'               => 'dashicons-welcome-learn-more',
    'show_in_rest'            => true,
    'publicly_queryable'      => true,
  );

  register_post_type( 'post_opp', $args );

  $supports = array(
    'custom-fields',
  );

  add_post_type_support( 'post_opp', $supports );
}
add_action( 'init', 'gbcp_register_opp' );

// Register custom meta
function gbcp_register_opp_meta() {
  // For meta fields which involve typed responses & stringable data
  $string_args = array(
    'show_in_rest'            => true,
    'single'                  => true,
    'type'                    => 'string',
  );
  // Data to be stored as meta field
  // 1. Opportunity title (call-to-action)
  //    - String
  // 2. Opportunity description
  //    - String
  // 3. Commitment description
  //    - String
  // 4. Location description
  //    - String
  // 5. Expiration date
  //    - String
  //    
  // Data to be stored as taxonomy
  // - Activity type(s)
  //    - This will be a taxonomy.
  // - Commitment level(s)
  //    - This will be a taxonomy
  // - Location tag(s)
  //    - This will be a taxonomy
  
  $string_fields = array(
    'post_opp_meta_title',
    'post_opp_meta_desc',
    'post_opp_meta_level',
    'post_opp_meta_loc',
    'post_opp_meta_expr',
  );

  foreach( $string_fields as $meta_name ) {
    register_post_meta( 'post_opp', $meta_name, $string_args );
  }
}
add_action( 'init', 'gbcp_register_opp_meta' );

function gbcp_register_opp_taxonomies() {
  $activities_labels = array(
    'name'                    => 'Activity Types',
    'singular_name'           => 'Activity Type',
    'search_items'            => 'Search Activity Types',
    'all_items'               => 'All Activity Types',
    'edit_item'               => 'Edit Activity Type',
    'update_item'             => 'Update Activity Type',
    'add_new_item'            => 'Add New Activity Type',
    'new_item_name'           => 'New Activity Type Name',
    'menu_name'               => 'Activity Types',
  );

  $activities_slugs = array(
    'slug'                    => 'type',
    'with_front'              => false,
    'hierarchical'            => false,
  );

  $activities_args = array(
    'hierarchical'            => false,
    'labels'                  => $activities_labels,
    'rewrite'                 => $activities_slugs,
  );

  register_taxonomy( 'type', 'post_opp', $activities_args );
}
add_action( 'init', 'gbcp_register_opp_taxonomies', 0 );