<?php
/**
 * Plugin Name: Simple WP Glossary
 * Plugin URI: http://ashmatadeen.com
 * Description: Automatically adds inline definitions for glossary terms
 * Author: Ash Matadeen
 * Author URI: http://ashmatadeen.com
 * Version: 0.1
 */

add_filter( 'the_content', 'wr_swpg_add_definitions' );
add_action( 'wp_enqueue_scripts', 'wr_swpg_enqueue_js' );

function wr_swpg_add_definitions( $content ) {
	$glossary_items = wr_swpg_get_glossary_items();
	if ( $glossary_items ) {
		foreach ( $glossary_items as $i ) {
			$term = $i->post_title;
			$definition = $i->post_excerpt;
			$regex[] = "/({$term})(?!([^<]+)?>)/i";
			$replacements[] = "<dfn title=\"{$definition}\">$1</dfn>";
		}
		return preg_replace( $regex, $replacements, $content, 1 );	
	} else {
		return $content;
	}
}

function wr_swpg_get_glossary_items() {
	$args = array( 
					'post_type' => 'glossary',
					'post_status' => 'publish',
			);

	$items = new WP_Query( $args );

	if ( $items->post_count > 0 ) {
		return $items->posts;
	} else {
		return false;
	}
}

function wr_swpg_enqueue_js() {
	$src = plugin_dir_url( __FILE__ ) . '/js/simple-wp-glossary.js';
	$deps = array( 'jquery' );
	wp_enqueue_script( 'wr-swpg', $src, $deps, null );

	$src = plugin_dir_url( __FILE__ ) . '/css/simple-wp-glossary.css';
	wp_enqueue_style( 'wr-swpg', $src, false, null );
}