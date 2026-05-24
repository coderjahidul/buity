<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.8.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<?php
$current_status = isset( $_GET['order_status'] ) ? sanitize_text_field( $_GET['order_status'] ) : 'all';
$base_url = wc_get_endpoint_url( 'orders' );
?>
<div class="buity-orders-tabs">
	<a href="<?php echo esc_url( add_query_arg( 'order_status', 'all', $base_url ) ); ?>" class="buity-orders-tab <?php echo $current_status === 'all' ? 'active' : ''; ?>">All</a>
	<a href="<?php echo esc_url( add_query_arg( 'order_status', 'processing', $base_url ) ); ?>" class="buity-orders-tab <?php echo $current_status === 'processing' ? 'active' : ''; ?>">In Process</a>
	<a href="<?php echo esc_url( add_query_arg( 'order_status', 'pending', $base_url ) ); ?>" class="buity-orders-tab <?php echo $current_status === 'pending' ? 'active' : ''; ?>">Pending</a>
	<a href="<?php echo esc_url( add_query_arg( 'order_status', 'on-hold', $base_url ) ); ?>" class="buity-orders-tab <?php echo $current_status === 'on-hold' ? 'active' : ''; ?>">Confirmed</a>
	<a href="<?php echo esc_url( add_query_arg( 'order_status', 'completed', $base_url ) ); ?>" class="buity-orders-tab <?php echo $current_status === 'completed' ? 'active' : ''; ?>">Delivered</a>
	<a href="<?php echo esc_url( add_query_arg( 'order_status', 'cancelled', $base_url ) ); ?>" class="buity-orders-tab <?php echo $current_status === 'cancelled' ? 'active' : ''; ?>">Cancelled</a>
</div>

<?php if ( $has_orders ) : ?>

	<div class="buity-orders-list">
		<?php
		foreach ( $customer_orders->orders as $customer_order ) {
			$order      = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			$item_count = $order->get_item_count() - $order->get_item_count_refunded();
			$order_date = wc_format_datetime( $order->get_date_created(), 'M d, Y, g:i a' );
			$status     = $order->get_status();
			$status_name = wc_get_order_status_name( $status );
			
			// Get first item for image and title
			$items = $order->get_items();
			$first_item = reset($items);
			$product = $first_item ? $first_item->get_product() : null;
			$product_image = $product ? $product->get_image('thumbnail', array('class' => 'buity-order-card-img')) : wc_placeholder_img('thumbnail', array('class' => 'buity-order-card-img'));
			$product_name = $first_item ? $first_item->get_name() : 'Order #' . $order->get_order_number();
			
			// If there are multiple items, we might append "+ X more"
			if ( count($items) > 1 ) {
				$product_name .= ' (+' . (count($items) - 1) . ' items)';
			}
			?>
			<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="buity-order-card">
				<div class="buity-order-card-header">
					<div class="buity-order-status <?php echo esc_attr( $status ); ?>">
						<span style="display:inline-block; width:6px; height:6px; border-radius:50%; background:currentColor; margin-right:5px; vertical-align:middle;"></span>
						<?php echo esc_html( $status_name ); ?>
					</div>
					<div class="buity-order-date"><?php echo esc_html( $order_date ); ?></div>
				</div>
				<div class="buity-order-card-body">
					<div class="buity-order-card-img-wrap">
						<?php echo $product_image; ?>
					</div>
					<div class="buity-order-info">
						<div class="buity-order-id">Order ID: <?php echo esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() ); ?></div>
						<div class="buity-order-title"><?php echo esc_html( $product_name ); ?></div>
						<div class="buity-order-price"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></div>
					</div>
					<div class="buity-order-action">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</div>
				</div>
			</a>
			<?php
		}
		?>
	</div>

	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
			<?php if ( 1 !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'woocommerce' ); ?></a>
			<?php endif; ?>

			<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Next', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

<?php else : ?>

	<?php wc_print_notice( esc_html__( 'No order has been made yet.', 'woocommerce' ) . ' <a class="woocommerce-Button button" href="' . esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ) . '">' . esc_html__( 'Browse products', 'woocommerce' ) . '</a>', 'notice' ); ?>

<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
