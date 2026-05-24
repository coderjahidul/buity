<?php
/**
 * My Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-address.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing'  => __( 'Billing address', 'woocommerce' ),
			'shipping' => __( 'Delivery address', 'woocommerce' ),
		),
		$customer_id
	);
} else {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing' => __( 'Billing address', 'woocommerce' ),
		),
		$customer_id
	);
}

$oldcol = 1;
$col    = 1;
?>

<div class="buity-card">
	<h3 class="buity-card-title" style="margin-bottom: 25px; font-size: 20px;">Delivery Address</h3>

	<div class="buity-address-grid">
		<?php foreach ( $get_addresses as $name => $address_title ) : ?>
			<?php
				$address = wc_get_account_formatted_address( $name );
			?>
			<div class="buity-address-card woocommerce-Address">
				<header class="buity-address-header woocommerce-Address-title title">
					<h3 class="buity-address-title"><?php echo esc_html( $address_title ); ?></h3>
					<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>" class="buity-address-edit edit"><?php echo $address ? esc_html__( 'Edit', 'woocommerce' ) : esc_html__( 'Add', 'woocommerce' ); ?></a>
				</header>
				<address class="buity-address-details">
					<?php
						echo $address ? wp_kses_post( $address ) : esc_html_e( 'You have not set up this type of address yet.', 'woocommerce' );
					?>
				</address>
			</div>
		<?php endforeach; ?>

		<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'shipping' ) ); ?>" class="buity-add-address-card">
			<div class="buity-add-address-icon">+</div>
			<div class="buity-add-address-title">Add New Address</div>
			<div class="buity-add-address-desc">Tap here to add or update your delivery details.</div>
		</a>
	</div>
</div>
