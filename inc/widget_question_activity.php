<?php
	class cs_question_activity_widget {

		function ra_widget_form()
		{
			
			return array(
				'style' => 'wide',
				'fields' => array(
					'ra_qa_count' => array(
						'label' => 'Numbers of questions',
						'type' => 'number',
						'tags' => 'name="ra_qa_count"',
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
			$widget_opt = @$themeobject->current_widget['Question Activity']['options'];
			
			require_once QA_INCLUDE_DIR.'qa-db-selects.php';
			require_once QA_INCLUDE_DIR.'qa-app-format.php';
			require_once QA_INCLUDE_DIR.'qa-app-q-list.php';
			
			$categoryslugs=qa_request_parts(1);
			$countslugs=count($categoryslugs);
			$userid=qa_get_logged_in_userid();


		//	Get lists of recent activity in all its forms, plus category information
			
			list($questions1, $questions2, $questions3, $questions4, $categories, $categoryid)=qa_db_select_with_pending(
				qa_db_qs_selectspec($userid, 'created', 0, $categoryslugs, null, false, false, (int)$widget_opt['ra_qa_count']),
				qa_db_recent_a_qs_selectspec($userid, 0, $categoryslugs),
				qa_db_recent_c_qs_selectspec($userid, 0, $categoryslugs),
				qa_db_recent_edit_qs_selectspec($userid, 0, $categoryslugs),
				qa_db_category_nav_selectspec($categoryslugs, false, false, true),
				$countslugs ? qa_db_slugs_to_category_id_selectspec($categoryslugs) : null
			);
			
			if ($countslugs) {
				if (!isset($categoryid))
					return include QA_INCLUDE_DIR.'qa-page-not-found.php';
			
				$categorytitlehtml=qa_html($categories[$categoryid]['title']);
				$sometitle=qa_lang_html_sub('main/recent_activity_in_x', $categorytitlehtml);
				$nonetitle=qa_lang_html_sub('main/no_questions_in_x', $categorytitlehtml);

			} else {
				$sometitle=qa_lang_html('main/recent_activity_title');
				$nonetitle=qa_lang_html('main/no_questions_found');
			}

			
		//	Prepare and return content for theme

			$content =  qa_q_list_page_content(
				qa_any_sort_and_dedupe(array_merge($questions1, $questions2, $questions3, $questions4)), // questions
				$widget_opt['ra_qa_count'], // questions per page
				0, // start offset
				null, // total count (null to hide page links)
				$sometitle, // title if some questions
				$nonetitle, // title if no questions
				$categories, // categories for navigation
				$categoryid, // selected category id
				true, // show question counts in category navigation
				'activity/', // prefix for links in category navigation
				qa_opt('feed_for_activity') ? 'activity' : null, // prefix for RSS feed paths (null to hide)
				qa_html_suggest_qs_tags(qa_using_tags(), qa_category_path_request($categories, $categoryid)), // suggest what to do next
				null, // page link params
				null // category nav params
			);
			if(@$themeobject->current_widget['Question Activity']['locations']['show_title'])
				$themeobject->output('<h3 class="widget-title">Recent Activity <a href="'.qa_path_html('activity').'">View All</a></h3>');
				
			$themeobject->output('<div class="ra-question-activity-widget">');

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