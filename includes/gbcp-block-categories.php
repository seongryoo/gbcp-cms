<?php

// Creating block categories for gbcp

function gbcp_opp_block_categories( $categories, $post ) {
  $gbcp_cat = array(
    'slug'                    => 'gbcp-opp-blocks',
    'title'                   => __( 'Global Change Opportunity Blocks', 'gbcp-opp-blocks' ),
    'icon'                    => 'dashicons-welcome-learn-more',
  );
  return array_merge(
    $categories,
    array(
      $gbcp_cat,
    )
  );
}

add_filter( 'block_categories', 'gbcp_opp_block_categories', 10, 2 );