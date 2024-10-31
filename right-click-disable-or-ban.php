<?php

/**
@package Right Click Disable OR Ban
Plugin Name: Right Click Ban
Plugin URI:  http://awplife.com/
Description: Disable Right Click On WordPress Website
Version:     1.1.17
Author:      A WP Life
Author URI:  http://awplife.com/
Text Domain: right-click-disable-or-ban
Domain Path: /languages
License:     GPL2

Right Click Disable OR Ban is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Right Click Disable OR Ban is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Right Click Disable. If not, see https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html.
*/



if ( ! class_exists( 'Right_Click_Disable' ) ) {

	class Right_Click_Disable {
		
		public function __construct() {
			// Plugin Version
			define( 'RCB_PLUGIN_VER', '1.1.17' );
			
			// Plugin Text Domain
			define("RCB_TXTDM", "right-click-disable-or-ban" );
			
			//Plugin Name
			define( 'RCB_PLUGIN_NAME', __( 'Right Click Disable', RCB_TXTDM ) );

			// Plugin Slug
			define( 'RCB_PLUGIN_SLUG', 'right-click-disable-or-ban' );

			// Plugin Directory Path
			define( 'RCB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

			// Plugin Directory URL
			define( 'RCB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			
			// load text domain
			add_action( 'plugins_loaded', array( $this, '_rcb_load_textdomain' ) );
			
			// plugin menu page
			add_action( 'admin_menu', array( $this, '_rcb_menu' ), 101 );
			
			// include script
			add_action( 'wp_enqueue_scripts', array( $this, '_load_ban_script' ) );			
		}
		
		// loads the text domain
		public function _rcb_load_textdomain() {
			load_plugin_textdomain( RCB_TXTDM, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}
		
		// plugin menu
		public function _rcb_menu() {
			//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
			$rcb_menu = add_menu_page( 'Right Click Disable', __( 'Right Click Disable', RCB_TXTDM ), 'administrator', RCB_PLUGIN_SLUG, array( $this, '_rcb_page_function'), 'dashicons-dismiss', 65 );
			$doc_menu = add_submenu_page( RCB_PLUGIN_SLUG, __( 'Docs', RCB_TXTDM ), __( 'Docs', RCB_TXTDM ), 'administrator', 'rcb-doc-page', array( $this, '_rcb_doc_page') );
			$theme_menu    = add_submenu_page( 'edit.php?post_type='.RCB_PLUGIN_SLUG, __( 'Our Theme', RCB_TXTDM ), __( 'Our Theme', RCB_TXTDM ), 'administrator', 'sr-theme-page', array( $this, '_rcb_theme_page') );
		}
		
		// plugin setting page
		public function _rcb_page_function() {
			require_once('settings.php');
		}
		
		// doc page
		public function _rcb_doc_page() {
			require_once('docs.php');
		}	

		// theme page
		public function _rcb_theme_page() {
			require_once('our-theme/awp-theme.php');
		}
		
		// load script
		public function _load_ban_script() {
			$settings = get_option('awl_save_rcb_settings');
			if(isset($settings['rcb_user_status'])) $rcb_user_status = $settings['rcb_user_status']; else $rcb_user_status = 0;
			if(isset($settings['rcb_msg_status'])) $rcb_msg_status = $settings['rcb_msg_status']; else $rcb_msg_status = 0;
			if(isset($settings['rcb_msg'])) $rcb_msg = $settings['rcb_msg']; else $rcb_msg = 'Right click is disabled.';
			if(isset($settings['rcb_admin_status'])) $rcb_admin_status = $settings['rcb_admin_status']; else $rcb_admin_status = 0;

			// check admin login
			if(current_user_can( 'manage_options' )) {
				// ban for admin
				if($rcb_admin_status) {
					?>
					<script language="javascript">
						document.onmousedown=disableclick;
						var status = "<?php echo $rcb_msg; ?>";
						function disableclick(event) {
							if(event.button==2) {
								<?php if($rcb_msg_status) { ?>
								alert(status);
								<?php } ?>
								return false;
							}
						}
					</script>
					<?php
				}
			} else {
				// ban for user
				if($rcb_user_status) {
					?>
					<script language="javascript">
						document.onmousedown=disableclick;
						var status = "<?php echo $rcb_msg; ?>";
						function disableclick(event) {
							if(event.button==2) {
								<?php if($rcb_msg_status) { ?>
								alert(status);
								<?php } ?>
								return false;
							}
						}
					</script>
					<?php
				}
			}
		}
		
	} // end of class

	$rcb_object = new Right_Click_Disable();
} // end of class exists
?>