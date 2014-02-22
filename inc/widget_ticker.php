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
					),
					'ra_ticker_data' => array(
						'label' => 'Data from',
						'type' => 'select',
						'tags' => 'name="ra_ticker_data"',
						'options' => array(
							'Category' => 'Category',
							'Tags' => 'Tags',
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
		

		function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
		{
			$themeobject->output('<div class="ra-ticker-widget">');
			
			//$themeobject->output(ra_relative_post_list('Q', 10,'','ipsum', true));
			$themeobject->output(ra_relative_post_list('A', 10,'','dolor', true));
			$themeobject->output('</div>');
		}
	
	}
/*
	Omit PHP closing tag to help avoid accidental output
*/