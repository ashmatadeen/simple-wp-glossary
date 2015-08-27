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
add_action( 'init', 'wr_swpg_cpt' );

function wr_swpg_add_definitions( $content ) {
	$glossary_items = wr_swpg_get_glossary_items();
	if ( $glossary_items ) {
		foreach ( $glossary_items as $i ) {
			$term = $i->post_title;
			$definition = $i->post_excerpt;
			$regex[] = "/(\b{$term}\b)(?!([^<]+)?>)/i";
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

function wr_swpg_cpt() {
	$labels = array(
		'name' => 'Glossary items',
		'singular_name' => 'Glossary item',
		);

	$args = array(
				'labels' => $labels,
				'description' => 'Defining terms',
				'public' => true,
				'show_ui' => true,
				'has_archive' => false,
				'show_in_menu' => true,
				'exclude_from_search' => false,
				'capability_type' => 'post',
				'map_meta_cap' => true,
				'hierarchical' => false,
				'rewrite' => array( 'slug' => 'glossary', 'with_front' => true ),
				'query_var' => true,
				'supports' => array( 'title', 'excerpt' ),
			);

	register_post_type( 'glossary', $args );
}
