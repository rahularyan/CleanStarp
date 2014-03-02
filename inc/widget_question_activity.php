<?php
	class cs_question_activity_widget {

		function cs_widget_form()
		{
			
			return array(
				'style' => 'wide',
				'fields' => array(
					'cs_qa_count' => array(
						'label' => 'Numbers of questions',
						'type' => 'number',
						'tags' => 'name="cs_qa_count"',
						'value' => '10',
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
			$widget_opt = @$themeobject->current_widget['param']['options'];
			$content = cs_get_cache_question_activity((int)$widget_opt['cs_qa_count']);
			if(@$themeobject->current_widget['Question Activity']['locations']['show_title'])
				$themeobject->output('<h3 class="widget-title">Recent Activity <a href="'.qa_path_html('activity').'">View All</a></h3>');
				
			$themeobject->output('<div class="ra-question-activity-widget">');

			$q_list = $content['q_list']['qs'];
			
			$themeobject->output('<ul class="activity-list">');
			foreach ($q_list as $list){
				$themeobject->output('<li><span class="fav-star icon-heart'.(@$list['raw']['userfavoriteq'] ? ' active' : '').'"></span><a'.(is_featured($list['raw']['postid']) ? ' class="featured" ' : '').' href="'.$list['url'].'">'.cs_truncate($list['title'], 50).'<span class="time">'.implode(' ', $list['when']).'</span><span class="ans-count total-'.$list['raw']['acount'].'">'.$list['raw']['acount'].'</span></a></li>');
			}
			$themeobject->output('</ul>');
			$themeobject->output('</div>');
		}
	}
/*
	Omit PHP closing tag to help avoid accidental output
*/