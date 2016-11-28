<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit ();
}

/**
 * Delete all defined options
 */
delete_option( 'affilinet_platform' );
delete_option( 'affilinet_publisher_id' );
delete_option( 'affilinet_standard_webservice_password' );
delete_option( 'affilinet_product_data_webservice_password' );
delete_option( 'affilinet_webservice_login_is_correct' );

delete_option( 'affilinet_text_monetization' );
delete_option( 'affilinet_link_replacement' );
delete_option( 'affilinet_text_widget' );

delete_option( 'affilinet_extended_settings' );
delete_option( 'affilinet_ywidgetpos' );
delete_option( 'affilinet_ywdensity' );
delete_option( 'affilinet_ywcap' );
delete_option( 'affilinet_ywcolor' );



/**
 * unregister settings
 */
unregister_setting( 'affilinet-settings-group', 'affilinet_platform' );
unregister_setting( 'affilinet-settings-group', 'affilinet_publisher_id' );
unregister_setting( 'affilinet-settings-group', 'affilinet_standard_webservice_password' );
unregister_setting( 'affilinet-settings-group', 'affilinet_product_data_webservice_password' );
unregister_setting( 'affilinet-settings-group', 'affilinet_webservice_login_is_correct' );

unregister_setting('affilinet-settings-group', 'affilinet_text_monetization');
unregister_setting('affilinet-settings-group', 'affilinet_link_replacement');
unregister_setting('affilinet-settings-group', 'affilinet_text_widget');

unregister_setting('affilinet-settings-group', 'affilinet_extended_settings');
unregister_setting('affilinet-settings-group', 'affilinet_ywidgetpos');
unregister_setting('affilinet-settings-group', 'affilinet_ywdensity');
unregister_setting('affilinet-settings-group', 'affilinet_ywcap');
unregister_setting('affilinet-settings-group', 'affilinet_ywcolor');


