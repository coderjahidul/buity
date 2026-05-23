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
 * Default home page product sections when none configured.
 *
 * @return array[]
 */
function buity_get_default_home_sections() {
	return array(
		array(
			'id'            => 'flash-deals',
			'title'         => __( 'Flash Deals', 'buity-theme' ),
			'source'        => 'sale',
			'category_id'   => 0,
			'limit'         => 10,
			'view_all_text' => __( 'View All', 'buity-theme' ),
			'view_all_url'  => '',
			'args'          => array(
				'on_sale' => true,
				'limit'   => 10,
				'status'  => 'publish',
			),
		),
		array(
			'id'            => 'popular',
			'title'         => __( 'Popular products', 'buity-theme' ),
			'source'        => 'popular',
			'category_id'   => 0,
			'limit'         => 10,
			'view_all_text' => __( 'View All', 'buity-theme' ),
			'view_all_url'  => '',
			'args'          => array(
				'orderby' => 'popularity',
				'order'   => 'DESC',
				'limit'   => 10,
				'status'  => 'publish',
			),
		),
		array(
			'id'            => 'new-arrival',
			'title'         => __( 'New Arrival', 'buity-theme' ),
			'source'        => 'new',
			'category_id'   => 0,
			'limit'         => 10,
			'view_all_text' => __( 'View All', 'buity-theme' ),
			'view_all_url'  => '',
			'args'          => array(
				'orderby' => 'date',
				'order'   => 'DESC',
				'limit'   => 10,
				'status'  => 'publish',
			),
		),
	);
}

/**
 * Build wc_get_products args from a home section config row.
 *
 * @param array<string, mixed> $section Section settings.
 * @return array<string, mixed>
 */
function buity_home_section_product_args( $section ) {
	$limit  = max( 1, min( 24, (int) ( $section['limit'] ?? 10 ) ) );
	$source = $section['source'] ?? 'popular';
	$args   = array(
		'limit'  => $limit,
		'status' => 'publish',
	);

	switch ( $source ) {
		case 'sale':
			$args['on_sale'] = true;
			break;
		case 'new':
			$args['orderby'] = 'date';
			$args['order']   = 'DESC';
			break;
		case 'featured':
			$args['featured'] = true;
			break;
		case 'category':
			$cat_id = (int) ( $section['category_id'] ?? 0 );
			if ( $cat_id > 0 ) {
				// WooCommerce `category` expects slugs; use term IDs via product_category_id.
				$args['product_category_id'] = array( $cat_id );
			}
			break;
		case 'popular':
		default:
			$args['orderby'] = 'popularity';
			$args['order']   = 'DESC';
			break;
	}

	return $args;
}

/**
 * Auto View All URL from section source.
 *
 * @param array<string, mixed> $section Section settings.
 * @return string
 */
function buity_home_section_auto_view_all_url( $section ) {
	$shop = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
	$source = $section['source'] ?? 'popular';

	switch ( $source ) {
		case 'sale':
			return add_query_arg( 'on_sale', '1', $shop );
		case 'category':
			$cat_id = (int) ( $section['category_id'] ?? 0 );
			if ( $cat_id > 0 ) {
				$link = get_term_link( $cat_id, 'product_cat' );
				return is_wp_error( $link ) ? $shop : $link;
			}
			return $shop;
		case 'new':
		case 'featured':
		case 'popular':
		default:
			return $shop;
	}
}

/**
 * Home page product sections from Theme Settings.
 *
 * @return array[]
 */
