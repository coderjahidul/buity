<?php
/**
 * Theme Settings admin page and option helpers.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BUITY_THEME_SETTINGS_OPTION', 'buity_theme_settings' );
define( 'BUITY_THEME_SETTINGS_PAGE', 'buity-theme-settings' );

/**
 * Default option values.
 *
 * @return array<string, mixed>
 */
function buity_theme_settings_defaults() {
	return array(
		'logo_header'              => 0,
		'logo_footer'              => 0,
		'logo_site'                => 0,
		'logo_alt_text'            => '',
		'footer_description'       => __( 'Your trusted beauty & personal care destination in Bangladesh.', 'buity-theme' ),
		'copyright_line'           => '',
		'copyright_site_name'      => 1,
		'powered_by_text'          => '',
		'powered_by_url'           => '',
		'quick_links_heading'      => __( 'Quick Links', 'buity-theme' ),
		'quick_links_menu'         => 0,
		'useful_links_heading'     => __( 'Useful Links', 'buity-theme' ),
		'useful_links_menu'        => 0,
		'search_placeholder'       => __( 'Search products…', 'buity-theme' ),
		'search_no_results'        => __( 'No products found. Try a different search.', 'buity-theme' ),
		'mobile_search_button'     => __( 'Search', 'buity-theme' ),
		'mobile_search_suggestions' => '',
		'contact_email'            => '',
		'contact_phone'            => '',
		'contact_address'          => '',
		'social_facebook'          => '',
		'social_instagram'         => '',
		'social_youtube'           => '',
		'social_whatsapp'          => '',
		'color_primary'            => '#009688',
		'color_secondary'          => '#e91e63',
		'color_tertiary'           => '#1a237e',
		'hero_slides'              => array(),
		'home_sections'            => array(),
		'shop_products_per_page'   => 12,
	);
}

/**
 * Product source choices for home sections.
 *
 * @return array<string, string>
 */
function buity_home_section_source_choices() {
	return array(
		'popular'  => __( 'Popular / best sellers', 'buity-theme' ),
		'sale'     => __( 'On sale', 'buity-theme' ),
		'new'      => __( 'New arrivals', 'buity-theme' ),
		'featured' => __( 'Featured products', 'buity-theme' ),
		'category' => __( 'Product category', 'buity-theme' ),
	);
}

/**
 * Get hero slides from theme settings.
 *
 * @return array<int, array<string, mixed>>
 */
function buity_get_hero_slides_option() {
	$slides = buity_get_option( 'hero_slides', array() );
	return is_array( $slides ) ? $slides : array();
}

/**
 * Get home sections from theme settings.
 *
 * @return array<int, array<string, mixed>>
 */
function buity_get_home_sections_option() {
	$sections = buity_get_option( 'home_sections', array() );
	return is_array( $sections ) ? $sections : array();
}

/**
 * Map legacy Customizer theme_mod keys to theme settings keys.
 *
 * @return array<string, string>
 */
function buity_theme_settings_theme_mod_map() {
	return array(
		'footer_description'  => 'buity_footer_about',
		'contact_email'       => 'buity_footer_email',
		'contact_phone'       => 'buity_footer_phone',
		'contact_address'     => 'buity_footer_address',
		'social_facebook'     => 'buity_social_facebook',
		'social_instagram'    => 'buity_social_instagram',
		'social_youtube'      => 'buity_social_youtube',
		'social_whatsapp'     => 'buity_social_whatsapp',
	);
}

/**
 * Get a single theme setting.
 *
 * @param string $key     Setting key.
 * @param mixed  $default Optional default override.
 * @return mixed
 */
function buity_get_option( $key, $default = null ) {
	$defaults = buity_theme_settings_defaults();
	$fallback = null !== $default ? $default : ( $defaults[ $key ] ?? '' );

	$settings = get_option( BUITY_THEME_SETTINGS_OPTION, array() );
	if ( is_array( $settings ) && array_key_exists( $key, $settings ) ) {
		$value = $settings[ $key ];
		if ( is_array( $value ) ) {
			return $value;
		}
		if ( '' !== $value && null !== $value ) {
			return $value;
		}
	}

	$map = buity_theme_settings_theme_mod_map();
	if ( isset( $map[ $key ] ) ) {
		$mod = get_theme_mod( $map[ $key ], '' );
		if ( '' !== $mod && null !== $mod ) {
			return $mod;
		}
	}

	return $fallback;
}

/**
 * Register settings.
 */
function buity_register_theme_settings() {
	register_setting(
		'buity_theme_settings_group',
		BUITY_THEME_SETTINGS_OPTION,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'buity_sanitize_theme_settings',
			'default'           => buity_theme_settings_defaults(),
		)
	);
}
add_action( 'admin_init', 'buity_register_theme_settings' );

/**
 * All registered setting keys.
 *
 * @return string[]
 */
function buity_theme_settings_keys() {
	$keys = array();
	foreach ( buity_theme_settings_fields() as $tab_sections ) {
		foreach ( $tab_sections as $section_fields ) {
			foreach ( $section_fields as $field ) {
				$keys[] = $field['key'];
			}
		}
	}
	return array_unique( $keys );
}

