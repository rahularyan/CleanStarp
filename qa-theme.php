<?php

	//error_reporting(0);
	//@ini_set('display_errors', 0);
		
	/* don't allow this page to be requested directly from browser */	
	if (!defined('QA_VERSION')) {
			header('Location: /');
			exit;
	}
	$cs_error ='';

	
	define('Q_THEME_DIR', dirname( __FILE__ ));
	define('Q_THEME_URL', get_base_url().'/qa-theme/'.qa_get_site_theme());
	
	qa_register_layer('/featured.php', 'Featured', Q_THEME_DIR , Q_THEME_URL );
	
	include_once Q_THEME_DIR.'/functions.php';
	include_once Q_THEME_DIR.'/inc/blocks.php';
	
	//require_once QA_INCLUDE_DIR.'qa-db-cache.php';
	//cs_get_site_cache();	

	
	qa_register_phrases(Q_THEME_DIR . '/language/cs-lang-*.php', 'cleanstrap');

	if(isset($_REQUEST['cs_ajax'])){	
		if(isset($_REQUEST['cs_ajax'])){
			$action = 'cs_ajax_'.$_REQUEST['action'];
			if(function_exists($action))
				$action();
		}
		
	}else{
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
						'Home 1 Left' => 'Home position 1',
						'Home 1 Center' => 'Home position 1',
						'Home 2' => 'Home position 2',
						'Home 3 Left' => 'Home position 3',
						'Home 3 Center' => 'Home position 3',
						'Home Right' => 'Home right side',						
						'Question Right' => 'Right side of question',
						'User Content' => 'On user page'
					)
				);
				reset_theme_options();
				qa_opt('cs_init',true);
			}

			if(!qa_opt('cs_installed')){
			/* add some option when theme init first time */

				//create table for builder
				qa_db_query_sub(
					'CREATE TABLE IF NOT EXISTS ^ra_widgets ('.
						'id INT(10) NOT NULL AUTO_INCREMENT,'.				
						'name VARCHAR (64),'.				
						'position VARCHAR (64),'.				
						'widget_order INT(2) NOT NULL DEFAULT 0,'.				
						'param LONGTEXT,'.				
						'PRIMARY KEY (id),'.
						'UNIQUE KEY id (id)'.				
					') ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;'
				);
				qa_opt('cs_installed', true); // update db, so that this code should not execute every time

			}
			
			//qa_register_layer('/inc/options.php', 'Theme Options', Q_THEME_DIR , Q_THEME_URL );	
			qa_register_layer('/inc/widgets.php', 'Theme Widgets', Q_THEME_DIR , Q_THEME_URL );
		}		
		
		
		qa_register_module('widget', '/inc/widget_ask.php', 'cs_ask_widget', 'CS Ajax Ask', Q_THEME_DIR, Q_THEME_URL);
		qa_register_module('widget', '/inc/widget_tags.php', 'cs_tags_widget', 'CS Tags', Q_THEME_DIR, Q_THEME_URL);
		qa_register_module('widget', '/inc/widget_ticker.php', 'cs_ticker_widget', 'CS Ticker', Q_THEME_DIR, Q_THEME_URL);
		qa_register_module('widget', '/inc/widget_top_users.php', 'cs_top_users_widget', 'CS Top Contributors', Q_THEME_DIR, Q_THEME_URL);
		qa_register_module('widget', '/inc/widget_activity.php', 'cs_activity_widget', 'CS Site Activity', Q_THEME_DIR, Q_THEME_URL);
		qa_register_module('widget', '/inc/widget_question_activity.php', 'cs_question_activity_widget', 'CS Question Activity', Q_THEME_DIR, Q_THEME_URL);
		qa_register_module('widget', '/inc/widget_featured_questions.php', 'cs_featured_questions_widget', 'CS Featured Questions', Q_THEME_DIR, Q_THEME_URL);
		qa_register_module('widget', '/inc/widget_site_status.php', 'cs_site_status_widget', 'CS Site Status', Q_THEME_DIR, Q_THEME_URL);
		//qa_register_module('widget', '/inc/widget_twitter.php', 'cs_twitter_widget', 'CS Twitter Widget', Q_THEME_DIR, Q_THEME_URL);
		//qa_register_module('widget', '/inc/widget_feed.php', 'cs_feed_widget', 'CS Feed Widget', Q_THEME_DIR, Q_THEME_URL);
		qa_register_module('widget', '/inc/widget_new_users.php', 'cs_new_users_widget', 'CS New Users', Q_THEME_DIR, Q_THEME_URL);
		qa_register_module('widget', '/inc/widget_related_questions.php', 'cs_related_questions', 'CS Related Questions', Q_THEME_DIR, Q_THEME_URL);
		qa_register_module('widget', '/inc/widget_text.php', 'cs_widget_text', 'CS Text Widget', Q_THEME_DIR, Q_THEME_URL);
		qa_register_module('widget', '/inc/widget_posts.php', 'cs_widget_posts', 'CS Posts', Q_THEME_DIR, Q_THEME_URL);
		
		qa_register_module('widget', '/inc/widget_user_activity.php', 'cs_user_activity_widget', 'CS User Activity', Q_THEME_DIR, Q_THEME_URL);
		
		qa_register_module('widget', '/inc/widget_user_posts.php', 'cs_user_posts_widget', 'CS User Posts', Q_THEME_DIR, Q_THEME_URL);
		
		qa_register_module('widget', '/inc/widget_current_category.php', 'cs_current_category_widget', 'CS Current Cat', Q_THEME_DIR, Q_THEME_URL);
		
		//enable category widget only if category is active in q2a
		if ( qa_using_categories() )
			qa_register_module('widget', '/inc/widget_categories.php', 'widget_categories', 'CS Categories', Q_THEME_DIR, Q_THEME_URL);			
	}
