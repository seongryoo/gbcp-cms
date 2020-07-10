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

// Register the activity type taxonomy
function gbcp_register_opp_taxon_type() {
  $activities_labels = array(
    'name'                    => 'Activity Types',
    'singular_name'           => 'Activity Type',
    'search_items'            => 'Search Activity Types',
    'all_items'               => 'All Activity Types',
    'edit_item'               => 'Edit Activity Type',
    'update_item'             => 'Update Activity Type',
    'add_new_item'            => 'Add New Activity Type',
    'new_item_name'           => 'New Activity Type Name',
    'menu_name'               => 'Type',
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


  // Only adds defaults if there are no terms
  if ( ! has_term( '', 'type' ) ) {
    $defaults = array(
      // 'On campus'               => array(
      //   'description'             => 'On campus at Georgia Tech',
      //   'slug'                    => 'loc-on-campus',
      // ),
      'Academics'             => array(
        'description'           => 'Opportunities relating to academic study, curricula, etc.',
        'slug'                  => 'type-aca',
      ),
      'Volunteering'          => array(
        'description'           => 'Service opportunities',
        'slug'                  => 'type-vol',
      ),
      'Recreation'            => array(
        'description'           => 'Opportunities which involve recreational activities for participants',
        'slug'                  => 'type-rec',
      ),
      'Events'                => array(
        'description'           => 'Activities which allow for individuals to participate as attendants, such as conferences, seminars, informational sessions, etc.',
        'slug'                  => 'type-eve',
      ),
      'Community-focused'     => array(
        'description'           => 'Opportunities which have an active goal of uplifting a specific community',
        'slug'                  => 'type-com',
      ),
      'Leadership'            => array(
        'description'           => 'e.g. student-led committees or student organization leadership opportunities',
        'slug'                  => 'type-lea',
      ),
    );

    foreach( $defaults as $term => $args ) {
      if ( term_exists( $term ) == null ) {
        wp_insert_term( $term, 'type', $args );
      }
    }
  }
}
add_action( 'init', 'gbcp_register_opp_taxon_type' );

// Register the time commitment taxonomy
function gbcp_register_opp_taxon_time() {
  $commit_labels = array(
    'name'                    => 'Time Commitment Levels',
    'singular_name'           => 'Time Commitment Level',
    'search_items'            => 'Search Commitment Levels',
    'edit_item'               => 'Edit Commitment Level',
    'update_item'             => 'Update Commitment Level',
    'add_new_item'            => 'Add New Commitment Level',
    'new_item_name'           => 'New Commitment Level Name',
    'menu_name'               => 'Duration'
  );

  $commit_slugs = array(
    'slug'                    => 'time',
    'with_front'              => false,
    'hierarchical'            => false,
  );

  $commit_args = array(
    'hierarchical'            => false,
    'labels'                  => $commit_labels,
    'rewrite'                 => $commit_slugs,
  );

  register_taxonomy( 'time', 'post_opp', $commit_args );

  // Only adds defaults if there are no terms
  if ( ! has_term( '', 'time' ) ) {
    $defaults = array(
      'A few minutes'         => array(
        'slug'                    => 'time-min',
      ),
      'A few days'            => array(
        'slug'                    => 'time-day',
      ),
      'Several weeks'         => array(
        'slug'                    => 'time-week',
      ),
      'Several months'        => array(
        'slug'                    => 'time-mon',
      ),
    );

    foreach( $defaults as $term => $args ) {
      if ( term_exists( $term ) == null ) {
        wp_insert_term( $term, 'time', $args );
      }
    }
  }
}
add_action( 'init', 'gbcp_register_opp_taxon_time' );

// Register the on/off campus taxonomy
function gbcp_register_opp_taxon_loc() {
  $loc_labels = array(
    'name'                    => 'Location Tags',
    'singular_name'           => 'Location Tag',
    'search_items'            => 'Search Location Tags',
    'edit_item'               => 'Edit Location Tag',
    'update_item'             => 'Update Location Tag',
    'add_new_item'            => 'Add New Location Tag',
    'new_item_name'           => 'New Location Tag',
    'menu_name'               => 'Location'
  );

  $loc_slugs = array(
    'slug'                    => 'loc',
    'with_front'              => false,
    'hierarchical'            => false,
  );

  $loc_args = array(
    'hierarchical'            => false,
    'labels'                  => $loc_labels,
    'rewrite'                 => $loc_slugs,
  );

  register_taxonomy( 'loc', 'post_opp', $loc_args );

  // Only adds defaults if there are no terms
  if ( ! has_term( '', 'loc' ) ) {
    $defaults = array(
      'On campus'               => array(
        'description'             => 'On campus at Georgia Tech',
        'slug'                    => 'loc-on-campus',
      ),
      'Outside of Atlanta'      => array(
        'description'             => 'Off campus, far from Atlanta',
        'slug'                    => 'loc-outside-atl',
      ),
      'Off campus in Atlanta'   => array(
        'description'             => 'Near campus in Atlanta',
        'slug'                    => 'loc-off-campus-atl',
      ),
    );

    foreach( $defaults as $term => $args ) {
      if ( term_exists( $term ) == null ) {
        wp_insert_term( $term, 'loc', $args );
      }
    }
  }
}
add_action( 'init', 'gbcp_register_opp_taxon_loc' );
add_action( 'init', 'gbcp_register_opp_taxon_type' );