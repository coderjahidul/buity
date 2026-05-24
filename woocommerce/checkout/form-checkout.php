<?php
/**
 * Buity custom checkout form — Kiddomart design.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( WC()->cart->is_empty() ) {
	wc_get_template( 'cart/cart-empty.php' );
	return;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

$customer = WC()->customer;
$cart     = WC()->cart;

// Billing info for address display
$billing_first   = $customer->get_billing_first_name();
$billing_last    = $customer->get_billing_last_name();
$billing_phone   = $customer->get_billing_phone();
$billing_email   = $customer->get_billing_email();
$billing_city    = $customer->get_billing_city();
$billing_state   = $customer->get_billing_state();
$billing_address = $customer->get_billing_address_1();
$billing_country = $customer->get_billing_country();
$billing_postcode= $customer->get_billing_postcode();
$billing_company = $customer->get_billing_company();

$full_name    = trim( $billing_first . ' ' . $billing_last );
$city_state   = trim( $billing_city . ( $billing_state ? ', ' . $billing_state : '' ) );

// Cart info
$cart_items = $cart->get_cart();
$item_count = $cart->get_cart_contents_count();

// Shipping packages
$packages       = WC()->shipping()->get_packages();
$chosen_methods = WC()->session->get( 'chosen_shipping_methods', array() );
?>

<div class="bco-wrap">

	<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

		<div class="bco-grid">

			<!-- ====== LEFT COLUMN ====== -->
			<div class="bco-col bco-col--left">

				<!-- Delivery Address -->
				<div class="bco-card" id="bco-address-card">
					<div class="bco-card__head">
						<h2 class="bco-card__title">Delivery Address</h2>
						<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-address' ) ); ?>" class="bco-manage-link">Manage Saved Address</a>
					</div>

					<!-- Address type tabs -->
					<div class="bco-addr-tabs" role="tablist">
						<button type="button" class="bco-addr-tab bco-addr-tab--active" data-tab="home" role="tab" aria-selected="true">
							<span class="bco-addr-tab__check" aria-hidden="true">
								<svg width="12" height="10" viewBox="0 0 12 10" fill="none"><path d="M1 5l3.5 3.5L11 1" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
							</span>
							Home
						</button>
						<button type="button" class="bco-addr-tab" data-tab="office" role="tab" aria-selected="false">Office</button>
						<button type="button" class="bco-addr-tab" data-tab="home-office" role="tab" aria-selected="false">Home Office</button>
						<button type="button" class="bco-addr-tab bco-addr-tab--add" data-tab="add" role="tab" aria-selected="false">
							<span aria-hidden="true">+</span> Add New Address
						</button>
					</div>

					<!-- Address display card -->
					<div class="bco-addr-box">
						<?php if ( $full_name ) : ?>
						<p class="bco-addr-name"><?php echo esc_html( $full_name ); ?></p>
						<?php endif; ?>
						<?php if ( $billing_phone ) : ?>
						<p class="bco-addr-row">
							<span class="bco-addr-icon" aria-hidden="true">
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 01.02 1.18 2 2 0 012 .02h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 14.92z"/></svg>
							</span>
							<?php echo esc_html( $billing_phone ); ?>
						</p>
						<?php endif; ?>
						<?php if ( $city_state ) : ?>
						<p class="bco-addr-row">
							<span class="bco-addr-icon" aria-hidden="true">
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
							</span>
							<?php echo esc_html( $city_state ); ?>
						</p>
						<?php endif; ?>
						<div class="bco-addr-foot">
							<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-address/billing' ) ); ?>" class="bco-btn-edit">
								<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
								Edit Address
							</a>
						</div>
					</div>

					<!-- Hidden WooCommerce billing fields (required for order processing) -->
					<?php foreach ( $checkout->get_checkout_fields( 'billing' ) as $key => $field ) :
						$val = $checkout->get_value( $key );
						if ( '' === $val || null === $val ) {
							// Fallback from customer object
							$method = 'get_' . $key;
							if ( method_exists( $customer, $method ) ) {
								$val = $customer->$method();
							}
						}
					?>
					<input type="hidden" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $val ); ?>" class="bco-billing-field">
					<?php endforeach; ?>
				</div><!-- /bco-address-card -->

				<!-- Payment Option -->
				<div class="bco-card" id="bco-payment-card">
					<h2 class="bco-card__title">Select a Payment Option</h2>
					<div class="bco-payment-list">
						<?php
						$gateways = WC()->payment_gateways()->get_available_payment_gateways();
						$first    = true;
						foreach ( $gateways as $gateway ) :
						?>
						<label class="bco-payment-method<?php echo $first ? ' bco-payment-method--active' : ''; ?>" for="bco_pm_<?php echo esc_attr( $gateway->id ); ?>">
							<input
								type="radio"
								id="bco_pm_<?php echo esc_attr( $gateway->id ); ?>"
								name="payment_method"
								value="<?php echo esc_attr( $gateway->id ); ?>"
								class="bco-payment-radio"
								<?php checked( $gateway->id, $checkout->get_value( 'payment_method' ) ?? ( $first ? $gateway->id : '' ) ); ?>
							>
							<span class="bco-payment-method__icon" aria-hidden="true">
								<?php if ( 'cod' === $gateway->id ) : ?>
								<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
								<?php else : ?>
								<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
								<?php endif; ?>
							</span>
							<span class="bco-payment-method__name"><?php echo esc_html( $gateway->get_title() ); ?></span>
						</label>
						<?php $first = false; endforeach; ?>
					</div>
				</div><!-- /bco-payment-card -->

				<!-- Shipping Option -->
				<?php if ( ! empty( $packages ) ) : ?>
				<div class="bco-card" id="bco-shipping-card">
					<h2 class="bco-card__title">Select Shipping Option</h2>
					<?php foreach ( $packages as $i => $package ) :
						if ( empty( $package['rates'] ) ) continue;
					?>
					<select name="shipping_method[<?php echo esc_attr( $i ); ?>]" class="bco-shipping-select" id="bco_shipping_<?php echo esc_attr( $i ); ?>">
						<?php foreach ( $package['rates'] as $rate ) : ?>
						<option value="<?php echo esc_attr( $rate->id ); ?>" <?php selected( isset( $chosen_methods[ $i ] ) ? $chosen_methods[ $i ] : '', $rate->id ); ?>>
							<?php echo esc_html( $rate->label ); ?>: <?php echo wc_price( $rate->cost ); ?>
						</option>
						<?php endforeach; ?>
					</select>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>

			</div><!-- /bco-col--left -->

			<!-- ====== RIGHT COLUMN ====== -->
			<div class="bco-col bco-col--right">
				<div class="bco-card bco-card--summary" id="bco-summary-card">

					<h2 class="bco-card__title">
						Order Items
						<span class="bco-item-count">(<?php echo esc_html( $item_count ); ?> <?php echo esc_html( _n( 'Item', 'Items', $item_count, 'buity-theme' ) ); ?>)</span>
					</h2>

					<!-- Cart items list -->
					<div class="bco-items" id="bco-items-list">
					<?php foreach ( $cart_items as $cart_item_key => $cart_item ) :
						$product  = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$qty      = $cart_item['quantity'];
						$line_total = $cart_item['line_total'];
						$thumb    = $product->get_image( array( 64, 64 ) );
						$name     = apply_filters( 'woocommerce_cart_item_name', $product->get_name(), $cart_item, $cart_item_key );
					?>
					<div class="bco-item" data-key="<?php echo esc_attr( $cart_item_key ); ?>">
						<div class="bco-item__thumb"><?php echo $thumb; ?></div>
						<div class="bco-item__meta">
							<p class="bco-item__name"><?php echo esc_html( $name ); ?></p>
							<div class="bco-item__qty">
								<button type="button" class="bco-qty__btn" data-action="minus" data-key="<?php echo esc_attr( $cart_item_key ); ?>" aria-label="Decrease quantity">−</button>
								<span class="bco-qty__num" id="bco_qty_<?php echo esc_attr( $cart_item_key ); ?>"><?php echo esc_html( $qty ); ?></span>
								<button type="button" class="bco-qty__btn" data-action="plus" data-key="<?php echo esc_attr( $cart_item_key ); ?>" aria-label="Increase quantity">+</button>
							</div>
						</div>
						<div class="bco-item__right">
							<button type="button" class="bco-item__remove" data-key="<?php echo esc_attr( $cart_item_key ); ?>" aria-label="Remove item">×</button>
							<span class="bco-item__price"><?php echo wc_price( $line_total ); ?></span>
						</div>
					</div>
					<?php endforeach; ?>
					</div><!-- /bco-items -->

					<!-- Totals -->
					<div class="bco-totals" id="bco-totals">
						<div class="bco-totals__row">
							<span class="bco-totals__label">Sub Total:</span>
							<span class="bco-totals__val"><?php echo $cart->get_cart_subtotal(); ?></span>
						</div>
						<div class="bco-totals__row">
							<span class="bco-totals__label">Discount:</span>
							<span class="bco-totals__val bco-totals__val--discount">
								<?php echo wc_price( $cart->get_discount_total() ); ?>
							</span>
						</div>
						<div class="bco-totals__row">
							<span class="bco-totals__label">Delivery Charge:</span>
							<span class="bco-totals__val"><?php echo wc_price( $cart->get_shipping_total() ); ?></span>
						</div>
						<div class="bco-totals__row bco-totals__row--grand">
							<span class="bco-totals__label"><strong>GrandTotal:</strong></span>
							<span class="bco-totals__val bco-totals__val--grand"><?php echo $cart->get_total(); ?></span>
						</div>
					</div>

					<!-- Coupon -->
					<div class="bco-coupon">
						<span class="bco-coupon__label">Coupon code</span>
						<div class="bco-coupon__links">
							<a href="#bco-coupon-form" class="bco-coupon__link" id="bco-coupon-toggle">Do have any coupon code?</a>
							<a href="#bco-coupon-form" class="bco-coupon__apply" id="bco-coupon-apply-link">Apply coupon</a>
						</div>
						<div class="bco-coupon__form" id="bco-coupon-form" hidden>
							<input type="text" name="coupon_code" id="bco-coupon-input" class="bco-coupon__input" placeholder="Enter coupon code" autocomplete="off">
							<button type="button" class="bco-coupon__btn" id="bco-coupon-submit">Apply</button>
						</div>
					</div>

					<!-- Actions -->
					<div class="bco-actions">
						<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="bco-btn-back">
							← Back to Cart
						</a>
						<button type="submit" id="place_order" name="woocommerce_checkout_place_order" value="Place order" class="bco-btn-confirm" data-value="Place order">
							Confirm Order
						</button>
					</div>

				</div><!-- /bco-card--summary -->
			</div><!-- /bco-col--right -->

		</div><!-- /bco-grid -->

		<!-- WooCommerce hidden fields (nonce, shipping, etc.) -->
		<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
		<input type="hidden" id="order_comments" name="order_comments" value="">

		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

	</form>

</div><!-- /bco-wrap -->

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
