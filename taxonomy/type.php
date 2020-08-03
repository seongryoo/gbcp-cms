<?php

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
    'show_in_rest'            => true,
    'rewrite'                 => $activities_slugs,
  );

  register_taxonomy( 'taxon_type', 'post_opp', $activities_args );
}
add_action( 'init', 'gbcp_register_opp_taxon_type' );

function gbcp_register_opp_taxon_type_defaults() {
  gbcp_register_opp_taxon_type();

  // Only adds defaults if there are no terms
  if ( ! has_term( '', 'taxon_type' ) ) {
    $defaults = array(
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
      if ( term_exists( $term, 'taxon_type' ) == null ) {
        wp_insert_term( $term, 'taxon_type', $args );
      }
    }
  }
}
register_activation_hook( PLUGIN_FILE_URL, 'gbcp_register_opp_taxon_type_defaults' );