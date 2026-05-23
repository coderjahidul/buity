<?php
/**
 * Homepage helpers and product queries.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Home page product sections — Shajghor layout.
 *
 * @return array[]
 */
function buity_get_home_sections() {
	$sections = array(
		array(
			'id'    => 'flash-deals',
			'title' => __( 'Flash Deals', 'buity-theme' ),
			'args'  => array(
				'on_sale' => true,
				'limit'   => 10,
				'status'  => 'publish',
			),
		),
		array(
			'id'    => 'popular',
			'title' => __( 'Popular products', 'buity-theme' ),
			'args'  => array(
				'orderby' => 'popularity',
				'order'   => 'DESC',
				'limit'   => 10,
				'status'  => 'publish',
			),
		),
		array(
			'id'    => 'new-arrival',
			'title' => __( 'New Arrival', 'buity-theme' ),
			'args'  => array(
				'orderby' => 'date',
				'order'   => 'DESC',
				'limit'   => 10,
				'status'  => 'publish',
			),
		),
	);

	return apply_filters( 'buity_home_sections', $sections );
}

/**
 * Get products for a home section.
 *
 * @param array $args wc_get_products args.
 * @return WC_Product[]
 */
function buity_get_section_products( $args ) {
	if ( ! function_exists( 'wc_get_products' ) ) {
		return array();
	}

	$products = wc_get_products( $args );
	return is_array( $products ) ? $products : array();
}

/**
 * Hero carousel slides from customizer.
 *
 * @return array[]
 */
function buity_get_hero_slides() {
	$slides = array();

	for ( $i = 1; $i <= 3; $i++ ) {
		$image_id = (int) get_theme_mod( 'buity_hero_slide_' . $i . '_image', 0 );
		$title_l  = get_theme_mod( 'buity_hero_slide_' . $i . '_title_left', '' );
		$sub_l    = get_theme_mod( 'buity_hero_slide_' . $i . '_subtitle_left', '' );
		$title_r  = get_theme_mod( 'buity_hero_slide_' . $i . '_title_right', '' );

		if ( ! $image_id && ! $title_l && ! $title_r ) {
			continue;
		}

		$slides[] = array(
			'image_id'       => $image_id,
			'title_left'     => $title_l,
			'subtitle_left'  => $sub_l,
			'title_right'    => $title_r,
			'link'           => get_theme_mod( 'buity_hero_slide_' . $i . '_link', '' ),
		);
	}

	if ( empty( $slides ) ) {
		$slides[] = array(
			'image_id'      => (int) get_theme_mod( 'buity_hero_image', 0 ),
			'title_left'    => get_theme_mod( 'buity_hero_headline', 'NIRVANA' ),
			'subtitle_left' => get_theme_mod( 'buity_hero_subtext', '#1 Makeup Brand from Bangladesh' ),
			'title_right'   => 'UNLEASH YOUR TRUE COLOR',
			'link'          => class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ),
		);
	}

	return apply_filters( 'buity_hero_slides', $slides );
}

/**
 * Top brand banners from customizer.
 *
 * @return array[]
 */
function buity_get_brand_banners() {
	$banners = array();

	for ( $i = 1; $i <= 6; $i++ ) {
		$image_id = (int) get_theme_mod( 'buity_brand_' . $i . '_image', 0 );
		$link     = get_theme_mod( 'buity_brand_' . $i . '_link', '' );
		$label    = get_theme_mod( 'buity_brand_' . $i . '_label', '' );

		if ( ! $image_id && ! $label ) {
			continue;
		}

		$banners[] = array(
			'image_id' => $image_id,
			'link'     => $link,
			'label'    => $label,
			'span'     => get_theme_mod( 'buity_brand_' . $i . '_span', 'normal' ),
		);
	}

	if ( empty( $banners ) ) {
		$defaults = array(
			array( 'label' => 'Winter Sale', 'span' => 'wide-left', 'color' => '#5c6bc0' ),
			array( 'label' => 'Neutrogena', 'span' => 'normal', 'color' => '#ff8a65' ),
			array( 'label' => 'Vatika', 'span' => 'normal', 'color' => '#81c784' ),
			array( 'label' => 'Veet', 'span' => 'normal', 'color' => '#f48fb1' ),
			array( 'label' => 'Jewellery', 'span' => 'normal', 'color' => '#ffd54f' ),
			array( 'label' => 'Skincare', 'span' => 'wide-right', 'color' => '#4dd0e1' ),
		);
		foreach ( $defaults as $d ) {
			$banners[] = $d;
		}
	}

	return apply_filters( 'buity_brand_banners', $banners );
}

/**
 * Section header "View All" URL.
 *
 * @param string $section_id Section ID.
 * @return string
 */
function buity_section_view_all_url( $section_id ) {
	$shop = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );

	$urls = array(
		'flash-deals'  => add_query_arg( 'on_sale', '1', $shop ),
		'popular'      => $shop,
		'new-arrival'  => $shop,
		'categories'   => $shop,
		'brands'       => $shop,
	);

	return apply_filters( 'buity_section_view_all_url', $urls[ $section_id ] ?? $shop, $section_id );
}

/**
 * Star rating HTML for product card.
 *
 * @param WC_Product $product Product.
 * @return string
 */
function buity_product_star_rating( $product ) {
	$rating = $product->get_average_rating();
	$count  = $product->get_rating_count();
	$html   = '<div class="product-card__stars" aria-label="' . esc_attr( sprintf( __( 'Rated %s out of 5', 'buity-theme' ), $rating ) ) . '">';

	for ( $i = 1; $i <= 5; $i++ ) {
		$filled = ( $rating >= $i - 0.25 );
		$html  .= '<span class="product-card__star' . ( $filled ? ' is-filled' : '' ) . '">★</span>';
	}

	$html .= '</div>';
	return $html;
}
