<?php
	class cs_featured_questions_widget {
		
		function ra_widget_form()
		{
			
			return array(
				'style' => 'wide',
				'fields' => array(
					'ra_ticker_count' => array(
						'label' => 'Questions to show',
						'type' => 'number',
						'tags' => 'name="ra_ticker_count"',
						'value' => '10',
					),
					'ra_ticker_data' => array(
						'label' => 'Data from',
						'type' => 'select',
						'tags' => 'name="ra_ticker_data"',
						'value' => 'Category',
						'options' => array(
							'Category' => 'Category',
							'Tags' => 'Tags',
						)
					),
					'ra_ticker_slug' => array(
						'label' => 'Enter slug',
						'type' => 'text',
						'tags' => 'name="ra_ticker_slug"',
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
		function carousel_item($handle, $type, $limit){
			$userid = qa_handle_to_userid($handle);
			require_once QA_INCLUDE_DIR.'qa-app-posts.php';
			$post = qa_db_query_sub('SELECT * FROM ^posts WHERE ^posts.type=$ and ^posts.userid=# ORDER BY ^posts.created DESC LIMIT #', $type, $userid, $limit);	
			
			$output = '<ul class="question-list users-widget">';
			while($p = mysql_fetch_array($post)){

				if($type=='Q'){
					$what = _ra_lang('asked');
				}elseif($type=='A'){
					$what = _ra_lang('answered');
				}elseif('C'){
					$what = _ra_lang('commented');
				}
				
				$handle = qa_post_userid_to_handle($p['userid']);

				$output .= '<li id="q-list-'.$p['postid'].'" class="question-item">';
				if ($type=='Q'){
					$output .= '<div class="big-ans-count pull-left">'.$p['acount'].'<span>'._ra_lang('Ans').'</span></div>';
				}elseif($type=='A'){
					$output .= '<div class="big-ans-count pull-left vote">'.$p['netvotes'].'<span>'._ra_lang('Vote').'</span></div>';
				}
				$output .= '<div class="list-right">';

				if($type=='Q'){
					$output .= '<h5><a href="'. qa_q_path_html($p['postid'], $p['title']) .'" title="'. $p['title'] .'">'.qa_html($p['title']).'</a></h5>';
				}elseif($type=='A'){
					$output .= '<h5><a href="'.ra_post_link($p['parentid']).'#a'.$p['postid'].'">'. substr(strip_tags($p['content']), 0, 50).'</a></h5>';
				}else{
					$output .= '<h5><a href="'.ra_post_link($p['parentid']).'#c'.$p['postid'].'">'. substr(strip_tags($p['content']), 0, 50).'</a></h5>';
				}
				
				$output .= '<div class="list-date"><span class="icon-calendar-2">'.date('d M Y', strtotime($p['created'])).'</span>';	
				$output .= '<span class="icon-chevron-up">'.$p['netvotes'].' '._ra_lang('votes').'</span></div>';	
				$output .= '</div>';	
				$output .= '</li>';
			}
			$output .= '<li>';
			$output .= '<a class="see-all" href="#">Show all</a>';
			$output .= '</li>';
			$output .= '</ul>';
			echo $output;
		}
		function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
		{
			$widget_opt = $themeobject->current_widget['Featured Questions']['options'];

			$count = (isset($widget_opt['ra_ticker_count']) && !empty($widget_opt['ra_ticker_count'])) ?(int)$widget_opt['ra_ticker_count'] : 10;
			
			$category = (isset($widget_opt['ra_ticker_data']) && $widget_opt['ra_ticker_data'] == 'Category') ? $widget_opt['ra_ticker_slug'] : '';
			
			$tag = (isset($widget_opt['ra_ticker_data']) && $widget_opt['ra_ticker_data'] == 'Tags') ? $widget_opt['ra_ticker_slug'] : '';
			
			$themeobject->output('<div class="ra-ticker-widget">');
			
			$themeobject->output('

            <div id="myCarousel" class="carousel slide">
                <!-- Carousel items -->
                <div class="carousel-inner">
                    <div class="item active">
                        <div class="row">
                            <div class="col-sm-3">
								<a href="#x"><img src="http://placehold.it/500x500" alt="Image" class="img-responsive"></a>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam aliquet libero nec neque adipiscing rhoncus. Fusce imperdiet orci sed metus pellentesque facilisis.</p>
                            </div>
                            <div class="col-sm-3">
								<a href="#x"><img src="http://placehold.it/500x500" alt="Image" class="img-responsive"></a>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam aliquet libero nec neque adipiscing rhoncus. Fusce imperdiet orci sed metus pellentesque facilisis.</p>

                            </div>
                            <div class="col-sm-3"><a href="#x"><img src="http://placehold.it/500x500" alt="Image" class="img-responsive"></a>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam aliquet libero nec neque adipiscing rhoncus. Fusce imperdiet orci sed metus pellentesque facilisis.</p>

                            </div>
                            <div class="col-sm-3"><a href="#x"><img src="http://placehold.it/500x500" alt="Image" class="img-responsive"></a>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam aliquet libero nec neque adipiscing rhoncus. Fusce imperdiet orci sed metus pellentesque facilisis.</p>

                            </div>
                        </div>
                        <!--/row-->
                    </div>
                    <!--/item-->
                    <div class="item">
                        <div class="row">
                            <div class="col-sm-3"><a href="#x" class="thumbnail">
							<img src="http://placehold.it/250x250" alt="Image" class="img-responsive"></a>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam aliquet libero nec neque adipiscing rhoncus. Fusce imperdiet orci sed metus pellentesque facilisis.</p>

                            </div>
                            <div class="col-sm-3"><a href="#x" class="thumbnail">
							<img src="http://placehold.it/250x250" alt="Image" class="img-responsive"></a>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam aliquet libero nec neque adipiscing rhoncus. Fusce imperdiet orci sed metus pellentesque facilisis.</p>

                            </div>
                            <div class="col-sm-3"><a href="#x" class="thumbnail">
							<img src="http://placehold.it/250x250" alt="Image" class="img-responsive"></a>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam aliquet libero nec neque adipiscing rhoncus. Fusce imperdiet orci sed metus pellentesque facilisis.</p>

                            </div>
                            <div class="col-sm-3"><a href="#x" class="thumbnail">
							<img src="http://placehold.it/250x250" alt="Image" class="img-responsive"></a>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam aliquet libero nec neque adipiscing rhoncus. Fusce imperdiet orci sed metus pellentesque facilisis.</p>

                            </div>
                        </div>
                        <!--/row-->
                    </div>
                    
                </div>
                <!--/carousel-inner--> <a class="left carousel-control" href="#myCarousel" data-slide="prev">‹</a>

                <a
                class="right carousel-control" href="#myCarousel" data-slide="next">›</a>
            </div>
            <!--/myCarousel-->

			');
			$themeobject->output('</div>');
		}
	
	}
/*
	Omit PHP closing tag to help avoid accidental output
*/