<?php


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
    'show_in_rest'            => true,
  );

  register_taxonomy( 'taxon_loc', 'post_opp', $loc_args );
}
add_action( 'init', 'gbcp_register_opp_taxon_loc' );

function gbcp_register_opp_taxon_loc_defaults() {
  gbcp_register_opp_taxon_loc();

  // Only adds defaults if there are no terms
  if ( ! has_term( '', 'taxon_loc' ) ) {
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
      if ( term_exists( $term, 'taxon_loc' ) == null ) {
        wp_insert_term( $term, 'taxon_loc', $args );
      }
    }
  }
}
register_activation_hook( PLUGIN_FILE_URL, 'gbcp_register_opp_taxon_loc_defaults' );