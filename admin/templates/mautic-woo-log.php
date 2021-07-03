<?php
/**
 * Provide a admin settings page
 *
 * This file is used to markup the admin settings of the plugin.
 *
 * @package enhanced-woocommerce-mautic-integration
 */

$log_dir = WC_LOG_DIR . 'mautic-woo-logs.log';

if ( ! is_dir( $log_dir ) ) {

	//phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	$fread = file_get_contents( $log_dir );

}
?>
<div class="mauwoo-error-log-wrap">
	<div class="mauwoo-error-log-head">
		<div class="mauwoo-error-log-head-left">
			<h3><?php esc_html_e( 'Mautic WooCommerce Sync Log', 'enhanced-woocommerce-mautic-integration' ); ?></h3>
		</div>
		<div class="mauwoo-error-log-head-right">
			<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=mautic-woo&mauwoo_tab=mautic-woo-log&action=download_log' ), 'mautic-woo-get', 'mautic-woo-get' ) ); ?>"
				class="mauwoo-sync-button"><?php esc_html_e( 'Download Log File', 'enhanced-woocommerce-mautic-integration' ); ?>
			</a>
			<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=mautic-woo&mauwoo_tab=mautic-woo-log&action=clear_log' ), 'mautic-woo-get', 'mautic-woo-get' ) ); ?>"
				class="mauwoo-sync-button"><?php esc_html_e( 'Clear Log File', 'enhanced-woocommerce-mautic-integration' ); ?>
			</a>
		</div>
	</div>
	<div id="log-viewer" class="mautic-woo-log-viewer">
		<?php if ( file_exists( WC_LOG_DIR . 'mautic-woo-logs.log' ) ) { ?>
		<pre>
			<?php
				//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo esc_attr( $fread );
			?>
			</pre>
		<?php } else { ?>
		<pre><strong><?php esc_html_e( 'Log file:mautic-woo-logs.log not found', 'enhanced-woocommerce-mautic-integration' ); ?></strong></pre>
		<?php } ?>
	</div>
</div>
