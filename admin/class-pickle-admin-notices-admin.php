<?php

class Pickle_Admin_Notices_Admin {
    
    public $notices;

	public function __construct() {
    	add_action('admin_enqueue_scripts', array($this, 'scripts_styles'));
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_init', array($this, 'delete_notice'), 0);
        add_action('admin_init', array($this, 'setup_notices'), 11);
        add_action('admin_init', array($this, 'update_notices'));
        add_action('admin_notices', array($this, 'admin_notices'), 99);
	}	

    public function scripts_styles() {
        wp_enqueue_script('pickle-admin-notices-single-script', PAN_URL.'admin/js/single-notice.js', array('jquery'), PAN_VERSION, true);
        
        wp_enqueue_style('pickle-admin-notices-single-style', PAN_URL.'admin/css/single-notice.css', '', PAN_VERSION);
    }

    public function admin_menu() {
        add_options_page('Pickle Admin Notices', 'Pickle Admin Notices', 'manage_options', 'pickle-admin-notices', array($this, 'admin_page'));
    }
    
    public function admin_page() {
        $html = '';

		$tabs=array(
			'notices' => 'Notices',
		);
		$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'notices';

			
		$html.='<div class="wrap pickle-admin-notices-admin">';
			$html.='<h1>Pickle Admin Notices</h1>';

			$html.='<h2 class="nav-tab-wrapper">';
				foreach ($tabs as $key => $name) :
					if ($active_tab==$key) :
						$class='nav-tab-active';
					else :
						$class=null;
					endif;

					$html.='<a href="?page=pickle-calendar&tab='.$key.'" class="nav-tab '.$class.'">'.$name.'</a>';
				endforeach;
			$html.='</h2>';

			switch ($active_tab) :
				case 'notices':
            		if (isset($_GET['action']) && $_GET['action'] == 'edit') :
                        $html.=$this->get_admin_page('notices-single');
            		else :
            			$html.=$this->get_admin_page('notices');
            		endif;
					break;					
				default:
					$html.=$this->get_admin_page('notices');
			endswitch;

		$html.='</div>';	        
        
        echo $html;
    }
    
    public function admin_notices() {
        if (!$this->has_notices() && $this->notices != '')
            return;
            
        $html = '';
            
        foreach ($this->notices as $notice) :
            if ($notice['dismissible']) :
                $dismissible = 'is-dismissible';
            else :
                $dismissible = '';
            endif;
            
            $classes = array('notice', 'notice-'.$notice['type'], $dismissible, 'pickle-admin-notice');
            
            $html .= '<div class="'.implode(' ', $classes).'">';
                $html .= '<p>'.__($notice['notice']).'</p>';
            $html.='</div>';
        endforeach;
        
        echo $html;
    }
    
    public function get_notices() {
        $notices = array();
        
        $notices = $this->parse_args($this->notices, $notices);
        
        return $notices;
    }

    public function get_notice($slug = '') {
    	$default=array(
        	'name' => '',
        	'slug' => '',
    		'notice' => '',
    		'display' => 1,
    		'type' => 'warning',
    		'dismissible' => 0,
    	);
    	$_notice=array();
    	
    	if ($this->has_notices()) :
        	foreach ($this->get_notices() as $notice) :
        		if ($notice['slug']==$slug) :
        			$_notice=$notice;
        			break;
        		endif;
        	endforeach;
    	endif;
    	
    	$_notice=$this->parse_args($_notice, $default);
    	
    	return $_notice;
    }
    
    public function has_notices() {
        if (empty($this->get_notices()))
            return false;
            
        return true;
    }
    
	public function setup_notices() {		
		$default_notices=array();
		
		$db_notices=get_option('pickle_admin_notices', array());
		
		$notices=$this->parse_args($db_notices, $default_notices);
	
		$this->notices = $notices;
	}    
    
	public function update_notices() {
		if (!isset($_POST['pickle_admin_notices_admin']) || !wp_verify_nonce($_POST['pickle_admin_notices_admin'], 'update_notice'))
			return false; 

        $update = false;
        $post_notice = $_POST['notice_details'];
        $notices = get_option('pickle_admin_notices', '');
        
        // clean slug
        $post_notice['slug'] = strtolower( str_replace(' ', '-', $post_notice['slug']) );
        
        foreach ($notices as $key => $notice) :
            if ($notice['slug'] == $post_notice['slug']) :
                $notices[$key] = $post_notice;
                $update = true;
            elseif (isset($_GET['slug']) && $notice['slug'] == $_GET['slug']) :
                $notices[$key] = $post_notice;
                $update = true;
            endif;
        endforeach;
        
        if (!$update)
            $notices[] = $post_notice;

		update_option('pickle_admin_notices', $notices);
		
		if (isset($post_notice['slug']) && $post_notice['slug'] != '')
		    $_POST['_wp_http_referer'] .= '&slug=' . $post_notice['slug'];

		wp_redirect(site_url($_POST['_wp_http_referer']));
		exit;
	}  
	
	public function delete_notice() {
		if (!isset($_GET['pickle_admin_notices']) || !wp_verify_nonce($_GET['pickle_admin_notices'], 'delete_notice') || !isset($_GET['slug']))
			return false;

    	$notices = get_option('pickle_admin_notices', '');
	
    	foreach ($notices as $key => $notice) :
    	    if ($notice['slug'] == $_GET['slug']) :
    	        unset($notices[$key]);
    	        
    	        break;
    	    endif;
    	endforeach;

        $notices = array_values($notices);
      
        update_option('pickle_admin_notices', $notices);
        
        wp_redirect(admin_url('options-general.php?page=pickle-admin-notices&tab=notices'));
        exit;
	}
	
	public function delete_url($slug = '') {
    	return wp_nonce_url(admin_url('options-general.php?page=pickle-admin-notices&tab=notices&action=delete&slug='.$slug), 'delete_notice', 'pickle_admin_notices');
	} 

	public function parse_args(&$a, $b) {
		$a = (array) $a;
		$b = (array) $b;
		$result = $b;
		
		foreach ( $a as $k => &$v ) {
			if ( is_array( $v ) && isset( $result[ $k ] ) ) {
				$result[ $k ] = $this->parse_args($v, $result[ $k ]);
			} else {
				$result[ $k ] = $v;
			}
		}
		
		return $result;
	}
    
	public function get_admin_page($template_name=false) {
		if (!$template_name)
			return false;

		ob_start();

		do_action('pickle_admin_notices_before_admin_'.$template_name);

		include(PAN_PATH.'admin/pages/'.$template_name.'.php');

		do_action('pickle_admin_notices_after_admin_'.$template_name);

		$html=ob_get_contents();

		ob_end_clean();

		return $html;
	}    
    
}
