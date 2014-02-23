<?php
	class cs_top_users_widget {

		
		function admin_form()
		{
			
			return array(
				
				'fields' => array(
					array(
						'label' => 'Maximum tags to show',
						'type' => 'number',
						'value' => (int)qa_opt('ra_tag_cloud_count'),
						'suffix' => 'tags',
						'tags' => 'name="ra_tag_cloud_count"',
					),
	
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
		function ra_top_users($limit = 5, $size){
			$users = qa_db_select_with_pending(qa_db_top_users_selectspec(qa_get_start()));
			
			$output = '<ul class="top-users-list clearfix">';
			$i = 1;
			foreach($users as $u){
				if(defined('QA_WORDPRESS_INTEGRATE_PATH')){
					require_once QA_INCLUDE_DIR.'qa-app-posts.php';
					$u['handle'] = qa_post_userid_to_handle($u['userid']);
				}
				$data = ra_user_data($u['handle']);
				$output .= '<li class="top-user clearfix">';
				$output .= '<div class="avatar pull-left" data-handle="'.$u['handle'].'" data-id="'. qa_handle_to_userid($u['handle']).'">';
				$output .= '<img src="'.ra_get_avatar($u['handle'], $size, false).'" /></div>';
				$output .= '<div class="top-user-data">';
				
				$output .= '<span class="points">'.$u['points'].' '._ra_lang('Points').'</span>';
				$output .= '<a href="'.qa_path_html('user/'.$u['handle']).'" class="name">'.ra_name($u['handle']).'</a>';
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
			$widget_opt = $themeobject->current_widget['RA Ticker']['options'];

			if(@$themeobject->current_widget['RA Ticker']['locations']['show_title'])
				$themeobject->output('<h3 class="widget-title">Top Contributors</h3>');
				
			$themeobject->output('<div class="ra-tags-widget">');
			$themeobject->output($this->ra_top_users(5, 30));
			$themeobject->output('</div>');
		}
	
	}
/*
	Omit PHP closing tag to help avoid accidental output
*/