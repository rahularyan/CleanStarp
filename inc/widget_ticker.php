<?php
	class cs_ticker_widget {
		
		function cs_widget_form()
		{
			
			return array(
				'style' => 'wide',
				'fields' => array(
					'cs_ticker_count' => array(
						'label' => 'Questions to show',
						'type' => 'number',
						'tags' => 'name="cs_ticker_count"',
						'value' => '10',
					),
					'cs_ticker_data' => array(
						'label' => 'Data from',
						'type' => 'select',
						'tags' => 'name="cs_ticker_data"',
						'value' => 'Category',
						'options' => array(
							'Category' => 'Category',
							'Tags' => 'Tags',
						)
					),
					'cs_ticker_slug' => array(
						'label' => 'Enter slug',
						'type' => 'text',
						'tags' => 'name="cs_ticker_slug"',
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
		function cs_relative_post_list($type, $limit, $categories, $tags, $return = false){
			require_once QA_INCLUDE_DIR.'qa-app-posts.php';
			if(!empty($categories)){
				
				$title = 'Questions in <a href="'.qa_path_html('category/'.$categories).'">'.$categories.'</a>';
				
				$post = qa_db_query_sub(
				'SELECT * FROM ^posts WHERE ^posts.type=$
				AND categoryid=(SELECT categoryid FROM ^categories WHERE ^categories.title=$ LIMIT 1) 
				ORDER BY ^posts.created DESC LIMIT #',
				$type, $categories, $limit);	
			}elseif(!empty($tags)){
				$title = 'Questions in <a href="'.qa_path_html('tag/'.$tags).'">'.$tags.'</a>';
				$post = qa_db_query_sub(
				'SELECT * FROM ^posts WHERE ^posts.type=$
				AND qa_posts.postid IN (SELECT postid FROM qa_posttags WHERE 
					wordid=(SELECT wordid FROM qa_words WHERE word=$ OR word=$ COLLATE utf8_bin LIMIT 1) ORDER BY postcreated DESC)
				ORDER BY ^posts.created DESC LIMIT #',
				$type, $tags, qa_strtolower($tags), $limit);
			}
			else
				return;
			
			$output = '<h3 class="widget-title">'.$title.'</h3>';
			
			$output .= '<ul class="question-list">';
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
				$output .= '<div class="pull-left avatar" data-handle="'.$handle.'" data-id="'. qa_handle_to_userid($handle).'"><img src="'.cs_get_avatar($handle, 35, false).'" /></div>';
				$output .= '<div class="list-right">';

				if($type=='Q'){

					$output .= '<a class="title" href="'. qa_q_path_html($p['postid'], $p['title']) .'" title="'. $p['title'] .'">'.cs_truncate(qa_html($p['title']), 50).'</a>';

				}elseif($type=='A'){

					$output .= '<p><a href="'.cs_post_link($p['parentid']).'#a'.$p['postid'].'">'. cs_truncate(strip_tags($p['content']),50).'</a></p>';

				}else{

					$output .= '<p><a href="'.cs_post_link($p['parentid']).'#c'.$p['postid'].'">'. cs_truncate(strip_tags($p['content']),50).'</a></p>';

				}
				$output .= '<div class="meta"><a href="'.qa_path_html('user/'.$handle).'">'.cs_name($handle).'</a> '.$what;
				if ($type=='Q'){

					$output .= ' <span class="vote-count">'.$p['netvotes'].' votes</span>';

					$output .= ' <span class="ans-count">'.$p['acount'].' ans</span>';

				}elseif($type=='A'){
					$output .= ' <span class="vote-count">'.$p['netvotes'].' votes</span>';
				}
				$output .= '</div></div>';	

				$output .= '</li>';
			}

			$output .= '</ul>';

			if($return)

				return $output;

			echo $output;

		}
		function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
		{
			$widget_opt = $themeobject->current_widget['RA Ticker']['options'];

			$count = (isset($widget_opt['cs_ticker_count']) && !empty($widget_opt['cs_ticker_count'])) ?(int)$widget_opt['cs_ticker_count'] : 10;
			
			$category = (isset($widget_opt['cs_ticker_data']) && $widget_opt['cs_ticker_data'] == 'Category') ? $widget_opt['cs_ticker_slug'] : '';
			
			$tag = (isset($widget_opt['cs_ticker_data']) && $widget_opt['cs_ticker_data'] == 'Tags') ? $widget_opt['cs_ticker_slug'] : '';
			
			$themeobject->output('<div class="ra-ticker-widget">');
			
			$themeobject->output($this->cs_relative_post_list('Q', $count, $category, $tag, true));
			$themeobject->output('</div>');
		}
	
	}
/*
	Omit PHP closing tag to help avoid accidental output
*/