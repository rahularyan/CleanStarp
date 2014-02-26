<?php
	class cs_featured_questions_widget {
		
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
		function carousel_item($type, $limit, $col_item = 1){
			require_once QA_INCLUDE_DIR.'qa-app-posts.php';
			$post = qa_db_query_sub('SELECT * FROM ^postmetas, ^posts  WHERE ^posts.type=$ and ( ^postmetas.postid = ^posts.postid and ^postmetas.title = "featured_question" ) ORDER BY ^posts.created DESC LIMIT #', $type, $limit);	
			$output ='<div class="item"><div class="row">';
			$i = 1;
			while($p = mysql_fetch_array($post)){
				if($type=='Q'){
					$what = _cs_lang('asked');
				}elseif($type=='A'){
					$what = _cs_lang('answered');
				}elseif('C'){
					$what = _cs_lang('commented');
				}
				
				$handle = qa_post_userid_to_handle($p['userid']);

				$output .= '<div class="slider-item col-sm-'.(12/$col_item).'">';
				$output .= '<div class="slider-item-inner">';
				$output .= '<div class="featured-image">'.get_featured_image($p['postid']).'</div>';
				if ($type=='Q'){
					$output .= '<div class="big-ans-count pull-left">'.$p['acount'].'<span> ans</span></div>';
				}elseif($type=='A'){
					$output .= '<div class="big-ans-count pull-left vote">'.$p['netvotes'].'<span>'._cs_lang('Vote').'</span></div>';
				}

				if($type=='Q'){
					$output .= '<h5><a href="'. qa_q_path_html($p['postid'], $p['title']) .'" title="'. $p['title'] .'">'.cs_truncate(qa_html($p['title']), 50).'</a></h5>';
				}elseif($type=='A'){
					$output .= '<h5><a href="'.cs_post_link($p['parentid']).'#a'.$p['postid'].'">'. cs_truncate(strip_tags($p['content']), 50).'</a></h5>';
				}else{
					$output .= '<h5><a href="'.cs_post_link($p['parentid']).'#c'.$p['postid'].'">'. cs_truncate(strip_tags($p['content']), 50).'</a></h5>';
				}
				
					
				$output .= '<div class="meta"><img src="'.cs_get_avatar($handle, 15, false).'" /><span class="icon-calendar-2">'.date('d M Y', strtotime($p['created'])).'</span>';	
				$output .= '<span class="vote-count">'.$p['netvotes'].' '._cs_lang('votes').'</span></div>';	
				
				$output .= '</div>';
				$output .= '</div>';
				if($col_item == $i){
					$output .= '</div></div><div class="item active"><div class="row">';
				}
				
				$i++;
			}
			$output .= '</div></div>';

			return $output;
		}
		function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
		{
			$widget_opt = $themeobject->current_widget['Featured Questions']['options'];

			$count = (isset($widget_opt['cs_ticker_count']) && !empty($widget_opt['cs_ticker_count'])) ?(int)$widget_opt['cs_ticker_count'] : 10;
			
			$category = (isset($widget_opt['cs_ticker_data']) && $widget_opt['cs_ticker_data'] == 'Category') ? $widget_opt['cs_ticker_slug'] : '';
			
			$tag = (isset($widget_opt['cs_ticker_data']) && $widget_opt['cs_ticker_data'] == 'Tags') ? $widget_opt['cs_ticker_slug'] : '';
			
			$themeobject->output('<div class="ra-featured-widget">');

			$themeobject->output('

            <div id="featured-slider" class="carousel slide">
                <!-- Carousel items -->
                <div class="carousel-inner">
                    '.$this->carousel_item('Q', '12', 4).'                    
                </div>
                <a class="left carousel-control icon-angle-left" href="#featured-slider" data-slide="prev"></a><a class="right carousel-control icon-angle-right" href="#featured-slider" data-slide="next"></a>
            </div>

			');
			$themeobject->output('</div>');
		}
	
	}
/*
	Omit PHP closing tag to help avoid accidental output
*/