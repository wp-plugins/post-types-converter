<?php
/*
Plugin Name: Post Types Converter
Plugin URI: http://wp-time.com/post-types-converter/
Description: One click convert post type easily, custom post type support.
Version: 1.0
Author: Qassim Hassan
Author URI: http://qass.im
License: GPLv2 or later
*/

/*  Copyright 2015 Qassim Hassan (email: qassim.pay@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


	function WPTime_post_types_converter_menu() {
		add_plugins_page( 'Post Types Converter', 'Post Types Converter', 'update_core', 'WPTime_post_types_converter_menu', 'WPTime_post_types_converter_page');
	}
	add_action( 'admin_menu', 'WPTime_post_types_converter_menu' );
	
	function WPTime_post_types_converter_register_settings() {
		register_setting( 'WPTime_post_types_converter_settings_fields', 'wpt_ptc_link' );
		register_setting( 'WPTime_post_types_converter_settings_fields', 'wpt_ptc_post_type' );
	}
	add_action( 'admin_init', 'WPTime_post_types_converter_register_settings' );
		
	function WPTime_post_types_converter_page(){ // page function
	
		$all_post_types = get_post_types( array( 'public' => true ), 'names' );
		$the_count = count($all_post_types);
		$get_post_types = '';
		
		foreach( $all_post_types as $the_post_type){
			$get_post_types .= '"'.$the_post_type.'", ';
		}
	
		if( get_option('wpt_ptc_link') and get_option('wpt_ptc_post_type') ){
			
			if( preg_match( '/^[0-9]+$/', get_option('wpt_ptc_link') ) ){
				$get_post_id = get_option('wpt_ptc_link');
			}else{
				$get_post_id = url_to_postid( get_option('wpt_ptc_link') );
			}
			
			$post_type = get_option('wpt_ptc_post_type');
			
			if( post_type_exists($post_type) ){
				
				global $wpdb;
				$convert = $wpdb->query(" SELECT * FROM $wpdb->posts WHERE ID = $get_post_id ");
				
				if( $convert !== 0 ){
					$wpdb->query(" UPDATE $wpdb->posts SET post_type = '$post_type' WHERE ID = $get_post_id ");
					$get_permalink = post_permalink($get_post_id);
					$class 	 = 'updated';
					$message = 'Has been converted! <a href="'.$get_permalink.'" target="_blank">Check</a> now.';
					delete_option('wpt_ptc_link');
					delete_option('wpt_ptc_post_type');
				}else{
					$class 	 = 'error';
					$message = 'Post not found, please try another Post Shortlink or Post ID.';
					delete_option('wpt_ptc_link');
					delete_option('wpt_ptc_post_type');
				}
				
			}else{
				
				$class 	 = 'error';
				$message = 'This post type "'.$post_type.'" is not registered, your registered post types is '.$get_post_types.' only.';
				
			}
		}else{
			
			$class 	 = 'error';
			$message = 'Please enter Post Shortlink or ID and Post Type.';
			
		}
		?>
			<div class="wrap">
				<h2>Post Types Converter</h2>
				<?php if( isset($_GET['settings-updated']) && $_GET['settings-updated'] ){ ?>
					<div id="message" class="<?php echo $class; ?> notice is-dismissible"> 
						<p><strong><?php echo $message; ?></strong></p>
                        <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
					</div>
				<?php } ?>
            	<form method="post" action="options.php">
                	<?php settings_fields( 'WPTime_post_types_converter_settings_fields' ); ?>
                	<table class="form-table">
                		<tbody>
                        
                    		<tr>
                        		<th scope="row"><label for="wpt_ptc_link">Post Shortlink Or ID</label></th>
                            	<td>
                                    <input class="regular-text" name="wpt_ptc_link" type="text" id="wpt_ptc_link" value="">
                                    <p class="description">Enter post shortlink or post ID to convert it. <a href="http://wp-time.com/post-types-converter/" target="_blank">How to get Post Shortlink and Post ID?</a></p>
								</td>
                        	</tr>
                            
                    		<tr>
                        		<th scope="row"><label for="wpt_ptc_post_type">Post Type</label></th>
                            	<td>
                                    <input class="regular-text" name="wpt_ptc_post_type" type="text" id="wpt_ptc_post_type" value="">
                                    <p class="description">Enter new post type name to convert your post, <?php echo "you have $the_count post type names: $get_post_types"; ?>only.</p>
								</td>
                        	</tr>

                    	</tbody>
                    </table>
                    <p class="submit"><input id="submit" class="button button-primary" type="submit" name="submit" value="Convert"></p>
                </form>
            	<div class="tool-box">
					<h3 class="title">Recommended Links</h3>
					<p>Get collection of 87 WordPress themes for $69 only, a lot of features and free support! <a href="http://j.mp/ET_WPTime_ref_pl" target="_blank">Get it now</a>.</p>
					<p>See also:</p>
						<ul>
							<li><a href="http://j.mp/GL_WPTime" target="_blank">Must Have Awesome Plugins.</a></li>
							<li><a href="http://j.mp/CM_WPTime" target="_blank">Premium WordPress themes on CreativeMarket.</a></li>
							<li><a href="http://j.mp/TF_WPTime" target="_blank">Premium WordPress themes on Themeforest.</a></li>
							<li><a href="http://j.mp/CC_WPTime" target="_blank">Premium WordPress plugins on Codecanyon.</a></li>
							<li><a href="http://j.mp/BH_WPTime" target="_blank">Unlimited web hosting for $3.95 only.</a></li>
						</ul>
					<p><a href="http://j.mp/GL_WPTime" target="_blank"><img style="max-width:100%;" src="<?php echo plugins_url( '/banner/global-aff-img.png', __FILE__ ); ?>" width="728" height="90"></a></p>
					<p><a href="http://j.mp/ET_WPTime_ref_pl" target="_blank"><img style="max-width:100%;" src="<?php echo plugins_url( '/banner/570x100.jpg', __FILE__ ); ?>"></a></p>
				</div>
            </div>
        <?php
	} // page function


?>