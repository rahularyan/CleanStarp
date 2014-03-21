<?php

	//error_reporting(0);
	//@ini_set('display_errors', 0);
		
	/* don't allow this page to be requested directly from browser */	
	if (!defined('QA_VERSION')) {
			header('Location: /');
			exit;
	}
	$cs_error ='';
	
	
	//first check if cs-control in installed
	if (!defined('CS_CONTROL_DIR'))
		qa_fatal_error('CS Control plugin is not installed !  please make sure you have installed CS Control plugin. Contact us from http://rahularyan.com/support');
	
	
	
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

			//qa_register_layer('/inc/widgets.php', 'Theme Widgets', Q_THEME_DIR , Q_THEME_URL );
		}		
		
	
		
		
		
	}
