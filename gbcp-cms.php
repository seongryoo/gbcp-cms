<?php

/**
 * Plugin Name: Global Change Program Content Management
 */

define( 'PLUGIN_FILE_URL', __FILE__ );

// Assets file loads in js and css needed to render blocks in WP editor
include( plugin_dir_path( __FILE__ ) . 'includes/gbcp-assets.php' );

// Blocks file loads in php files needed for any custom block-based rendering
include( plugin_dir_path( __FILE__ ) . 'includes/gbcp-block-render.php' );

// Custom posts file connects php files which register individual custom post types
include( plugin_dir_path( __FILE__ ) . 'includes/gbcp-custom-posts.php' );

// Taxonomy file connects php files which register custom taxonomimes for custom post types & register default taxonomy terms
include( plugin_dir_path( __FILE__ ) . 'includes/gbcp-taxonomy.php' );


// Very useful helper method
function scream( $msg ) {
  echo '<script>console.log("' . $msg . '");</script>';
}