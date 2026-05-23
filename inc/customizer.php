<?php
/**
 * Theme Customizer settings.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function buity_customize_register( $wp_customize ) {
	$wp_customize->add_section( 'buity_hero', array(
		'title'    => __( 'Homepage Hero', 'buity-theme' ),
		'priority' => 30,
	) );

	$wp_customize->add_setting( 'buity_hero_headline', array(
		'default'           => 'NIRVANA',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'buity_hero_headline', array(
		'label'   => __( 'Headline', 'buity-theme' ),
		'section' => 'buity_hero',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'buity_hero_subtext', array(
		'default'           => '#1 Makeup Brand from Bangladesh',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'buity_hero_subtext', array(
		'label'   => __( 'Subtext', 'buity-theme' ),
		'section' => 'buity_hero',
		'type'    => 'textarea',
	) );

	$wp_customize->add_setting( 'buity_hero_cta_text', array(
		'default'           => __( 'Shop Now', 'buity-theme' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'buity_hero_cta_text', array(
		'label'   => __( 'CTA Button Text', 'buity-theme' ),
		'section' => 'buity_hero',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'buity_hero_cta_url', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'buity_hero_cta_url', array(
		'label'       => __( 'CTA Button URL', 'buity-theme' ),
		'description' => __( 'Leave empty to use the shop page.', 'buity-theme' ),
		'section'     => 'buity_hero',
		'type'        => 'url',
	) );

	$wp_customize->add_setting( 'buity_hero_image', array(
		'default'           => 0,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'buity_hero_image', array(
		'label'     => __( 'Hero Background Image', 'buity-theme' ),
		'section'   => 'buity_hero',
		'mime_type' => 'image',
	) ) );

	for ( $i = 1; $i <= 3; $i++ ) {
		$wp_customize->add_setting( 'buity_hero_slide_' . $i . '_image', array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		) );
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'buity_hero_slide_' . $i . '_image', array(
			'label'     => sprintf( __( 'Slide %d Image', 'buity-theme' ), $i ),
			'section'   => 'buity_hero',
			'mime_type' => 'image',
		) ) );

		foreach ( array(
			'title_left'    => __( 'Slide %d Left Title', 'buity-theme' ),
			'subtitle_left' => __( 'Slide %d Left Subtitle', 'buity-theme' ),
			'title_right'   => __( 'Slide %d Right Title', 'buity-theme' ),
		) as $key => $label ) {
			$setting = 'buity_hero_slide_' . $i . '_' . $key;
			$wp_customize->add_setting( $setting, array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			) );
			$wp_customize->add_control( $setting, array(
				'label'   => sprintf( $label, $i ),
				'section' => 'buity_hero',
				'type'    => 'text',
			) );
		}

		$wp_customize->add_setting( 'buity_hero_slide_' . $i . '_link', array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( 'buity_hero_slide_' . $i . '_link', array(
			'label'   => sprintf( __( 'Slide %d Link', 'buity-theme' ), $i ),
			'section' => 'buity_hero',
			'type'    => 'url',
		) );
	}

	$wp_customize->add_section( 'buity_brands', array(
		'title'    => __( 'Top Brands Banners', 'buity-theme' ),
		'priority' => 35,
	) );

	for ( $i = 1; $i <= 6; $i++ ) {
		$wp_customize->add_setting( 'buity_brand_' . $i . '_image', array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		) );
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'buity_brand_' . $i . '_image', array(
			'label'     => sprintf( __( 'Banner %d Image', 'buity-theme' ), $i ),
			'section'   => 'buity_brands',
			'mime_type' => 'image',
		) ) );

		$wp_customize->add_setting( 'buity_brand_' . $i . '_label', array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'buity_brand_' . $i . '_label', array(
			'label'   => sprintf( __( 'Banner %d Label', 'buity-theme' ), $i ),
			'section' => 'buity_brands',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'buity_brand_' . $i . '_link', array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( 'buity_brand_' . $i . '_link', array(
			'label'   => sprintf( __( 'Banner %d Link', 'buity-theme' ), $i ),
			'section' => 'buity_brands',
			'type'    => 'url',
		) );
	}

	$wp_customize->add_section( 'buity_footer', array(
		'title'    => __( 'Footer', 'buity-theme' ),
		'priority' => 120,
	) );

	$wp_customize->add_setting( 'buity_track_order_url', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'buity_track_order_url', array(
		'label'   => __( 'Track Order URL', 'buity-theme' ),
		'section' => 'buity_footer',
		'type'    => 'url',
	) );

	$wp_customize->add_setting( 'buity_footer_about', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'buity_footer_about', array(
		'label'   => __( 'About Text', 'buity-theme' ),
		'section' => 'buity_footer',
		'type'    => 'textarea',
	) );

	$wp_customize->add_setting( 'buity_footer_phone', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'buity_footer_phone', array(
		'label'   => __( 'Phone', 'buity-theme' ),
		'section' => 'buity_footer',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'buity_footer_email', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_email',
	) );
	$wp_customize->add_control( 'buity_footer_email', array(
		'label'   => __( 'Email', 'buity-theme' ),
		'section' => 'buity_footer',
		'type'    => 'email',
	) );

	$wp_customize->add_setting( 'buity_footer_address', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'buity_footer_address', array(
		'label'   => __( 'Address', 'buity-theme' ),
		'section' => 'buity_footer',
		'type'    => 'textarea',
	) );

	$social_keys = array(
		'facebook'  => __( 'Facebook URL', 'buity-theme' ),
		'instagram' => __( 'Instagram URL', 'buity-theme' ),
		'youtube'   => __( 'YouTube URL', 'buity-theme' ),
		'whatsapp'  => __( 'WhatsApp URL', 'buity-theme' ),
	);

	foreach ( $social_keys as $key => $label ) {
		$setting = 'buity_social_' . $key;
		$wp_customize->add_setting( $setting, array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( $setting, array(
			'label'   => $label,
			'section' => 'buity_footer',
			'type'    => 'url',
		) );
	}

	$wp_customize->add_section( 'buity_promo', array(
		'title'    => __( 'Promo Bar', 'buity-theme' ),
		'priority' => 25,
	) );

	$wp_customize->add_setting( 'buity_promo_text', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'buity_promo_text', array(
		'label'   => __( 'Promo Bar Text', 'buity-theme' ),
		'section' => 'buity_promo',
		'type'    => 'text',
	) );
}
add_action( 'customize_register', 'buity_customize_register' );

/**
 * Output promo bar if configured.
 */
function buity_promo_bar() {
	$text = get_theme_mod( 'buity_promo_text', '' );
	if ( ! $text ) {
		return;
	}
	?>
	<div class="promo-bar">
		<div class="container promo-bar__inner">
			<p><?php echo esc_html( $text ); ?></p>
		</div>
	</div>
	<?php
}
add_action( 'buity_before_header', 'buity_promo_bar' );