function buity_get_home_sections() {
	$configured = function_exists( 'buity_get_home_sections_option' ) ? buity_get_home_sections_option() : array();

	if ( ! empty( $configured ) ) {
		$sections = array();
		foreach ( $configured as $index => $row ) {
			$title = isset( $row['title'] ) ? trim( (string) $row['title'] ) : '';
			if ( '' === $title ) {
				continue;
			}

			$id = 'home-section-' . ( $index + 1 );
			$sections[] = array(
				'id'            => $id,
				'title'         => $title,
				'source'        => $row['source'] ?? 'popular',
				'category_id'   => (int) ( $row['category_id'] ?? 0 ),
				'limit'         => (int) ( $row['limit'] ?? 10 ),
				'view_all_text' => $row['view_all_text'] ?? __( 'View All', 'buity-theme' ),
				'view_all_url'  => $row['view_all_url'] ?? '',
				'args'          => buity_home_section_product_args( $row ),
			);
		}

		if ( ! empty( $sections ) ) {
			return apply_filters( 'buity_home_sections', $sections );
		}
	}

	return apply_filters( 'buity_home_sections', buity_get_default_home_sections() );
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
 * Hero slides from Theme Settings with Customizer fallback.
 *
 * @return array[]
 */
function buity_get_hero_slides() {
	$slides = function_exists( 'buity_get_hero_slides_option' ) ? buity_get_hero_slides_option() : array();

	if ( ! empty( $slides ) ) {
		$normalized = array();
		foreach ( $slides as $slide ) {
			$image_id = (int) ( $slide['image_id'] ?? 0 );
			if ( ! $image_id ) {
				continue;
			}
			$normalized[] = array(
				'image_id'      => $image_id,
				'title_left'    => $slide['title_left'] ?? '',
				'subtitle_left' => $slide['subtitle_left'] ?? '',
				'title_right'   => $slide['title_right'] ?? '',
				'link'          => $slide['link'] ?? '',
				'alt'           => $slide['alt'] ?? '',
			);
		}
		if ( ! empty( $normalized ) ) {
			return apply_filters( 'buity_hero_slides', $normalized );
		}
	}

	$legacy = array();
	for ( $i = 1; $i <= 3; $i++ ) {
		$image_id = (int) get_theme_mod( 'buity_hero_slide_' . $i . '_image', 0 );
		$title_l  = get_theme_mod( 'buity_hero_slide_' . $i . '_title_left', '' );
		$sub_l    = get_theme_mod( 'buity_hero_slide_' . $i . '_subtitle_left', '' );
		$title_r  = get_theme_mod( 'buity_hero_slide_' . $i . '_title_right', '' );

		if ( ! $image_id && ! $title_l && ! $title_r ) {
			continue;
		}

		$legacy[] = array(
			'image_id'      => $image_id,
			'title_left'    => $title_l,
			'subtitle_left' => $sub_l,
			'title_right'   => $title_r,
			'link'          => get_theme_mod( 'buity_hero_slide_' . $i . '_link', '' ),
			'alt'           => '',
		);
	}

	if ( empty( $legacy ) ) {
		$legacy[] = array(
			'image_id'      => (int) get_theme_mod( 'buity_hero_image', 0 ),
			'title_left'    => get_theme_mod( 'buity_hero_headline', 'NIRVANA' ),
			'subtitle_left' => get_theme_mod( 'buity_hero_subtext', '#1 Makeup Brand from Bangladesh' ),
			'title_right'   => 'UNLEASH YOUR TRUE COLOR',
			'link'          => class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ),
			'alt'           => '',
		);
	}

	return apply_filters( 'buity_hero_slides', $legacy );
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

	foreach ( buity_get_home_sections() as $section ) {
		if ( ( $section['id'] ?? '' ) !== $section_id ) {
			continue;
		}
		if ( ! empty( $section['view_all_url'] ) ) {
			return $section['view_all_url'];
		}
		return buity_home_section_auto_view_all_url( $section );
	}

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
	$html   = '<div class="product-card__stars" aria-label="' . esc_attr( sprintf( __( 'Rated %s out of 5', 'buity-theme' ), $rating ) ) . '">';

	for ( $i = 1; $i <= 5; $i++ ) {
		$filled = ( $rating >= $i - 0.25 );
		$html  .= '<span class="product-card__star' . ( $filled ? ' is-filled' : '' ) . '">★</span>';
	}

	$html .= '</div>';
	return $html;
}
