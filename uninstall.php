<?php

// if not uninstalled plugin
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) 
	exit(); // out.


/*esle:
	if uninstalled plugin, this options will be deleted.
*/
delete_option('wpt_ptc_link');
delete_option('wpt_ptc_post_type');

?>