/**
 * Sanitize saved settings (merge with existing so tab saves do not wipe other tabs).
 *
 * @param array<string, mixed>|mixed $input Raw input.
 * @return array<string, mixed>
 */
function buity_sanitize_theme_settings( $input ) {
	$defaults = buity_theme_settings_defaults();
	$stored   = get_option( BUITY_THEME_SETTINGS_OPTION, array() );
	$output   = wp_parse_args( is_array( $stored ) ? $stored : array(), $defaults );

	if ( ! is_array( $input ) ) {
		return $output;
	}

	$active_tab = isset( $_POST['buity_active_tab'] ) // phpcs:ignore WordPress.Security.NonceVerification.Missing
		? sanitize_key( wp_unslash( $_POST['buity_active_tab'] ) ) // phpcs:ignore WordPress.Security.NonceVerification.Missing
		: '';

	$ints = array( 'logo_header', 'logo_footer', 'logo_site', 'quick_links_menu', 'useful_links_menu', 'shop_products_per_page' );
	foreach ( $ints as $key ) {
		if ( array_key_exists( $key, $input ) ) {
			$output[ $key ] = absint( $input[ $key ] );
		}
	}

	if ( array_key_exists( 'shop_products_per_page', $output ) ) {
		$output['shop_products_per_page'] = max( 1, min( 100, (int) $output['shop_products_per_page'] ) );
	}

	if ( array_key_exists( 'hero_slides', $input ) && is_array( $input['hero_slides'] ) ) {
		$output['hero_slides'] = buity_sanitize_hero_slides( $input['hero_slides'] );
	}

	if ( array_key_exists( 'home_sections', $input ) && is_array( $input['home_sections'] ) ) {
		$output['home_sections'] = buity_sanitize_home_sections( $input['home_sections'] );
	}

	if ( 'general' === $active_tab ) {
		$output['copyright_site_name'] = ! empty( $input['copyright_site_name'] ) ? 1 : 0;
	}

	$text_fields = array(
		'logo_alt_text',
		'copyright_line',
		'powered_by_text',
		'quick_links_heading',
		'useful_links_heading',
		'search_placeholder',
		'search_no_results',
		'mobile_search_button',
		'contact_phone',
		'social_whatsapp',
	);
	foreach ( $text_fields as $key ) {
		if ( array_key_exists( $key, $input ) ) {
			$output[ $key ] = sanitize_text_field( $input[ $key ] );
		}
	}

	$textarea_fields = array( 'footer_description', 'contact_address', 'mobile_search_suggestions' );
	foreach ( $textarea_fields as $key ) {
		if ( array_key_exists( $key, $input ) ) {
			$output[ $key ] = sanitize_textarea_field( $input[ $key ] );
		}
	}

	if ( array_key_exists( 'contact_email', $input ) ) {
		$output['contact_email'] = sanitize_email( $input['contact_email'] );
	}

	$url_fields = array( 'powered_by_url', 'social_facebook', 'social_instagram', 'social_youtube' );
	foreach ( $url_fields as $key ) {
		if ( array_key_exists( $key, $input ) ) {
			$output[ $key ] = esc_url_raw( $input[ $key ] );
		}
	}

	$color_fields = array( 'color_primary', 'color_secondary', 'color_tertiary' );
	foreach ( $color_fields as $key ) {
		if ( array_key_exists( $key, $input ) ) {
			$color = sanitize_hex_color( $input[ $key ] );
			$output[ $key ] = $color ? $color : $defaults[ $key ];
		}
	}

	return $output;
}

/**
 * Sanitize hero slider slides.
 *
 * @param array<int, array<string, mixed>> $slides Raw slides.
 * @return array<int, array<string, mixed>>
 */
function buity_sanitize_hero_slides( $slides ) {
	$clean = array();

	foreach ( $slides as $slide ) {
		if ( ! is_array( $slide ) ) {
			continue;
		}

		$image_id = absint( $slide['image_id'] ?? 0 );
		if ( ! $image_id ) {
			continue;
		}

		$clean[] = array(
			'image_id'      => $image_id,
			'link'          => esc_url_raw( $slide['link'] ?? '' ),
			'alt'           => sanitize_text_field( $slide['alt'] ?? '' ),
			'title_left'    => sanitize_text_field( $slide['title_left'] ?? '' ),
			'subtitle_left' => sanitize_text_field( $slide['subtitle_left'] ?? '' ),
			'title_right'   => sanitize_text_field( $slide['title_right'] ?? '' ),
		);
	}

	return $clean;
}

/**
 * Sanitize home page product sections.
 *
 * @param array<int, array<string, mixed>> $sections Raw sections.
 * @return array<int, array<string, mixed>>
 */
