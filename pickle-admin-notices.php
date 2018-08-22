<?php
/*
  Plugin Name: Pickle Admin Notices
  Plugin URI: 
  Description: Setup notcies that appear in the WP Admin panel
  Version: 1.0.0-beta
  Author: Erik Mitchell
  Author URI: http://erikmitchell.net
  License: GPL-2.0+
  License URI: http://www.gnu.org/licenses/gpl-2.0.txt
  Text Domain: pan
  Domain Path: /languages
*/

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

if (!defined('PAN_PLUGIN_FILE')) {
	define('PAN_PLUGIN_FILE', __FILE__);
}

final class PickleAdminNotices {

	public $version='1.0.0-beta';
	
	public $admin='';

	protected static $_instance=null;

	public static function instance() {
		if (is_null(self::$_instance)) {
			self::$_instance=new self();
		}
		
		return self::$_instance;
	}

	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();				
	}

	private function define_constants() {
		$this->define('PAN_VERSION', $this->version);
		$this->define('PAN_PATH', plugin_dir_path(__FILE__));
		$this->define('PAN_URL', plugin_dir_url(__FILE__));		
	}

	private function define($name, $value) {
		if (!defined($name)) {
			define($name, $value);
		}
	}

	public function includes() {
		include_once(PAN_PATH.'admin/class-pickle-admin-notices-admin.php');
		
		if (is_admin()) :
			$this->admin=new Pickle_Admin_Notices_Admin();
		endif;
	}

	private function init_hooks() {
		//register_activation_hook(PAN_PLUGIN_FILE, array('Pickle_Custom_Login_Install', 'install'));
		add_action('init', array($this, 'init'), 0);
	}

	public function init() {

	}	

}

function pickle_admin_notices() {
	return PickleAdminNotices::instance();
}

// Global for backwards compatibility.
$GLOBALS['pickle_admin_notices']=pickle_admin_notices();