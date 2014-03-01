<?php
	class widget_categories {

		function cs_widget_form()
		{
			
			return array(
				'style' => 'wide',
				'fields' => array(
					'cs_category_depth' => array(
						'label' => 'Categories Depth',
						'type' => 'select',
						'tags' => 'name="cs_category_depth"',
						'value' => 1,
						'options' => array('1' => 'One', '2'=> 'Two', '3' => 'Three'),
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

			return $output;
		}
		function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
		{
			$widget_opt = @$themeobject->current_widget['param']['options'];

			if(@$themeobject->current_widget['param']['locations']['show_title'])
				$themeobject->output('<h3 class="widget-title">Categories</h3>');
		
		
			$depth= (int)$widget_opt['cs_category_depth']; // change it to get from options
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
				
				$themeobject->output('<li><a class="icon-folder-close-alt" href="' . $category['url'] . '">' . $category['label'] . '<span>'.filter_var($category['note'], FILTER_SANITIZE_NUMBER_INT).'</span></a>');
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