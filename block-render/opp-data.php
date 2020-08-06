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
      'willExpire' => array(
        'type' => 'boolean',
        'default' => false,
      ),
    ),
    'render_callback' => 'gbcp_opp_data_block_render',
  );

  register_block_type( 'gbcp/opp-data', $register_args );
}
add_action( 'init', 'gbcp_register_opp_data_block' );

// Rendering

function gbcp_opp_data_block_render( $attributes ) {

  $markup = '';
  $markup .= 'test';
  return $markup;
}