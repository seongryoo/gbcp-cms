<?php

// Enqueue stylesheets
function gbcp_enqueue_admin_styles() {
  $stylesheets = array(
    'admin',
  );

  foreach( $stylesheets as $style ) {
    $style_name = 'gbcp-' . $style;
    $style_path = plugins_url( '/../admin-assets/css/' . $style . '.css', __FILE__ );
    wp_enqueue_style( $style_name, $style_path );
    scream( $style_path );
  }
}
add_action( 'admin_enqueue_scripts', 'gbcp_enqueue_admin_styles' );


// Enqueue block editor scripts
function gbcp_enqueue_block_assets() {
  // Stylesheets for editing experience
  $stylesheets = array(
    'blocks',
  );
  foreach( $stylesheets as $style ) {
    $style_name = 'gbcp-' . $style;
    $style_path = plugins_url( '/../editor-assets/css/' . $style . '.css', __FILE__ );
    wp_enqueue_style( $style_name, $style_path );
  }
  // Block js files
  $scripts = array(
    'opp',
  );
  $deps = array(
    'wp-blocks',
    'wp-i18n',
    'wp-editor',
    'wp-date',
  );
  foreach( $scripts as $script ) {
    $script_name = 'gbcp-' . $script;
    $script_path = plugins_url( '/../editor-assets/js/' . $script . '.js', __FILE__ );
    wp_enqueue_script( $script_name, $script_path, $deps );
  }
}
add_action( 'enqueue_block_editor_assets', 'gbcp_enqueue_block_assets' );