function buity_sanitize_home_sections( $sections ) {
	$clean   = array();
	$sources = array_keys( buity_home_section_source_choices() );

	foreach ( $sections as $section ) {
		if ( ! is_array( $section ) ) {
			continue;
		}

		$title = sanitize_text_field( $section['title'] ?? '' );
		if ( '' === $title ) {
			continue;
		}

		$source = sanitize_key( $section['source'] ?? 'popular' );
		if ( ! in_array( $source, $sources, true ) ) {
			$source = 'popular';
		}

		$clean[] = array(
			'title'          => $title,
			'source'         => $source,
			'category_id'    => absint( $section['category_id'] ?? 0 ),
			'limit'          => max( 1, min( 24, absint( $section['limit'] ?? 10 ) ) ),
			'view_all_text'  => sanitize_text_field( $section['view_all_text'] ?? __( 'View All', 'buity-theme' ) ),
			'view_all_url'   => esc_url_raw( $section['view_all_url'] ?? '' ),
		);

		if ( count( $clean ) >= 12 ) {
			break;
		}
	}

	return $clean;
}

/**
 * Add Theme Settings under Appearance.
 */
function buity_theme_settings_menu() {
	add_theme_page(
		__( 'Theme Settings', 'buity-theme' ),
		__( 'Theme Settings', 'buity-theme' ),
		'manage_options',
		BUITY_THEME_SETTINGS_PAGE,
		'buity_theme_settings_page_render'
	);
}
add_action( 'admin_menu', 'buity_theme_settings_menu' );

/**
 * Enqueue admin assets on the settings page.
 *
 * @param string $hook_suffix Current admin page hook.
 */
