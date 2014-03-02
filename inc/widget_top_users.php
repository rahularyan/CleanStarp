<?php
	class cs_top_users_widget {

		function cs_widget_form()
		{
			
			return array(
				'style' => 'wide',
				'fields' => array(
					'cs_tc_count' => array(
						'label' => 'Numbers of user',
						'type' => 'number',
						'tags' => 'name="cs_tc_count"',
						'value' => '10',
					),
					'cs_tc_avatar' => array(
						'label' => 'Avatar Size',
						'type' => 'number',
						'tags' => 'name="cs_tc_avatar"',
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
		/* top users widget */
		function cs_top_users($limit = 5, $size){
			$users = cs_get_cache_select_selectspec(qa_db_top_users_selectspec(qa_get_start()));
			$output = '<ul class="top-users-list clearfix">';
			$i = 1;
			foreach($users as $u){
				if(defined('QA_WORDPRESS_INTEGRATE_PATH')){
					require_once QA_INCLUDE_DIR.'qa-app-posts.php';
					$u['handle'] = qa_post_userid_to_handle($u['userid']);
				}
				$data = cs_user_data($u['handle']);
				$output .= '<li class="top-user clearfix">';
				$output .= '<div class="avatar pull-left" data-handle="'.$u['handle'].'" data-id="'. qa_handle_to_userid($u['handle']).'">';
				$output .= '<img src="'.cs_get_avatar($u['handle'], $size, false).'" /></div>';
				$output .= '<div class="top-user-data">';
				
				$output .= '<span class="points">'.$u['points'].' '._cs_lang('Points').'</span>';
				$output .= '<a href="'.qa_path_html('user/'.$u['handle']).'" class="name">'.cs_name($u['handle']).'</a>';
				$output .= '<p class="counts"><span>'.$data[2]['aposts'].' Answers</span> <span>'.$data[2]['qposts'].' Questions</span></p>';
				$output .= '</div>';
				$output .= '</li>';
				if($i==$limit)break;
				$i++;
			
			}
			$output .= '</ul>';
			return $output;
		}		

		function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
		{
			$widget_opt = $themeobject->current_widget['param']['options'];

			if(@$themeobject->current_widget['param']['locations']['show_title'])
				$themeobject->output('<h3 class="widget-title">Top Contributors</h3>');
				
			$themeobject->output('<div class="ra-tags-widget">');
			$themeobject->output($this->cs_top_users((int)@$widget_opt['cs_tc_count'], (int)@$widget_opt['cs_tc_avatar']));
			$themeobject->output('</div>');
		}
	
	}
/*
	Omit PHP closing tag to help avoid accidental output
*/