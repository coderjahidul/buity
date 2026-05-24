<?php
/**
 * My Account Dashboard
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$current_user = wp_get_current_user();
$order_count  = wc_get_customer_order_count( $current_user->ID );
$member_since = date_i18n( get_option( 'date_format' ), strtotime( $current_user->user_registered ) );
$phone        = get_user_meta( $current_user->ID, 'billing_phone', true );

// Avatar initial
$avatar_letter = strtoupper( substr( $current_user->display_name, 0, 1 ) );
?>

<div class="buity-account-dashboard">
	<div class="buity-account-dashboard-left">
		<div class="buity-card" style="margin-bottom: 20px;">
			<div class="buity-card-header">
				<h3 class="buity-card-title">My Order</h3>
				<a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>" class="buity-btn-outline">Create Order</a>
			</div>
			<div style="text-align: center; padding: 30px 0;">
				<p style="color: #718096; margin-bottom: 20px;">You have <?php echo esc_html( $order_count ); ?> orders.</p>
				<a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>" class="buity-btn-primary">View All Orders</a>
			</div>
		</div>

		<div class="buity-card">
			<div class="buity-card-header">
				<h3 class="buity-card-title">My Wishlists</h3>
				<a href="#" class="buity-btn-outline">Add Wishlist</a>
			</div>
			<div style="text-align: center; padding: 40px 0;">
				<svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="opacity: 0.2;">
					<path d="M19 11H5M19 11C20.1046 11 21 11.8954 21 13V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19V13C3 11.8954 3.89543 11 5 11M19 11V9C19 7.89543 18.1046 7 17 7M5 11V9C5 7.89543 5.89543 7 7 7M7 7V5C7 3.89543 7.89543 3 9 3H15C16.1046 3 17 3.89543 17 5V7M7 7H17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					<path d="M12 14V18M10 16H14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</div>
		</div>
	</div>

	<div class="buity-account-dashboard-right">
		<div class="buity-card buity-user-card">
			<div class="buity-user-avatar"><?php echo esc_html( $avatar_letter ); ?></div>
			<div class="buity-user-name"><?php echo esc_html( $current_user->display_name ); ?></div>
			
			<div class="buity-user-details">
				<p><strong>Phone No:</strong> <?php echo esc_html( $phone ? $phone : 'Not set' ); ?></p>
				<p><strong>Email:</strong> <?php echo esc_html( $current_user->user_email ); ?></p>
				<p><strong>Member Since:</strong> <?php echo esc_html( $member_since ); ?></p>
			</div>
			
			<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account' ) ); ?>" class="buity-btn-outline" style="width: 100%; display: block; box-sizing: border-box;">Edit</a>
		</div>
	</div>
</div>

<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );
	do_action( 'woocommerce_before_my_account' );
	do_action( 'woocommerce_after_my_account' );
?>
