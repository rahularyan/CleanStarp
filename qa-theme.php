<?php
	/* don't allow this page to be requested directly from browser */	
	if (!defined('QA_VERSION')) {
			header('Location: /');
			exit;
	}
	$ra_error ='';

	function get_base_url()
    {
        /* First we need to get the protocol the website is using */
        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https://' : 'http://';

        /* returns /myproject/index.php */
		if(qa_opt('neat_urls') == 0 || strpos($_SERVER['PHP_SELF'],'/index.php/') !== false):
			$path = strstr($_SERVER['PHP_SELF'], '/index', true);
			$directory = $path;
		else:
			$path = $_SERVER['PHP_SELF'];
			$path_parts = pathinfo($path);
			$directory = $path_parts['dirname'];
			$directory = ($directory == "/") ? "" : $directory;
		endif;       
			
			$directory = ($directory == "\\") ? "" : $directory;
			
        /* Returns localhost OR mysite.com */
        $host = $_SERVER['HTTP_HOST'];

        return $protocol . $host . $directory;
    }
	
	define('Q_THEME_DIR', dirname( __FILE__ ));
	define('Q_THEME_URL', get_base_url().'/qa-theme/'.qa_get_site_theme());
	
	require Q_THEME_DIR.'/functions.php';
	require Q_THEME_DIR.'/inc/blocks.php';
	
	if(isset($_REQUEST['ra_ajax']))
		require Q_THEME_DIR.'/inc/ajax.php';
	else{
		global $qa_request;

		//init 
		
		if(!(bool)qa_opt('ra_init')){
			ra_register_widget_position(
				array(
					'Top' => 'Before navbar', 
					'Header' => 'After navbar', 
					'Header left' => 'Left side of header', 
					'Header Right' => 'Right side of header', 
					'Left' => 'Right side below menu', 
					'Content Top' => 'Before questions list', 
					'Content Bottom' => 'After questions lists', 
					'Right' => 'Right side of content', 
					'Bottom' => 'Below content adn before footer'
				)
			);
		}
		
		if (qa_get_logged_in_level()>=QA_USER_LEVEL_ADMIN)
			qa_register_layer('/inc/options.php', 'Theme Options', Q_THEME_DIR , Q_THEME_URL );	
		
		if (qa_get_logged_in_level()>=QA_USER_LEVEL_ADMIN)
			qa_register_layer('/inc/widgets.php', 'Theme Widgets', Q_THEME_DIR , Q_THEME_URL );
	}
	
	qa_register_module('widget', '/inc/widget_ask.php', 'ra_ask_widget', 'RA Ajax Ask', Q_THEME_DIR, Q_THEME_URL);