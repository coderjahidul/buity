<?php
/**
 * View Order
 *
 * Shows the details of a particular order on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/view-order.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */

defined( 'ABSPATH' ) || exit;

$order_number  = $order->get_order_number();
$order_date    = wc_format_datetime( $order->get_date_created(), 'M d, Y, g:i a' );
$status        = $order->get_status();
$status_name   = wc_get_order_status_name( $status );
$payment_method = $order->get_payment_method_title();
?>

<a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>" class="buity-settings-back" style="margin-bottom: 15px;">
	<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
	Back to Orders
</a>

<!-- Order Details Header -->
<div class="buity-card buity-view-order-header">
	<div class="buity-view-order-title-row">
		<div>
			<h2 class="buity-view-order-title">Order Details</h2>
			<p class="buity-view-order-meta">
				<span class="buity-view-order-id">Order ID: #<?php echo esc_html( $order_number ); ?></span>
				<span class="buity-view-order-sep">&middot;</span>
				<span><?php echo esc_html( $order_date ); ?></span>
				<span class="buity-view-order-sep">&middot;</span>
				<span><?php echo esc_html( $payment_method ); ?></span>
			</p>
		</div>
		<div class="buity-order-status <?php echo esc_attr( $status ); ?>">
			<span style="display:inline-block; width:6px; height:6px; border-radius:50%; background:currentColor; margin-right:5px; vertical-align:middle;"></span>
			<?php echo esc_html( $status_name ); ?>
		</div>
	</div>
</div>

<!-- Order Items -->
<div class="buity-card buity-view-order-items">
	<h3 class="buity-card-title" style="margin-bottom: 15px;">Order Items</h3>
	
	<?php foreach ( $order->get_items() as $item_id => $item ) :
		$product = $item->get_product();
		$qty     = $item->get_quantity();
		$total   = $item->get_total();
	?>
		<div class="buity-view-order-item">
			<div class="buity-view-order-item-img">
				<?php
				if ( $product ) {
					echo $product->get_image( 'thumbnail' );
				} else {
					echo wc_placeholder_img( 'thumbnail' );
				}
				?>
			</div>
			<div class="buity-view-order-item-info">
				<div class="buity-view-order-item-name"><?php echo esc_html( $item->get_name() ); ?></div>
				<div class="buity-view-order-item-qty">Qty: <?php echo esc_html( $qty ); ?></div>
			</div>
			<div class="buity-view-order-item-price"><?php echo wp_kses_post( wc_price( $total ) ); ?></div>
		</div>
	<?php endforeach; ?>

	<!-- Order Summary -->
	<div class="buity-view-order-summary">
		<h3 class="buity-card-title" style="margin-bottom: 15px;">Order Summary</h3>
		
		<div class="buity-view-order-summary-row">
			<span class="buity-view-order-summary-label">Subtotal:</span>
			<span class="buity-view-order-summary-value"><?php echo wp_kses_post( wc_price( $order->get_subtotal() ) ); ?></span>
		</div>

		<?php if ( $order->get_shipping_total() > 0 ) : ?>
		<div class="buity-view-order-summary-row">
			<span class="buity-view-order-summary-label">Shipping:</span>
			<span class="buity-view-order-summary-value"><?php echo wp_kses_post( wc_price( $order->get_shipping_total() ) ); ?></span>
		</div>
		<?php endif; ?>

		<?php if ( $order->get_total_discount() > 0 ) : ?>
		<div class="buity-view-order-summary-row">
			<span class="buity-view-order-summary-label">Discount:</span>
			<span class="buity-view-order-summary-value">-<?php echo wp_kses_post( wc_price( $order->get_total_discount() ) ); ?></span>
		</div>
		<?php endif; ?>

		<div class="buity-view-order-summary-row buity-view-order-total">
			<span class="buity-view-order-summary-label"><strong>Total:</strong></span>
			<span class="buity-view-order-summary-value buity-view-order-total-value"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></span>
		</div>

		<div class="buity-view-order-summary-row">
			<span class="buity-view-order-summary-label">Payment method:</span>
			<span class="buity-view-order-summary-value"><?php echo esc_html( $payment_method ); ?></span>
		</div>
	</div>
</div>

<!-- Delivery Address -->
<div class="buity-card buity-view-order-address">
	<h3 class="buity-card-title" style="margin-bottom: 15px;">Delivery Address</h3>
	
	<div class="buity-view-order-address-box">
		<?php
		$billing_name  = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
		$billing_phone = $order->get_billing_phone();
		$billing_email = $order->get_billing_email();
		$billing_addr  = $order->get_formatted_billing_address();
		?>
		<div class="buity-view-order-address-name"><?php echo esc_html( trim( $billing_name ) ); ?></div>
		
		<?php if ( $billing_phone ) : ?>
			<p class="buity-view-order-address-detail"><strong>Phone:</strong> <?php echo esc_html( $billing_phone ); ?></p>
		<?php endif; ?>
		
		<?php if ( $billing_email ) : ?>
			<p class="buity-view-order-address-detail"><strong>Email:</strong> <?php echo esc_html( $billing_email ); ?></p>
		<?php endif; ?>
		
		<?php if ( $billing_addr ) : ?>
			<address class="buity-view-order-address-formatted">
				<?php echo wp_kses_post( $billing_addr ); ?>
			</address>
		<?php endif; ?>
	</div>
</div>
