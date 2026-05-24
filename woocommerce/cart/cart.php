<?php
/**
 * Custom WooCommerce Cart Template
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_cart' ); ?>

<div class="buity-cart-page-wrap">
	<div class="buity-cart-header">
		<h1 class="buity-cart-title">My Cart</h1>
		<span class="buity-cart-count-badge" id="buity-cart-count-badge">
			<?php 
			$item_count = WC()->cart->get_cart_contents_count();
			echo sprintf( _n( '%d Item', '%d Items', $item_count, 'buity-theme' ), $item_count ); 
			?>
		</span>
	</div>

	<?php if ( WC()->cart->is_empty() ) : ?>
		<div class="buity-cart-empty">
			<p><?php esc_html_e( 'Your cart is currently empty.', 'woocommerce' ); ?></p>
			<p class="return-to-shop">
				<a class="button wc-backward btn" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect_url', wc_get_page_permalink( 'shop' ) ) ); ?>">
					<?php esc_html_e( 'Return to shop', 'woocommerce' ); ?>
				</a>
			</p>
		</div>
	<?php else : ?>
		<div class="buity-cart-grid">
			<!-- Left Column: Cart Form -->
			<div class="buity-cart-col-left">
				<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
					<?php do_action( 'woocommerce_before_cart_table' ); ?>

					<div class="buity-cart-table">
						<!-- Table Header -->
						<div class="buity-cart-table-header">
							<div class="buity-cart-header-checkbox">
								<label class="buity-custom-checkbox">
									<input type="checkbox" checked disabled>
									<span class="checkmark"></span>
								</label>
							</div>
							<div class="buity-cart-header-product">PRODUCT DETAILS</div>
							<div class="buity-cart-header-quantity">QUANTITY</div>
							<div class="buity-cart-header-price">PRICE</div>
							<div class="buity-cart-header-total">TOTAL</div>
							<div class="buity-cart-header-remove"></div>
						</div>

						<!-- Table Body -->
						<div class="buity-cart-table-body">
							<?php
							foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
								$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
								$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

								if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
									$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
									?>
									<div class="buity-cart-item woocommerce-cart-form__cart-item" data-key="<?php echo esc_attr( $cart_item_key ); ?>">
										<!-- Checkbox -->
										<div class="buity-cart-item-checkbox">
											<label class="buity-custom-checkbox">
												<input type="checkbox" class="cart-item-select" checked>
												<span class="checkmark"></span>
											</label>
										</div>

										<!-- Product Info -->
										<div class="buity-cart-item-product">
											<div class="buity-cart-item-image">
												<?php
												$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( array( 80, 80 ) ), $cart_item, $cart_item_key );

												if ( ! $product_permalink ) {
													echo $thumbnail;
												} else {
													printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
												}
												?>
											</div>
											<div class="buity-cart-item-details">
												<h3 class="buity-cart-item-title">
													<?php
													if ( ! $product_permalink ) {
														echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) );
													} else {
														echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
													}
													?>
												</h3>
												<?php
												$categories = wp_strip_all_tags( wc_get_product_category_list( $product_id, ', ' ) );
												if ( $categories ) {
													echo '<div class="buity-cart-item-category">Category: ' . esc_html( $categories ) . '</div>';
												}
												?>
											</div>
										</div>

										<!-- Quantity -->
										<div class="buity-cart-item-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
											<div class="buity-quantity-selector">
												<button type="button" class="buity-qty-btn buity-qty-minus" data-action="minus" data-key="<?php echo esc_attr( $cart_item_key ); ?>">−</button>
												<input type="number" 
													   class="input-text qty text buity-qty-input" 
													   step="<?php echo esc_attr( apply_filters( 'woocommerce_quantity_input_step', '1', $_product, true ) ); ?>" 
													   min="0" 
													   max="<?php echo esc_attr( $_product->get_max_purchase_quantity() ); ?>" 
													   name="cart[<?php echo esc_attr( $cart_item_key ); ?>][qty]" 
													   value="<?php echo esc_attr( $cart_item['quantity'] ); ?>" 
													   title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'woocommerce' ); ?>" 
													   size="4" 
													   placeholder="" 
													   inputmode="numeric" 
													   id="qty_<?php echo esc_attr( $cart_item_key ); ?>"
													   autocomplete="off" />
												<button type="button" class="buity-qty-btn buity-qty-plus" data-action="plus" data-key="<?php echo esc_attr( $cart_item_key ); ?>">+</button>
											</div>
										</div>

										<!-- Price -->
										<div class="buity-cart-item-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
											<?php
											if ( $_product->is_on_sale() && $_product->get_regular_price() ) {
												echo '<div class="buity-price-box">';
												echo '<span class="buity-price-current">' . wc_price( $_product->get_price() ) . '</span>';
												echo '<span class="buity-price-regular">' . wc_price( $_product->get_regular_price() ) . '</span>';
												echo '</div>';
											} else {
												echo '<span class="buity-price-current">' . apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ) . '</span>';
											}
											?>
										</div>

										<!-- Total -->
										<div class="buity-cart-item-total" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
											<span class="buity-item-subtotal-val">
												<?php
												echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
												?>
											</span>
										</div>

										<!-- Remove -->
										<div class="buity-cart-item-remove">
											<?php
											echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
												'woocommerce_cart_item_remove_link',
												sprintf(
													'<a href="%s" class="remove buity-remove-item" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-key="%s">✕</a>',
													esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
													/* translators: %s is the product name */
													esc_html( sprintf( __( 'Remove %s from cart', 'woocommerce' ), $_product->get_name() ) ),
													esc_attr( $product_id ),
													esc_attr( $_product->get_sku() ),
													esc_attr( $cart_item_key )
												),
												$cart_item_key
											);
											?>
										</div>
									</div>
									<?php
								}
							}
							?>
						</div>
					</div>

					<!-- WooCommerce Actions (Hidden fallback for standard updates) -->
					<div class="buity-cart-actions" style="display:none;">
						<button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>
						<?php do_action( 'woocommerce_cart_actions' ); ?>
						<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
					</div>

					<?php do_action( 'woocommerce_after_cart_table' ); ?>
				</form>
			</div>

			<!-- Right Column: Order Summary -->
			<div class="buity-cart-col-right">
				<div class="buity-order-summary-card">
					<h2 class="buity-summary-title">Order Summary</h2>

					<div class="buity-summary-totals">
						<div class="buity-summary-row">
							<span class="buity-summary-label">Sub Total</span>
							<span class="buity-summary-value buity-summary-subtotal"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
						</div>

						<?php 
						$discount_total = WC()->cart->get_discount_total();
						?>
						<div class="buity-summary-row buity-summary-row-discount" style="<?php echo ($discount_total > 0) ? '' : 'display:none;'; ?>">
							<span class="buity-summary-label">Discount</span>
							<span class="buity-summary-value buity-summary-discount"><?php echo wc_price( $discount_total ); ?></span>
						</div>

						<div class="buity-summary-row buity-summary-row-grand">
							<span class="buity-summary-label">GrandTotal</span>
							<span class="buity-summary-value buity-summary-total-val"><?php echo WC()->cart->get_total(); ?></span>
						</div>
					</div>

					<div class="buity-summary-checkout-action">
						<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="buity-btn-checkout">Checkout</a>
					</div>
				</div>
			</div>
		</div>

		<!-- You May Like / Cross Sells Section -->
		<section class="home-section home-products buity-cart-cross-sells">
			<div class="container">
				<div class="home-section__header">
					<h2 class="home-section__title">You May Like</h2>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="home-section__view-all">View All</a>
				</div>
				<?php
				$cross_sells = WC()->cart->get_cross_sells();

				if ( empty( $cross_sells ) ) {
					$args = array(
						'post_type'      => 'product',
						'posts_per_page' => 10,
						'orderby'        => 'rand',
					);
				} else {
					$args = array(
						'post_type'      => 'product',
						'posts_per_page' => 10,
						'post__in'       => $cross_sells,
					);
				}

				$query = new WP_Query( $args );

				if ( $query->have_posts() ) :
				?>
				<div class="home-products__scroll">
					<ul class="home-products__list">
						<?php
						while ( $query->have_posts() ) {
							$query->the_post();
							global $product;
							$product = wc_get_product( get_the_ID() );
							get_template_part( 'template-parts/shop/product-card' );
						}
						wp_reset_postdata();
						?>
					</ul>
				</div>
				<?php else : ?>
					<p class="buity-no-products"><?php esc_html_e( 'No recommended products found.', 'buity-theme' ); ?></p>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
