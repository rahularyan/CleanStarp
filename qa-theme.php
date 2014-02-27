<?php
	/* don't allow this page to be requested directly from browser */	
	if (!defined('QA_VERSION')) {
			header('Location: /');
			exit;
	}
	$cs_error ='';

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
	
	if(isset($_REQUEST['cs_ajax']))
		require Q_THEME_DIR.'/inc/ajax.php';
	else{
		global $qa_request;
		
		if (qa_get_logged_in_level()>=QA_USER_LEVEL_ADMIN){
			if(!(bool)qa_opt('cs_init')){ // theme init 
				cs_register_widget_position(
					array(
						'Top' => 'Before navbar', 
						'Header' => 'After navbar', 
						'Header left' => 'Left side of header', 
						'Header Right' => 'Right side of header', 
						'Left' => 'Right side below menu', 
						'Content Top' => 'Before questions list', 
						'Content Bottom' => 'After questions lists', 
						'Right' => 'Right side of content', 
						'Bottom' => 'Below content and before footer',
						'Home Slide' => 'Home Top',
						'Home Left' => 'Home left',
						'Home Left Right' => 'Home inner right',
						'Home Left Bottom 1' => 'Home bottom',
						'Home Left Bottom 2' => 'Home bottom',
						'Home Left Bottom 3' => 'Home bottom',
						'Home Right' => 'Top content in home',
						'Top Users' => 'Right tab',
						'New Users' => 'Right tab'
					)
				);
				reset_theme_options();
				qa_opt('cs_init',true);
			}
			qa_register_layer('/inc/options.php', 'Theme Options', Q_THEME_DIR , Q_THEME_URL );	
			qa_register_layer('/inc/widgets.php', 'Theme Widgets', Q_THEME_DIR , Q_THEME_URL );
		}		
			
		qa_register_module('widget', '/inc/widget_ask.php', 'cs_ask_widget', 'Ajax Ask', Q_THEME_DIR, Q_THEME_URL);
		qa_register_module('widget', '/inc/widget_tags.php', 'cs_tags_widget', 'Tags', Q_THEME_DIR, Q_THEME_URL);
		qa_register_module('widget', '/inc/widget_ticker.php', 'cs_ticker_widget', 'Ticker', Q_THEME_DIR, Q_THEME_URL);
		qa_register_module('widget', '/inc/widget_top_users.php', 'cs_top_users_widget', 'Top Contributors', Q_THEME_DIR, Q_THEME_URL);
		qa_register_module('widget', '/inc/widget_activity.php', 'cs_activity_widget', 'Site Activity', Q_THEME_DIR, Q_THEME_URL);
		qa_register_module('widget', '/inc/widget_question_activity.php', 'cs_question_activity_widget', 'Question Activity', Q_THEME_DIR, Q_THEME_URL);
		qa_register_module('widget', '/inc/widget_featured_questions.php', 'cs_featured_questions_widget', 'Featured Questions', Q_THEME_DIR, Q_THEME_URL);
		qa_register_module('widget', '/inc/widget_activity_count.php', 'cs_activity_count_widget', 'User Activity Count', Q_THEME_DIR, Q_THEME_URL);
		qa_register_module('widget', '/inc/widget_twitter.php', 'cs_twitter_widget', 'Twitter Widget', Q_THEME_DIR, Q_THEME_URL);
		qa_register_module('widget', '/inc/widget_feed.php', 'cs_feed_widget', 'Feed Widget', Q_THEME_DIR, Q_THEME_URL);
		qa_register_module('widget', '/inc/widget_new_users.php', 'cs_new_users_widget', 'New Users', Q_THEME_DIR, Q_THEME_URL);			
	}
