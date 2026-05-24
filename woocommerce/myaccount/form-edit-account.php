<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' ); ?>

<div class="buity-settings-header">
	<div>
		<a href="<?php echo esc_url( wc_get_endpoint_url( 'dashboard' ) ); ?>" class="buity-settings-back">
			<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
			Back to My Account
		</a>
		<h2 class="buity-settings-title">Settings</h2>
		<p class="buity-settings-subtitle">Manage your password and account preferences.</p>
	</div>
</div>

<div class="buity-card">
	<form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

		<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

		<!-- Hidden fields for required WooCommerce data -->
		<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first" style="display:none;">
			<label for="account_first_name"><?php esc_html_e( 'First name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" />
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last" style="display:none;">
			<label for="account_last_name"><?php esc_html_e( 'Last name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" />
		</p>
		<div class="clear"></div>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide" style="display:none;">
			<label for="account_display_name"><?php esc_html_e( 'Display name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" value="<?php echo esc_attr( $user->display_name ); ?>" />
		</p>
		<div class="clear"></div>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide" style="display:none;">
			<label for="account_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="email" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" />
		</p>

		<div class="buity-settings-grid">
			<!-- Password Manage -->
			<div class="buity-password-manage">
				<h3 style="font-size: 16px; font-weight: 600; color: #2d3748; margin-bottom: 20px;">Password Manage</h3>
				
				<div class="buity-form-group">
					<label class="buity-form-label" for="password_current"><?php esc_html_e( 'Old Password', 'woocommerce' ); ?></label>
					<div class="buity-form-input-icon">
						<input type="password" class="buity-form-control" name="password_current" id="password_current" autocomplete="off" placeholder="Leave blank to keep current" />
						<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 12C1 12 5 4 12 4C19 4 23 12 23 12C23 12 19 20 12 20C5 20 1 12 1 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</div>
				</div>

				<div class="buity-form-group">
					<label class="buity-form-label" for="password_1"><?php esc_html_e( 'Password', 'woocommerce' ); ?></label>
					<div class="buity-form-input-icon">
						<input type="password" class="buity-form-control" name="password_1" id="password_1" autocomplete="off" placeholder="New password" />
						<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 12C1 12 5 4 12 4C19 4 23 12 23 12C23 12 19 20 12 20C5 20 1 12 1 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</div>
				</div>

				<div class="buity-form-group">
					<label class="buity-form-label" for="password_2"><?php esc_html_e( 'Confirm Password', 'woocommerce' ); ?></label>
					<div class="buity-form-input-icon">
						<input type="password" class="buity-form-control" name="password_2" id="password_2" autocomplete="off" placeholder="Confirm new password" />
						<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 12C1 12 5 4 12 4C19 4 23 12 23 12C23 12 19 20 12 20C5 20 1 12 1 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</div>
				</div>

				<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
				<button type="submit" class="buity-btn-primary" name="save_account_details" value="<?php esc_attr_e( 'Save & Changes', 'woocommerce' ); ?>" style="width: 100%;"><?php esc_html_e( 'Save & Changes', 'woocommerce' ); ?></button>
				<input type="hidden" name="action" value="save_account_details" />
			</div>

			<!-- Account Status -->
			<div class="buity-account-status">
				<h3 style="font-size: 16px; font-weight: 600; color: #2d3748; margin-bottom: 20px;">Account Status</h3>
				<p class="buity-status-desc">Your account status is <strong>active</strong>. You can disable or inactive account below.</p>
				
				<div class="buity-form-group">
					<label class="buity-form-label">Explain the reason</label>
					<textarea class="buity-form-control" rows="4" placeholder="Write Reason for inactive account"></textarea>
				</div>
				
				<button type="button" class="buity-btn-danger">Deactivate Account</button>
				<p style="font-size: 11px; color: #a0aec0; margin-top: 15px; line-height: 1.4;">This does not close your account automatically. Contact the store to remove your account.</p>
			</div>
		</div>

		<?php do_action( 'woocommerce_edit_account_form' ); ?>

	</form>
</div>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
