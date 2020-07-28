<?php


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
}
add_action( 'init', 'gbcp_register_opp_taxon_time' );

function gbcp_register_opp_taxon_time_defaults() {
  gbcp_register_opp_taxon_time();

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
register_activation_hook( PLUGIN_FILE_URL, 'gbcp_register_opp_taxon_time_defaults' );