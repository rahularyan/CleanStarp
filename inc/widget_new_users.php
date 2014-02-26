<?php
	class cs_new_users_widget {

		function ra_widget_form()
		{
			
			return array(
				'style' => 'wide',
				'fields' => array(
					'ra_nu_count' => array(
						'label' => 'Numbers of user',
						'type' => 'number',
						'tags' => 'name="ra_nu_count"',
						'value' => '10',
					),
					'ra_nu_avatar' => array(
						'label' => 'Avatar Size',
						'type' => 'number',
						'tags' => 'name="ra_nu_avatar"',
						'value' => '30',
					)	
				),

			);
		}

		
		function allow_template($template)
		{
			$allow=false;
			
			switch ($template)
			{
				case 'activity':
				case 'qa':
				case 'questions':
				case 'hot':
				case 'ask':
				case 'categories':
				case 'question':
				case 'tag':
				case 'tags':
				case 'unanswered':
				case 'user':
				case 'users':
				case 'search':
				case 'admin':
				case 'custom':
					$allow=true;
					break;
			}
			
			return $allow;
		}

		
		function allow_region($region)
		{
			$allow=false;
			
			switch ($region)
			{
				case 'main':
				case 'side':
				case 'full':
					$allow=true;
					break;
			}
			
			return $allow;
		}
		function ra_new_users($limit, $size){
			$output = '<ul class="users-list clearfix">';
			if (defined('QA_FINAL_WORDPRESS_INTEGRATE_PATH')){
				global $wpdb;
				$users = $wpdb->get_results("SELECT ID from $wpdb->users order by ID DESC LIMIT $limit");
				require_once QA_INCLUDE_DIR.'qa-app-posts.php';
				
				foreach($users as $u){
					$handle = qa_post_userid_to_handle($u->ID);
					$output .= '<li class="user">';
					$output .= '<div class="avatar" data-handle="'.$handle.'" data-id="'. qa_handle_to_userid($handle).'"><img src="'.ra_get_avatar($u['handle'], $size, false).'" /></div>';
					$output .= '</li>';
				}
				
			}else{
				$users = qa_db_query_sub('SELECT * FROM ^users ORDER BY created DESC LIMIT #', $limit);	
				while($u = mysql_fetch_array($users)){
					$output .= '<li class="user">';
					$output .= '<div class="avatar" data-handle="'.$u['handle'].'" data-id="'. qa_handle_to_userid($u['handle']).'"><img src="'.ra_get_avatar($u['handle'], $size, false).'" /></div>';
					$output .= '</li>';
				}
			}
			$output .= '</ul>';
			echo $output;
		}

		function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
		{
			$widget_opt = $themeobject->current_widget['New Users']['options'];

			if(@$themeobject->current_widget['New Users']['locations']['show_title'])
				$themeobject->output('<h3 class="widget-title">New Users</h3>');
				
			$themeobject->output('<div class="ra-new-users-widget">');
			$themeobject->output($this->ra_new_users((int)@$widget_opt['ra_nu_count'], (int)@$widget_opt['ra_nu_avatar']));
			$themeobject->output('</div>');
		}
	
	}
/*
	Omit PHP closing tag to help avoid accidental output
*/