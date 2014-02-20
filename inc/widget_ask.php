<?php


	class ra_ask_widget {
		
		function option_default($option)
		{
			if ($option=='tag_cloud_count_tags')
				return 100;
			elseif ($option=='tag_cloud_font_size')
				return 24;
			elseif ($option=='tag_cloud_size_popular')
				return true;
		}

		
		function admin_form()
		{
			$saved=false;
			
			if (qa_clicked('tag_cloud_save_button')) {
				qa_opt('tag_cloud_count_tags', (int)qa_post_text('tag_cloud_count_tags_field'));
				qa_opt('tag_cloud_font_size', (int)qa_post_text('tag_cloud_font_size_field'));
				qa_opt('tag_cloud_size_popular', (int)qa_post_text('tag_cloud_size_popular_field'));
				$saved=true;
			}
			
			return array(
				'ok' => $saved ? 'Tag cloud settings saved' : null,
				
				'fields' => array(
					array(
						'label' => 'Maximum tags to show:',
						'type' => 'number',
						'value' => (int)qa_opt('tag_cloud_count_tags'),
						'suffix' => 'tags',
						'tags' => 'name="tag_cloud_count_tags_field"',
					),

					array(
						'label' => 'Starting font size:',
						'suffix' => 'pixels',
						'type' => 'number',
						'value' => (int)qa_opt('tag_cloud_font_size'),
						'tags' => 'name="tag_cloud_font_size_field"',
					),
					
					array(
						'label' => 'Font size represents tag popularity',
						'type' => 'checkbox',
						'value' => qa_opt('tag_cloud_size_popular'),
						'tags' => 'name="tag_cloud_size_popular_field"',
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
			
			if (isset($qa_content['categoryids']))
				$params=array('cat' => end($qa_content['categoryids']));
			else
				$params=null;
				
			$themeobject->output('<div class="ra-ask-widget">');
			$themeobject->output(
				'<form action="'.qa_path_html('ask', $params).'" method="post">',
					'<div class="input-group">
						  <input type="text"  name="title" class="form-control" id="ra-ask-search" placeholder="Your question in one sentence">
						  <span class="input-group-btn">
							<button class="icon-question btn" type="submit">Ask</button>
						  </span>
					</div>',
					'<input type="hidden" value="1" name="doask1">',
				'</form>'
			);
			$themeobject->output('</div>');
		}
	
	}
	

/*
	Omit PHP closing tag to help avoid accidental output
*/