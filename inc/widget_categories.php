<?php
	class widget_categories {

		function cs_widget_form()
		{
			
			return array(
				'style' => 'wide',
				'fields' => array(
					'cs_nu_count' => array(
						'label' => 'Categories Depth',
						'type' => 'number',
						'tags' => 'name="cs_category_depth"',
						'value' => '1',
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
		function cs_category_navigation_sub($sub_categories,$depth){
			if ($depth<=1) return;
			$output	= '';
			if (is_array($sub_categories))
				foreach ($sub_categories as $category){
					$output .= '<ul class="cs-category-widget-list-sub">';
					$output .= '<li><a href="">' . $category['title'] . '</a>';
					$sub_sub_categories = qa_db_select_with_pending(qa_db_category_sub_selectspec($category['categoryid']));
					$output .= $this->cs_category_navigation_sub($sub_sub_categories,$depth-1);
					$output .= '</li>';
					$output .= '</ul>';
				}
				/*	$navigation[qa_html($category['tags'])]=array(
						'url' => qa_path_html($pathprefix.$category['tags'], $pathparams),
						'label' => qa_html($category['title']),
						'popup' => qa_html(@$category['content']),
						'selected' => isset($selecteds[$category['categoryid']]),
						'note' => $showqcount ? ('('.qa_html(number_format($category['qcount'])).')') : null,
						'subnav' => qa_category_navigation_sub($parentcategories, $category['categoryid'], $selecteds,
							$pathprefix.$category['tags'].'/', $showqcount, $pathparams, $favoritemap),
						'categoryid' => $category['categoryid'],
						'favorited' => @$favoritemap['category'][$category['backpath']],
					);
				*/
			return $output;
		}
		function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
		{
		if ( qa_using_categories() ){}
			$depth=3; // change it to get from options
			$userid=qa_get_logged_in_userid();
			$categoryslugs=0;
			$countslugs=0;
			$themeobject->output('<div class="cs-category-widget">');
			$categories = qa_category_navigation(qa_db_select_with_pending(qa_db_category_nav_selectspec($categoryslugs, false, false, true)));
			unset($categories['all']);
			$themeobject->output('<ul class="cs-category-widget-list">');
			foreach ($categories as $category){
				//$sub_categories = qa_category_navigation(qa_db_select_with_pending(qa_db_category_nav_selectspec($categoryslugs, false, false, true)));
				$sub_categories = qa_db_select_with_pending(qa_db_category_sub_selectspec($category['categoryid']));
				
				$themeobject->output('<li><a href="' . $category['url'] . '">' . $category['label'] . '</a>');
				$themeobject->output($this->cs_category_navigation_sub($sub_categories,$depth));
				$themeobject->output('</li>');
			}
			$themeobject->output('</ul>');
			$themeobject->output('</div>');
		}
	
	}
/*
	Omit PHP closing tag to help avoid accidental output
*/