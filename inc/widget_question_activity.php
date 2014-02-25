<?php
	class cs_question_activity_widget {

		function ra_widget_form()
		{
			
			return array(
				'style' => 'wide',
				'fields' => array(
					'ra_tc_count' => array(
						'label' => 'Numbers of user',
						'type' => 'number',
						'tags' => 'name="ra_tc_count"',
						'value' => '10',
					),
					'ra_tc_avatar' => array(
						'label' => 'Avatar Size',
						'type' => 'number',
						'tags' => 'name="ra_tc_avatar"',
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

		function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
		{
			$widget_opt = @$themeobject->current_widget['Question Activity']['options'];

			if(@$themeobject->current_widget['Question Activity']['locations']['show_title'])
				$themeobject->output('<h3 class="widget-title">Recent Activity <a href="'.qa_path_html('activity').'">View All</a></h3>');
				
			$themeobject->output('<div class="ra-question-activity-widget">');
			
			$content = include_once QA_INCLUDE_DIR.'qa-page-activity.php';
			$q_list = $content['q_list']['qs'];
			
			$themeobject->output('<ul class="activity-list">');
			foreach ($q_list as $list){
				$themeobject->output('<li><span class="fav-star icon-star'.(@$list['raw']['userfavoriteq'] ? ' active' : '').'"></span><a'.(is_featured($list['raw']['postid']) ? ' class="featured" ' : '').' href="'.$list['url'].'">'.ra_truncate($list['title'], 50).'<span class="time">'.implode(' ', $list['when']).'</span><span class="ans-count total-'.$list['raw']['acount'].'">'.$list['raw']['acount'].'</span></a></li>');
			}
			$themeobject->output('</ul>');
			$themeobject->output('</div>');
		}
	
	}
/*
	Omit PHP closing tag to help avoid accidental output
*/