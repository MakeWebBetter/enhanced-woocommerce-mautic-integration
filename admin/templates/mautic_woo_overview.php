<?php 

/**
 * All mautic needed general settings.
 *
 * Template for showing/managing all the mautic general settings
 *
 * @since 1.0.0 
 */

$GLOBALS['hide_save_button']  = true;
?>
    <div class="mauwoo-overview-wrapper">
        <div class="mauwoo-overview-header mauwoo-common-header">
            <h2><?php _e("How our Integration works?", "mautic-woo") ?></h2>
        </div>
        <div class="mauwoo-overview-body">
            <div class="mauwoo-what-we-do mauwoo-overview-container">
                <h4><?php _e( "What we create?", "mauwoo" );?></h4>
                <div class="mauwoo-custom-fields">
                    <a class="mauwoo-anchors" href="#"><?php _e( "Contact Fields","mautic-woo")?></a>
                </div>
                <p class="mauwoo-desc-num">1</p>
            </div>
            <div class="mauwoo-how-easy-to-setup mauwoo-overview-container">
                <h4><?php _e( "How easy is it?", "mautic-woo" );?></h4>
                <div class="mauwoo-setup">
                    <a class="mauwoo-anchors" href="#"><?php _e( "Just 2 steps to Go!","mautic-woo")?></a>
                </div>
                <p class="mauwoo-desc-num">2</p>
            </div>
            <div class="mauwoo-what-you-achieve mauwoo-overview-container">
                <h4><?php _e( "What at the End?", "mautic-woo" );?></h4>
                <div class="mauwoo-automation">
                    <a class="mauwoo-anchors" href="#"><?php _e( "Automated Marketing","mautic-woo")?></a>
                </div>
                <p class="mauwoo-desc-num">3</p>
            </div>
        </div>
        <div class="mauwoo-overview-footer">
            <div class="mauwoo-overview-footer-content-2 mauwoo-footer-container">
                
                <?php
                    if( get_option( 'mautic_woo_get_started', false ) ) {
                        ?>
                            <a href="?page=mautic-woo&mauwoo_tab=mautic_woo_connect" class="mauwoo-button"><?php echo __( 'Next', 'mautic-woo' ) ?></a>
                        <?php
                    }
                    else {
                        ?>
                            <img width="40px" height="40px" src="<?php echo MAUTIC_WOO_URL . 'admin/images/right-direction-icon.png' ?>"/>
                            <a id="mauwoo-get-started" href="javascript:void(0)" class="mauwoo-button"><?php echo __( 'Get Started', 'mautic-woo' ) ?></a>
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>