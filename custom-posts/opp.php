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
  // 2. Opportunity description
  // 3. Commitment description
  // 4. Location description
  // 5. Expiration date
  
  $string_fields = array(
    'post_opp_meta_will_expire',
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

function gbcp_register_opp_data_block_template() {
  $opp_object = get_post_type_object( 'post_opp' );
  $opp_object->template = array(
    array( 'gbcp/opp-data' ),
  );
  $opp_object->template_lock = 'all';
}
add_action( 'init', 'gbcp_register_opp_data_block_template' );

function gbcp_flush_opp() {
  gbcp_register_opp();
  gbcp_register_opp_meta();
  flush_rewrite_rules();
}
register_activation_hook( PLUGIN_FILE_URL, 'gbcp_flush_opp' );

function gbcp_deflush_opp() {
  flush_rewrite_rules();
}
register_deactivation_hook( PLUGIN_FILE_URL, 'gbcp_deflush_opp' );