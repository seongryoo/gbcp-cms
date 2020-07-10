<?php

// Enqueue stylesheets
function gbcp_enqueue_admin_styles() {
  $stylesheets = array(
    'admin',
  );

  foreach( $stylesheets as $style ) {
    $style_name = 'gbcp-' . $style;
    $style_path = plugins_url( '/../assets/css/' . $style . '.css', __FILE__ );
    wp_enqueue_style( $style_name, $style_path );
    scream( $style_path );
  }
}
add_action( 'admin_enqueue_scripts', 'gbcp_enqueue_admin_styles' );