function buity_theme_settings_admin_assets( $hook_suffix ) {
	if ( 'appearance_page_' . BUITY_THEME_SETTINGS_PAGE !== $hook_suffix ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );

	wp_enqueue_style(
		'buity-theme-settings-admin',
		BUITY_THEME_URI . '/assets/css/admin-theme-options.css',
		array(),
		buity_asset_version( 'assets/css/admin-theme-options.css' )
	);

	wp_enqueue_script(
		'buity-theme-settings-admin',
		BUITY_THEME_URI . '/assets/js/admin-theme-options.js',
		array( 'jquery', 'wp-color-picker' ),
		buity_asset_version( 'assets/js/admin-theme-options.js' ),
		true
	);

	wp_localize_script(
		'buity-theme-settings-admin',
		'buityThemeSettingsAdmin',
		array(
			'maxHeroSlides'    => 10,
			'maxHomeSections'  => 12,
			'heroLabel'        => __( 'Slide', 'buity-theme' ),
			'sectionLabel'     => __( 'Section', 'buity-theme' ),
			'selectImage'      => __( 'Select image', 'buity-theme' ),
			'useImage'         => __( 'Use image', 'buity-theme' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'buity_theme_settings_admin_assets' );

/**
 * Field definitions grouped by tab and section.
 *
 * @return array<string, array<string, array<int, array<string, mixed>>>>
 */
function buity_theme_settings_fields() {
	$menus = wp_get_nav_menus();
	$menu_choices = array( 0 => __( '— Select menu —', 'buity-theme' ) );
	foreach ( $menus as $menu ) {
		$menu_choices[ (int) $menu->term_id ] = $menu->name;
	}

	return array(
		'general' => array(
			'logo' => array(
				array(
					'key'   => 'logo_header',
					'label' => __( 'Header Logo', 'buity-theme' ),
					'type'  => 'image',
				),
				array(
					'key'   => 'logo_footer',
					'label' => __( 'Footer Logo', 'buity-theme' ),
					'type'  => 'image',
				),
				array(
					'key'         => 'logo_site',
					'label'       => __( 'Site Logo (Fallback)', 'buity-theme' ),
					'type'        => 'image',
					'description' => __( 'Used when a context-specific logo is not set. Falls back to the Customizer site logo if empty.', 'buity-theme' ),
				),
				array(
					'key'   => 'logo_alt_text',
					'label' => __( 'Logo Alt Text', 'buity-theme' ),
					'type'  => 'text',
				),
			),
			'footer_content' => array(
				array(
					'key'   => 'footer_description',
					'label' => __( 'Footer Description', 'buity-theme' ),
					'type'  => 'textarea',
				),
			),
			'footer_copyright' => array(
				array(
					'key'         => 'copyright_line',
					'label'       => __( 'Copyright Line', 'buity-theme' ),
					'type'        => 'text',
					'description' => __( 'Leave empty for the default “Copyright © {year} …” line.', 'buity-theme' ),
				),
				array(
					'key'   => 'copyright_site_name',
					'label' => __( 'Site Name In Copyright', 'buity-theme' ),
					'type'  => 'checkbox',
				),
				array(
					'key'   => 'powered_by_text',
					'label' => __( 'Powered By Link Text', 'buity-theme' ),
					'type'  => 'text',
				),
				array(
					'key'   => 'powered_by_url',
					'label' => __( 'Powered By Link URL', 'buity-theme' ),
					'type'  => 'url',
				),
			),
			'footer_links' => array(
				array(
					'key'   => 'quick_links_heading',
					'label' => __( 'Quick Links Heading', 'buity-theme' ),
					'type'  => 'text',
				),
				array(
					'key'     => 'quick_links_menu',
					'label'   => __( 'Quick Links Menu', 'buity-theme' ),
					'type'    => 'select',
					'choices' => $menu_choices,
				),
				array(
					'key'   => 'useful_links_heading',
					'label' => __( 'Useful Links Heading', 'buity-theme' ),
					'type'  => 'text',
				),
				array(
					'key'     => 'useful_links_menu',
					'label'   => __( 'Useful Links Menu', 'buity-theme' ),
					'type'    => 'select',
					'choices' => $menu_choices,
				),
			),
			'header_search' => array(
				array(
					'key'   => 'search_placeholder',
					'label' => __( 'Search Placeholder', 'buity-theme' ),
					'type'  => 'text',
				),
				array(
					'key'   => 'search_no_results',
					'label' => __( 'No Results Message', 'buity-theme' ),
					'type'  => 'text',
				),
				array(
					'key'   => 'mobile_search_button',
					'label' => __( 'Mobile Search Button', 'buity-theme' ),
					'type'  => 'text',
				),
				array(
					'key'         => 'mobile_search_suggestions',
					'label'       => __( 'Mobile Search Suggestions', 'buity-theme' ),
					'type'        => 'textarea',
					'description' => __( 'One suggestion per line. Shown as autocomplete hints on mobile search.', 'buity-theme' ),
				),
			),
		),
		'contact' => array(
			'contact' => array(
				array(
					'key'   => 'contact_email',
					'label' => __( 'Email', 'buity-theme' ),
					'type'  => 'email',
				),
				array(
					'key'   => 'contact_phone',
					'label' => __( 'Phone', 'buity-theme' ),
					'type'  => 'text',
				),
				array(
					'key'   => 'contact_address',
					'label' => __( 'Address', 'buity-theme' ),
					'type'  => 'textarea',
				),
			),
			'social' => array(
				array(
					'key'   => 'social_facebook',
					'label' => __( 'Facebook', 'buity-theme' ),
					'type'  => 'url',
				),
				array(
					'key'   => 'social_instagram',
					'label' => __( 'Instagram', 'buity-theme' ),
					'type'  => 'url',
				),
				array(
					'key'   => 'social_youtube',
					'label' => __( 'YouTube', 'buity-theme' ),
					'type'  => 'url',
				),
				array(
					'key'         => 'social_whatsapp',
					'label'       => __( 'WhatsApp Number', 'buity-theme' ),
					'type'        => 'text',
					'description' => __( 'Digits only with country code, e.g. 8801712345678', 'buity-theme' ),
				),
			),
		),
		'colors' => array(
			'site_colors' => array(
				array(
					'key'   => 'color_primary',
					'label' => __( 'Primary Color (Teal)', 'buity-theme' ),
					'type'  => 'color',
				),
				array(
					'key'   => 'color_secondary',
					'label' => __( 'Secondary Color (Pink)', 'buity-theme' ),
					'type'  => 'color',
				),
				array(
					'key'   => 'color_tertiary',
					'label' => __( 'Tertiary Accent Color', 'buity-theme' ),
					'type'  => 'color',
				),
			),
		),
		'shop' => array(
			'shop' => array(
				array(
					'key'         => 'shop_products_per_page',
					'label'       => __( 'Products per page', 'buity-theme' ),
					'type'        => 'number',
					'description' => __( 'Number of products shown on shop and category archives.', 'buity-theme' ),
				),
			),
		),
	);
}

/**
 * Human-readable section titles.
 *
 * @return array<string, string>
 */
function buity_theme_settings_section_labels() {
	return array(
		'logo'             => __( 'Logo Settings', 'buity-theme' ),
		'footer_content'   => __( 'Footer Content', 'buity-theme' ),
		'footer_copyright' => __( 'Footer Copyright', 'buity-theme' ),
		'footer_links'     => __( 'Footer Link Columns', 'buity-theme' ),
		'header_search'    => __( 'Header Search', 'buity-theme' ),
		'contact'          => __( 'Contact Information', 'buity-theme' ),
		'social'           => __( 'Social Links', 'buity-theme' ),
		'site_colors'      => __( 'Site Colors', 'buity-theme' ),
		'shop'             => __( 'Shop', 'buity-theme' ),
	);
}

/**
 * Tab labels.
 *
 * @return array<string, string>
 */
function buity_theme_settings_tabs() {
	return array(
		'general' => __( 'General', 'buity-theme' ),
		'contact' => __( 'Contact & Social', 'buity-theme' ),
		'colors'        => __( 'Colors', 'buity-theme' ),
		'hero_slider'   => __( 'Hero Slider', 'buity-theme' ),
		'home_sections' => __( 'Home Sections', 'buity-theme' ),
		'shop'          => __( 'Shop', 'buity-theme' ),
	);
}

/**
 * Render a single settings field.
 *
 * @param array<string, mixed> $field Field config.
 * @param array<string, mixed> $values Current values.
 */
function buity_theme_settings_render_field( $field, $values ) {
	$key   = $field['key'];
	$type  = $field['type'];
	$value = $values[ $key ] ?? buity_get_option( $key );
	$name  = BUITY_THEME_SETTINGS_OPTION . '[' . esc_attr( $key ) . ']';
	$id    = 'buity-field-' . esc_attr( $key );

	echo '<tr>';
	echo '<th scope="row"><label for="' . esc_attr( $id ) . '">' . esc_html( $field['label'] ) . '</label></th>';
	echo '<td>';

	switch ( $type ) {
		case 'image':
			$attachment_id = (int) $value;
			$preview_url   = $attachment_id ? wp_get_attachment_image_url( $attachment_id, 'medium' ) : '';
			echo '<div class="buity-media-field" data-field="' . esc_attr( $key ) . '">';
			echo '<input type="hidden" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( (string) $attachment_id ) . '" />';
			echo '<div class="buity-media-field__preview">';
			if ( $preview_url ) {
				echo '<img src="' . esc_url( $preview_url ) . '" alt="" />';
			}
			echo '</div>';
			echo '<p class="buity-media-field__actions">';
			echo '<button type="button" class="button buity-media-upload">' . esc_html__( 'Select image', 'buity-theme' ) . '</button> ';
			echo '<button type="button" class="button buity-media-remove' . ( $attachment_id ? '' : ' hidden' ) . '">' . esc_html__( 'Remove', 'buity-theme' ) . '</button>';
			echo '</p></div>';
			break;

		case 'textarea':
			echo '<textarea class="large-text" rows="4" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '">' . esc_textarea( (string) $value ) . '</textarea>';
			break;

		case 'checkbox':
			echo '<label><input type="checkbox" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="1" ' . checked( (int) $value, 1, false ) . ' /> ';
			echo esc_html__( 'Include site name in the copyright line', 'buity-theme' ) . '</label>';
			break;

		case 'select':
			echo '<select id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '">';
			foreach ( (array) ( $field['choices'] ?? array() ) as $choice_id => $choice_label ) {
				printf(
					'<option value="%1$s" %2$s>%3$s</option>',
					esc_attr( (string) $choice_id ),
					selected( (int) $value, (int) $choice_id, false ),
					esc_html( $choice_label )
				);
			}
			echo '</select>';
			break;

		case 'color':
			echo '<input type="text" class="buity-color-picker" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( (string) $value ) . '" data-default-color="' . esc_attr( buity_theme_settings_defaults()[ $key ] ?? '' ) . '" />';
			break;

		case 'url':
			echo '<input type="url" class="regular-text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( (string) $value ) . '" />';
			break;

		case 'email':
			echo '<input type="email" class="regular-text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( (string) $value ) . '" />';
			break;

		case 'number':
			echo '<input type="number" class="small-text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( (string) $value ) . '" min="1" max="100" step="1" />';
			break;

		default:
			echo '<input type="text" class="regular-text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( (string) $value ) . '" />';
			break;
	}

	if ( ! empty( $field['description'] ) ) {
		echo '<p class="description">' . esc_html( $field['description'] ) . '</p>';
	}

	echo '</td></tr>';
}

/**
 * Render one hero slide row in admin.
 *
 * @param int                  $index Slide index.
 * @param array<string, mixed> $slide Slide data.
 */
function buity_theme_settings_render_hero_slide_row( $index, $slide ) {
	$prefix    = BUITY_THEME_SETTINGS_OPTION . '[hero_slides][' . $index . ']';
	$image_id  = (int) ( $slide['image_id'] ?? 0 );
	$preview   = $image_id ? wp_get_attachment_image_url( $image_id, 'medium' ) : '';
	$row_label = sprintf( __( 'Slide %d', 'buity-theme' ), $index + 1 );
	?>
	<div class="buity-repeater__item buity-hero-slide" data-index="<?php echo esc_attr( (string) $index ); ?>">
		<div class="buity-repeater__item-head">
			<strong><?php echo esc_html( $row_label ); ?></strong>
			<button type="button" class="button-link buity-repeater__remove"><?php esc_html_e( 'Remove', 'buity-theme' ); ?></button>
		</div>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php esc_html_e( 'Image', 'buity-theme' ); ?></th>
				<td>
					<div class="buity-media-field" data-field="hero-image">
						<input type="hidden" name="<?php echo esc_attr( $prefix ); ?>[image_id]" value="<?php echo esc_attr( (string) $image_id ); ?>" />
						<div class="buity-media-field__preview">
							<?php if ( $preview ) : ?>
								<img src="<?php echo esc_url( $preview ); ?>" alt="" />
							<?php endif; ?>
						</div>
						<p class="buity-media-field__actions">
							<button type="button" class="button buity-media-upload"><?php esc_html_e( 'Upload / Select', 'buity-theme' ); ?></button>
							<button type="button" class="button buity-media-remove<?php echo $image_id ? '' : ' hidden'; ?>"><?php esc_html_e( 'Remove', 'buity-theme' ); ?></button>
						</p>
						<p class="description"><?php esc_html_e( 'Required for the home page slider.', 'buity-theme' ); ?></p>
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row"><label><?php esc_html_e( 'Link URL', 'buity-theme' ); ?></label></th>
				<td>
					<input type="url" class="regular-text" name="<?php echo esc_attr( $prefix ); ?>[link]" value="<?php echo esc_attr( $slide['link'] ?? '' ); ?>" />
					<p class="description"><?php esc_html_e( 'Optional. Wraps the slide image in a link when set.', 'buity-theme' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label><?php esc_html_e( 'Alt text', 'buity-theme' ); ?></label></th>
				<td>
					<input type="text" class="regular-text" name="<?php echo esc_attr( $prefix ); ?>[alt]" value="<?php echo esc_attr( $slide['alt'] ?? '' ); ?>" />
				</td>
			</tr>
		</table>
	</div>
	<?php
}

/**
 * Render Hero Slider settings tab.
 *
 * @param array<string, mixed> $values Saved settings.
 */
function buity_theme_settings_render_hero_slider_tab( $values ) {
	$slides = isset( $values['hero_slides'] ) && is_array( $values['hero_slides'] ) ? $values['hero_slides'] : array();
	?>
	<p class="description buity-repeater__intro">
		<?php esc_html_e( 'Home page slider: add slides here (no default images). Each slide needs an uploaded image. The slider appears on the home page only after you save at least one slide with an image.', 'buity-theme' ); ?>
	</p>
	<div class="buity-repeater" id="buity-hero-slides" data-repeater="hero">
		<?php
		if ( empty( $slides ) ) {
			buity_theme_settings_render_hero_slide_row( 0, array() );
		} else {
			foreach ( $slides as $index => $slide ) {
				buity_theme_settings_render_hero_slide_row( (int) $index, $slide );
			}
		}
		?>
	</div>
	<p><button type="button" class="button" id="buity-add-hero-slide">+ <?php esc_html_e( 'Add Slide', 'buity-theme' ); ?></button></p>
	<?php
}

/**
 * Render one home section row in admin.
 *
 * @param int                  $index   Section index.
 * @param array<string, mixed> $section Section data.
 */
function buity_theme_settings_render_home_section_row( $index, $section ) {
	$prefix       = BUITY_THEME_SETTINGS_OPTION . '[home_sections][' . $index . ']';
	$sources      = buity_home_section_source_choices();
	$categories   = array( 0 => __( '— Select category —', 'buity-theme' ) );
	$current_src  = $section['source'] ?? 'popular';
	$category_id  = (int) ( $section['category_id'] ?? 0 );

	if ( taxonomy_exists( 'product_cat' ) ) {
		$terms = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
				'number'     => 100,
			)
		);
		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$categories[ (int) $term->term_id ] = $term->name;
			}
		}
	}

	$row_label = sprintf( __( 'Section %d', 'buity-theme' ), $index + 1 );
	?>
	<div class="buity-repeater__item buity-home-section" data-index="<?php echo esc_attr( (string) $index ); ?>">
		<div class="buity-repeater__item-head">
			<strong><?php echo esc_html( $row_label ); ?></strong>
			<button type="button" class="button-link buity-repeater__remove"><?php esc_html_e( 'Remove', 'buity-theme' ); ?></button>
		</div>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><label><?php esc_html_e( 'Title', 'buity-theme' ); ?></label></th>
				<td>
					<input type="text" class="regular-text" name="<?php echo esc_attr( $prefix ); ?>[title]" value="<?php echo esc_attr( $section['title'] ?? '' ); ?>" />
					<p class="description"><?php esc_html_e( 'Required. Shown as the heading on the home page.', 'buity-theme' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><label><?php esc_html_e( 'Product source', 'buity-theme' ); ?></label></th>
				<td>
					<select name="<?php echo esc_attr( $prefix ); ?>[source]" class="buity-home-section-source">
						<?php foreach ( $sources as $source_key => $source_label ) : ?>
							<option value="<?php echo esc_attr( $source_key ); ?>" <?php selected( $current_src, $source_key ); ?>><?php echo esc_html( $source_label ); ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr class="buity-home-section-category-row<?php echo 'category' === $current_src ? '' : ' hidden'; ?>">
				<th scope="row"><label><?php esc_html_e( 'Category', 'buity-theme' ); ?></label></th>
				<td>
					<select name="<?php echo esc_attr( $prefix ); ?>[category_id]">
						<?php foreach ( $categories as $cat_id => $cat_label ) : ?>
							<option value="<?php echo esc_attr( (string) $cat_id ); ?>" <?php selected( $category_id, (int) $cat_id ); ?>><?php echo esc_html( $cat_label ); ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label><?php esc_html_e( 'Number of products', 'buity-theme' ); ?></label></th>
				<td>
					<input type="number" class="small-text" name="<?php echo esc_attr( $prefix ); ?>[limit]" value="<?php echo esc_attr( (string) ( $section['limit'] ?? 10 ) ); ?>" min="1" max="24" step="1" />
				</td>
			</tr>
			<tr>
				<th scope="row"><label><?php esc_html_e( 'View All button text', 'buity-theme' ); ?></label></th>
				<td>
					<input type="text" class="regular-text" name="<?php echo esc_attr( $prefix ); ?>[view_all_text]" value="<?php echo esc_attr( $section['view_all_text'] ?? __( 'View All', 'buity-theme' ) ); ?>" />
				</td>
			</tr>
			<tr>
				<th scope="row"><label><?php esc_html_e( 'View All URL', 'buity-theme' ); ?></label></th>
				<td>
					<input type="url" class="regular-text" name="<?php echo esc_attr( $prefix ); ?>[view_all_url]" value="<?php echo esc_attr( $section['view_all_url'] ?? '' ); ?>" />
					<p class="description"><?php esc_html_e( 'Leave empty to auto-link based on product source (category, sale, etc.).', 'buity-theme' ); ?></p>
				</td>
			</tr>
		</table>
	</div>
	<?php
}

/**
 * Render Home Sections settings tab.
 *
 * @param array<string, mixed> $values Saved settings.
 */
function buity_theme_settings_render_home_sections_tab( $values ) {
	$sections = isset( $values['home_sections'] ) && is_array( $values['home_sections'] ) ? $values['home_sections'] : array();
	?>
	<p class="description buity-repeater__intro">
		<?php esc_html_e( 'Add product rows on the home page. Enter a title for each section, then click Save Changes (up to 12).', 'buity-theme' ); ?>
	</p>
	<div class="buity-repeater" id="buity-home-sections" data-repeater="home">
		<?php
		if ( empty( $sections ) ) {
			buity_theme_settings_render_home_section_row( 0, array() );
		} else {
			foreach ( $sections as $index => $section ) {
				buity_theme_settings_render_home_section_row( (int) $index, $section );
			}
		}
		?>
	</div>
	<p><button type="button" class="button" id="buity-add-home-section">+ <?php esc_html_e( 'Add Section', 'buity-theme' ); ?></button></p>
	<?php
}

/**
 * Render Theme Settings admin page.
 */
function buity_theme_settings_page_render() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$tabs         = buity_theme_settings_tabs();
	$active_tab   = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'general'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( ! isset( $tabs[ $active_tab ] ) ) {
		$active_tab = 'general';
	}

	$fields       = buity_theme_settings_fields();
	$sections     = buity_theme_settings_section_labels();
	$values       = wp_parse_args( get_option( BUITY_THEME_SETTINGS_OPTION, array() ), buity_theme_settings_defaults() );
	$page_url     = admin_url( 'themes.php?page=' . BUITY_THEME_SETTINGS_PAGE );
	?>
	<div class="wrap buity-theme-settings">
		<h1><?php esc_html_e( 'Theme Settings', 'buity-theme' ); ?></h1>

		<?php if ( isset( $_GET['settings-updated'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
			<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Settings saved.', 'buity-theme' ); ?></p></div>
		<?php endif; ?>

		<nav class="nav-tab-wrapper buity-theme-settings__tabs" aria-label="<?php esc_attr_e( 'Settings tabs', 'buity-theme' ); ?>">
			<?php foreach ( $tabs as $tab_id => $tab_label ) : ?>
				<a href="<?php echo esc_url( add_query_arg( 'tab', $tab_id, $page_url ) ); ?>" class="nav-tab <?php echo $active_tab === $tab_id ? 'nav-tab-active' : ''; ?>">
					<?php echo esc_html( $tab_label ); ?>
				</a>
			<?php endforeach; ?>
		</nav>

		<form method="post" action="options.php" class="buity-theme-settings__form">
			<?php settings_fields( 'buity_theme_settings_group' ); ?>
			<input type="hidden" name="buity_active_tab" value="<?php echo esc_attr( $active_tab ); ?>" />

			<?php
			if ( 'hero_slider' === $active_tab ) {
				buity_theme_settings_render_hero_slider_tab( $values );
			} elseif ( 'home_sections' === $active_tab ) {
				buity_theme_settings_render_home_sections_tab( $values );
			} else {
				$tab_sections = $fields[ $active_tab ] ?? array();
				foreach ( $tab_sections as $section_id => $section_fields ) :
					$section_title = $sections[ $section_id ] ?? $section_id;
					?>
					<h2 class="title"><?php echo esc_html( $section_title ); ?></h2>
					<table class="form-table" role="presentation">
						<?php
						foreach ( $section_fields as $field ) {
							buity_theme_settings_render_field( $field, $values );
						}
						?>
					</table>
				<?php endforeach; ?>
			<?php } ?>

			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

/**
 * Attachment ID for a logo context.
 *
 * @param string $context header|footer|site.
 * @return int
 */
function buity_get_logo_id( $context = 'header' ) {
	$map = array(
		'header' => 'logo_header',
		'footer' => 'logo_footer',
		'site'   => 'logo_site',
	);
	$key = $map[ $context ] ?? 'logo_site';
	$id  = (int) buity_get_option( $key );

	if ( ! $id && 'site' !== $context ) {
		$id = (int) buity_get_option( 'logo_site' );
	}
	if ( ! $id && function_exists( 'get_theme_mod' ) ) {
		$id = (int) get_theme_mod( 'custom_logo', 0 );
	}

	return $id;
}

/**
 * Print site logo markup.
 *
 * @param string $context header|footer.
 */
function buity_the_logo( $context = 'header' ) {
	$attachment_id = buity_get_logo_id( $context );
	$home_url      = home_url( '/' );
	$alt           = buity_get_option( 'logo_alt_text' );
	if ( ! $alt ) {
		$alt = get_bloginfo( 'name', 'display' );
	}

	if ( $attachment_id ) {
		$image = wp_get_attachment_image(
			$attachment_id,
			'full',
			false,
			array(
				'class'   => 'custom-logo',
				'alt'     => $alt,
				'loading' => 'lazy',
			)
		);
		if ( $image ) {
			printf(
				'<a href="%1$s" class="custom-logo-link" rel="home">%2$s</a>',
				esc_url( $home_url ),
				$image // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
			return;
		}
	}

	if ( 'footer' === $context ) {
		echo '<span class="site-footer__logo-text">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
		return;
	}

	printf(
		'<a class="site-branding__title" href="%1$s" rel="home">%2$s</a>',
		esc_url( $home_url ),
		esc_html( get_bloginfo( 'name' ) )
	);
}

/**
 * WhatsApp URL from stored number or legacy URL theme mod.
 *
 * @return string
 */
function buity_get_whatsapp_url() {
	$number = preg_replace( '/\D+/', '', (string) buity_get_option( 'social_whatsapp' ) );
	if ( $number ) {
		return 'https://wa.me/' . $number;
	}

	$legacy = get_theme_mod( 'buity_social_whatsapp', '' );
	return $legacy ? (string) $legacy : '';
}

/**
 * Search suggestion lines for mobile datalist.
 *
 * @return string[]
 */
function buity_get_search_suggestions() {
	$raw = (string) buity_get_option( 'mobile_search_suggestions' );
	if ( ! $raw ) {
		return array();
	}
	$lines = preg_split( '/\r\n|\r|\n/', $raw );
	$lines = array_map( 'trim', $lines );
	return array_values( array_filter( $lines ) );
}

/**
 * Output dynamic color CSS variables.
 */
function buity_output_theme_colors() {
	$primary   = buity_get_option( 'color_primary' );
	$secondary = buity_get_option( 'color_secondary' );
	$tertiary  = buity_get_option( 'color_tertiary' );

	$primary_dark   = buity_adjust_hex_color( $primary, -0.12 );
	$secondary_dark = buity_adjust_hex_color( $secondary, -0.12 );

	$css = sprintf(
		':root{--color-primary:%1$s;--color-primary-dark:%2$s;--color-magenta:%3$s;--color-magenta-dark:%4$s;--color-tertiary:%5$s;--color-navy:%5$s;}',
		$primary,
		$primary_dark,
		$secondary,
		$secondary_dark,
		$tertiary
	);

	wp_add_inline_style( 'buity-theme', $css );
}
add_action( 'wp_enqueue_scripts', 'buity_output_theme_colors', 20 );

/**
 * Lighten or darken a hex color.
 *
 * @param string $hex     Hex color.
 * @param float  $percent Negative to darken, positive to lighten (-1 to 1).
 * @return string
 */
function buity_adjust_hex_color( $hex, $percent ) {
	$hex = ltrim( (string) $hex, '#' );
	if ( 6 !== strlen( $hex ) ) {
		return '#' . $hex;
	}

	$r = hexdec( substr( $hex, 0, 2 ) );
	$g = hexdec( substr( $hex, 2, 2 ) );
	$b = hexdec( substr( $hex, 4, 2 ) );

	$r = (int) max( 0, min( 255, $r + ( $r * $percent ) ) );
	$g = (int) max( 0, min( 255, $g + ( $g * $percent ) ) );
	$b = (int) max( 0, min( 255, $b + ( $b * $percent ) ) );

	return sprintf( '#%02x%02x%02x', $r, $g, $b );
}

/**
 * Build footer copyright HTML.
 *
 * @return string
 */
function buity_get_copyright_html() {
	$line = (string) buity_get_option( 'copyright_line' );
	if ( ! $line ) {
		$site_name = (int) buity_get_option( 'copyright_site_name' )
			? get_bloginfo( 'name' )
			: '';
		$line = sprintf(
			/* translators: 1: year, 2: site name */
			__( 'Copyright © %1$s %2$s. All rights reserved.', 'buity-theme' ),
			gmdate( 'Y' ),
			$site_name
		);
	}

	$powered_text = (string) buity_get_option( 'powered_by_text' );
	$powered_url  = (string) buity_get_option( 'powered_by_url' );
	if ( $powered_text && $powered_url ) {
		$line .= ' ' . sprintf(
			'<a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a>',
			esc_url( $powered_url ),
			esc_html( $powered_text )
		);
	} elseif ( $powered_text ) {
		$line .= ' ' . esc_html( $powered_text );
	}

	return $line;
}

/**
 * Render a footer nav menu from theme settings.
 *
 * @param string $menu_option_key Option key holding menu term ID.
 * @param string $fallback_cb     Fallback callback name.
 */
function buity_footer_nav_menu( $menu_option_key, $fallback_cb ) {
	$menu_id = (int) buity_get_option( $menu_option_key );

	if ( $menu_id > 0 ) {
		wp_nav_menu(
			array(
				'menu'       => $menu_id,
				'menu_class' => 'site-footer__links',
				'container'  => false,
				'depth'      => 1,
			)
		);
		return;
	}

	if ( is_callable( $fallback_cb ) ) {
		call_user_func( $fallback_cb );
	}
}

/**
 * Localize search strings for front-end scripts.
 */
function buity_localize_search_script() {
	wp_localize_script(
		'buity-search',
		'buitySearch',
		array(
			'noResults' => (string) buity_get_option( 'search_no_results' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'buity_localize_search_script', 25 );
