<?php
	class cs_user_posts_widget {

		function cs_widget_form()
		{
			
			return array(
				'style' => 'wide',
				'fields' => array(
					'cs_up_count' => array(
						'label' => 'Numbers of post',
						'type' => 'number',
						'tags' => 'name="cs_up_count"',
						'value' => '10',
					),
					'cs_up_type' => array(
						'label' => 'Numbers of Questions',
						'type' => 'select',
						'tags' => 'name="cs_up_type"',
						'value' => array('Q' => 'Questions'),
						'options' => array(
							'Q' => 'Questions',
							'A' => 'Answers',
							'C' => 'Comments',
						)
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

		// output the list of selected post type
		function cs_user_post_list($handle, $type, $limit){
			$userid = qa_handle_to_userid($handle);
			require_once QA_INCLUDE_DIR.'qa-app-posts.php';
			$post = qa_db_query_sub('SELECT * FROM ^posts WHERE ^posts.type=$ and ^posts.userid=# ORDER BY ^posts.created DESC LIMIT #', $type, $userid, $limit);	
			
			$output = '<ul class="question-list users-post-widget">';
			while($p = mysql_fetch_array($post)){

				if($type=='Q'){
					$what = _cs_lang('asked');
				}elseif($type=='A'){
					$what = _cs_lang('answered');
				}elseif('C'){
					$what = _cs_lang('commented');
				}
				
				$handle = qa_post_userid_to_handle($p['userid']);

				$output .= '<li id="q-list-'.$p['postid'].'" class="question-item">';
				if ($type=='Q'){
					$output .= '<div class="big-ans-count pull-left">'.$p['acount'].'<span>'._cs_lang('Ans').'</span></div>';
				}elseif($type=='A'){
					$output .= '<div class="big-ans-count pull-left vote">'.$p['netvotes'].'<span>'._cs_lang('Vote').'</span></div>';
				}
				$output .= '<div class="list-right">';

				if($type=='Q'){
					$output .= '<h5><a href="'. qa_q_path_html($p['postid'], $p['title']) .'" title="'. $p['title'] .'">'.qa_html($p['title']).'</a></h5>';
				}elseif($type=='A'){
					$output .= '<h5><a href="'.cs_post_link($p['parentid']).'#a'.$p['postid'].'">'. substr(strip_tags($p['content']), 0, 50).'</a></h5>';
				}else{
					$output .= '<h5><a href="'.cs_post_link($p['parentid']).'#c'.$p['postid'].'">'. substr(strip_tags($p['content']), 0, 50).'</a></h5>';
				}
				
				$output .= '<div class="list-date"><span class="icon-calendar-2">'.date('d M Y', strtotime($p['created'])).'</span>';	
				$output .= '<span class="icon-chevron-up">'.$p['netvotes'].' '._cs_lang('votes').'</span></div>';	
				$output .= '</div>';	
				$output .= '</li>';
			}
			$output .= '</ul>';
			echo $output;
		}

		function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
		{
			$widget_opt = @$themeobject->current_widget['param']['options'];
			$handle = $qa_content['raw']['account']['handle'];
			
			if($widget_opt['cs_up_type'] == 'Q')
				$type_title = 'questions';
			elseif($widget_opt['cs_up_type'] == 'A')
				$type_title = 'answers';
			else
				$type_title = 'comments';
			
			if($widget_opt['cs_up_type'] != 'C')
				$type_link = '<a class="see-all" href="'.qa_path_html('user/'.$handle.'/'.$type_title).'">Show all</a>';
			
			if(@$themeobject->current_widget['param']['locations']['show_title'])
				$themeobject->output('<h3 class="widget-title">'.cs_name($handle).'\'s '.$type_title.@$type_link.'</h3>');
				
			$themeobject->output('<div class="ra-ua-widget">');
			$themeobject->output($this->cs_user_post_list($handle, @$widget_opt['cs_up_type'],  (int)$widget_opt['cs_ua_count']));
			$themeobject->output('</div>');
		}
	
	}
/*
	Omit PHP closing tag to help avoid accidental output
*/