<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Makewebbetter_Onboarding
 * @subpackage Makewebbetter_Onboarding/admin/onboarding
 */

$form_fields = apply_filters( 'mwb_on_boarding_form_fields', array() );

?>

<?php if ( ! empty( $form_fields ) ) : ?>
	<div class="mwb-onboarding-section">
		<div class="mwb-on-boarding-wrapper-background">
			<div class="mwb-on-boarding-wrapper">
				<div class="mwb-on-boarding-close-btn">
					<a href="#">
						<span class="close-form">x</span>
					</a>
				</div>
				<h3 class="mwb-on-boarding-heading">Welcome to MakeWebBetter </h3>
				<p class="mwb-on-boarding-desc">We love making new friends! Subscribe below and we promise to keep you up-to-date with our latest new plugins, updates, awesome deals and a few special offers.</p>
				<form action="#" method="post" class="mwb-on-boarding-form">
					<?php foreach ( $form_fields as $key => $field_attr ) : ?>
						<?php $this->render_field_html( $field_attr ); ?>
					<?php endforeach; ?> 
					<div class="mwb-on-boarding-form-btn__wrapper">
						<div class="mwb-on-boarding-form-submit mwb-on-boarding-form-verify ">
							<input type="submit" class="mwb-on-boarding-submit mwb-on-boarding-verify " value="Send Us">
						</div>
						<div class="mwb-on-boarding-form-no_thanks">
							<a href="#" class="mwb-on-boarding-no_thanks"><?php esc_html_e( 'Skip For Now', 'enhanced-woocommerce-mautic-integration' ); ?></a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php endif; ?>
