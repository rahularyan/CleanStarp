<?php
	class cs_ticker_widget {
		
		function option_default($option)
		{
			if ($option=='ra_tag_cloud_count')
				return 20;
		}

		
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
		

		function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
		{
			$widget_opt = $themeobject->current_widget['RA Ticker']['options'];

			$count = (isset($widget_opt['ra_ticker_count']) && !empty($widget_opt['ra_ticker_count'])) ? $widget_opt['ra_ticker_count'] : 10;
			
			$category = (isset($widget_opt['ra_ticker_data']) && $widget_opt['ra_ticker_data'] == 'Category') ? $widget_opt['ra_ticker_slug'] : '';
			
			$tag = (isset($widget_opt['ra_ticker_data']) && $widget_opt['ra_ticker_data'] == 'Tags') ? $widget_opt['ra_ticker_slug'] : '';
			
			$themeobject->output('<div class="ra-ticker-widget">');
			
			$themeobject->output(ra_relative_post_list('Q', $count, $category, $tag, true));
			$themeobject->output('</div>');
		}
	
	}
/*
	Omit PHP closing tag to help avoid accidental output
*/