<?php
/**
 * Schema.org JSON-LD Structured Data Generator for Tools and Blog Posts
 * Enhances Semantic SEO & Rich Snippets in Google Search Results.
 *
 * @package PickWitty_Tools_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output Schema.org JSON-LD in <head>
 */
function pw_tools_output_schema_json_ld() {
	if ( is_singular( 'tool' ) ) {
		global $post;
		$short_desc = get_post_meta( $post->ID, '_pw_tool_short_desc', true ) ?: get_the_excerpt();
		$author_name = get_the_author_meta( 'display_name', $post->post_author ) ?: 'PickWitty';

		$schema = array(
			'@context'        => 'https://schema.org',
			'@type'           => 'SoftwareApplication',
			'name'            => get_the_title(),
			'operatingSystem' => 'All (Web Browser)',
			'applicationCategory' => 'WebApplication',
			'offers'          => array(
				'@type' => 'Offer',
				'price' => '0',
				'priceCurrency' => 'USD',
			),
			'description'     => wp_strip_all_tags( $short_desc ),
			'url'             => get_permalink(),
			'author'          => array(
				'@type' => 'Organization',
				'name'  => $author_name,
				'url'   => home_url(),
			),
		);

		if ( has_post_thumbnail() ) {
			$schema['image'] = get_the_post_thumbnail_url( $post->ID, 'full' );
		}

		echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>' . "\n";
	} elseif ( is_single() ) {
		global $post;
		$schema = array(
			'@context'         => 'https://schema.org',
			'@type'            => 'BlogPosting',
			'headline'         => get_the_title(),
			'datePublished'    => get_the_date( 'c' ),
			'dateModified'     => get_the_modified_date( 'c' ),
			'author'           => array(
				'@type' => 'Person',
				'name'  => get_the_author(),
			),
			'publisher'        => array(
				'@type' => 'Organization',
				'name'  => get_bloginfo( 'name' ),
				'logo'  => array(
					'@type' => 'ImageObject',
					'url'   => get_site_icon_url() ?: PW_TOOLS_URI . '/assets/img/logo.png',
				),
			),
			'description'      => wp_strip_all_tags( get_the_excerpt() ),
			'mainEntityOfPage' => get_permalink(),
		);

		if ( has_post_thumbnail() ) {
			$schema['image'] = get_the_post_thumbnail_url( $post->ID, 'full' );
		}

		echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>' . "\n";
	}
}
add_action( 'wp_head', 'pw_tools_output_schema_json_ld' );
