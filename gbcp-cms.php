<?php

/**
 * Plugin Name: Global Change Program Content Management
 */

// Assets file loads in js and css needed to render blocks in WP editor
include( plugin_dir_path( __FILE__ ) . 'includes/gbcp-assets.php' );

// Blocks file loads in php files needed for any custom block-based rendering
include( plugin_dir_path( __FILE__ ) . 'includes/gbcp-block-render.php' );

// Custom posts file connects php files which register individual custom post types
include( plugin_dir_path( __FILE__ ) . 'includes/gbcp-custom-posts.php' );