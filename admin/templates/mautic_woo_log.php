<div class="mauwoo-error-log-wrap">
	<div class="mauwoo-error-log-head">
		<div class="mauwoo-error-log-head-left">
			<h3><?php _e("Mautic WooCommerce Sync Log" , "mautic-woo") ;?></h3>
		</div>
		<div class="mauwoo-error-log-head-right">
			<a href="<?php echo admin_url('admin.php?page=mautic-woo&mauwoo_tab=mautic_woo_log&action=download_log') ;?>" class="mauwoo-sync-button"><?php _e("Download Log File" , "mautic-woo") ; ?>
		</a>
		<a href="<?php echo admin_url('admin.php?page=mautic-woo&mauwoo_tab=mautic_woo_log&action=clear_log') ;?>" class="mauwoo-sync-button"><?php _e("Clear Log File" , "mautic-woo") ; ?>
		</a>
		</div>
	</div>
	<div id="log-viewer" class="mautic-woo-log-viewer">
		<?php if ( file_exists( WC_LOG_DIR . 'mautic-woo-logs.log' ) ) { ?>
			<pre><?php echo esc_html( file_get_contents( WC_LOG_DIR . 'mautic-woo-logs.log' ) ); ?></pre>
		<?php } else { ?>
			<pre><strong><?php echo esc_html( "Log file:mautic-woo-logs.log not found", "mauwoo" ); ?></strong></pre>
		<?php } ?>
	</div>
</div>
