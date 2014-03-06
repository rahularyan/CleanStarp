<?php
	class cs_widget_recent_answers {

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
			$selectsort='created';
			$userid = qa_get_logged_in_userid();
			$questions = cs_get_cache_select_selectspec(qa_db_recent_a_qs_selectspec(null, 0, null, null, false, true,(int)$widget_opt['cs_qa_count']));

			if(@$themeobject->current_widget['Question Activity']['locations']['show_title'])
				$themeobject->output('<h3 class="widget-title">Recent Comments</h3>');
				
			$themeobject->output('<div class="ra-questions-widget">');
			
			$themeobject->output('<ul class="questions-list">');
			foreach ($questions as $post){
				$when = qa_when_to_html($post['created'], 7); // 7 days
				$content = cs_truncate(strip_tags($post['ocontent']), 50);
				if (strlen($content)<1)
					$content = cs_truncate($post['title'], 50);
				$themeobject->output('<li><span class=""></span><a href="'. qa_q_path_html($post['postid'], $post['title'],true, 'A',$post['opostid']) .'">'. $content .'<span class="time">'.implode(' ', $when).'</span><span class="ans-count total-'.$post['acount'].'">'.$post['acount'].'</span></a></li>');
			}
			$themeobject->output('</ul>');
			$themeobject->output('</div>');
		}
	}
/*
	Omit PHP closing tag to help avoid accidental output
*/