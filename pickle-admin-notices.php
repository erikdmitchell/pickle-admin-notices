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
    	include_once(PAN_PATH.'updater/updater.php');
		include_once(PAN_PATH.'admin/class-pickle-admin-notices-admin.php');
		
		if (is_admin()) :
			$this->admin=new Pickle_Admin_Notices_Admin();
		endif;
	}

	private function init_hooks() {
		//register_activation_hook(PAN_PLUGIN_FILE, array('Pickle_Custom_Login_Install', 'install'));
		add_action('init', array($this, 'init'), 0);
		add_action('admin_init', array($this, 'plugin_updater'));
	}

	public function init() {

	}
	
	public function plugin_updater() {
		if (!is_admin())
			return false;
	
		if (!defined('PAN_GITHUB_FORCE_UPDATE'))
			define('PAN_GITHUB_FORCE_UPDATE', true);
			
		$username='erikdmitchell';
		$repo_name='pickle-admin-notices';
		$folder_name='pickle-admin-notices';
	    
	    $config = array(
	        'slug' => plugin_basename(__FILE__), // this is the slug of your plugin
	        'proper_folder_name' => $folder_name, // this is the name of the folder your plugin lives in
	        'api_url' => 'https://api.github.com/repos/'.$username.'/'.$repo_name, // the github API url of your github repo
	        'raw_url' => 'https://raw.github.com/'.$username.'/'.$repo_name.'/master', // the github raw url of your github repo
	        'github_url' => 'https://github.com/'.$username.'/'.$repo_name, // the github url of your github repo
	        'zip_url' => 'https://github.com/'.$username.'/'.$repo_name.'/zipball/master', // the zip url of the github repo
	        'sslverify' => true, // wether WP should check the validity of the SSL cert when getting an update, see https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/2 and https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/4 for details
	        'requires' => '4.0', // which version of WordPress does your plugin require?
	        'tested' => '4.9', // which version of WordPress is your plugin tested up to?
	        'readme' => 'readme.txt', // which file to use as the readme for the version number
	    );
	   
		new PAN_GitHub_Updater($config);
	}	

}

function pickle_admin_notices() {
	return PickleAdminNotices::instance();
}

// Global for backwards compatibility.
$GLOBALS['pickle_admin_notices']=pickle_admin_notices();