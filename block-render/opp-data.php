<?php
// Registration

function gbcp_register_opp_data_block() {
  if ( ! function_exists( 'register_block_type' ) ) {
    return;
  }

  $register_args = array(
    'attributes' => array(
      'desc' => array(
        'type' => 'string',
        'source' => 'meta',
        'meta' => 'post_opp_meta_desc',
      ),
      'level' => array(
        'type' => 'string',
        'source' => 'meta',
        'meta' => 'post_opp_meta_level',
      ),
      'loc' => array(
        'type' => 'string',
        'source' => 'meta',
        'meta' => 'post_opp_meta_loc',
      ),
      'expr' => array(
        'type' => 'string',
        'source' => 'meta',
        'meta' => 'post_opp_meta_expr',
      ),
      'will_expire' => array(
        'type' => 'boolean',
        'default' => false,
        'source' => 'meta',
        'meta' => 'post_opp_meta_will_expire'
      ),
    ),
    'render_callback' => 'gbcp_opp_data_block_render',
  );

  register_block_type( 'gbcp/opp-data', $register_args );
}
add_action( 'init', 'gbcp_register_opp_data_block' );

// Replacing gutenberg title text

function gbcp_opp_change_title_text( $title ) {
  $screen = get_current_screen();

  if ( 'post_opp' == $screen->post_type ) {
    $title = 'Add Title (e.g. "Sign up for an affiliated course")';
  }

  return $title;
}
add_filter( 'enter_title_here', 'gbcp_opp_change_title_text' );

// Rendering

function gbcp_opp_data_block_render( $attributes ) {
  $markup = '';
  return $markup;
}