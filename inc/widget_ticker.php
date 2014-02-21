<?php
	class cs_ticker_widget {
		
		function option_default($option)
		{
			if ($option=='ra_tag_cloud_count')
				return 20;
		}

		
		function admin_form()
		{
			$saved=false;
			
			if (qa_clicked('tag_cloud_save_button')) {
				qa_opt('ra_tag_cloud_count', (int)qa_post_text('ra_tag_cloud_count'));
				$saved=true;
			}
			
			return array(
				'ok' => $saved ? 'Tag cloud settings saved' : null,
				
				'fields' => array(
					array(
						'label' => 'Maximum tags to show',
						'type' => 'number',
						'value' => (int)qa_opt('ra_tag_cloud_count'),
						'suffix' => 'tags',
						'tags' => 'name="ra_tag_cloud_count"',
					),
	
				),
				
				'buttons' => array(
					array(
						'label' => 'Save Changes',
						'tags' => 'name="tag_cloud_save_button"',
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
			
			$themeobject->output(ra_post_list('Q', 10, true));
			$themeobject->output('</div>');
		}
	
	}
/*
	Omit PHP closing tag to help avoid accidental output
*/