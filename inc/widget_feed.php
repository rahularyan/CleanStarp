<?php
	class cs_feed_widget {

		function ra_widget_form()
		{
			
			return array(
				'style' => 'wide',
				'fields' => array(
					'cs_feed_title_field' => array(
						'label' => 'Widget Title:',
						'type' => 'string',
						'value' => qa_opt('cs_feed_title'),
						'tags' => 'NAME="cs_feed_title_field"',
					),
					'cs_feed_url_field' => array(
						'label' => 'Feed URL:',
						'type' => 'string',
						'value' => qa_opt('cs_feed_url'),
						'tags' => 'NAME="cs_feed_url_field"',
					),
					'cs_feed_count_field' => array(
						'label' => 'number of recent feeds:',
						'suffix' => 'item',
						'type' => 'number',
						'value' => (int)qa_opt('cs_feed_count'),
						'tags' => 'NAME="cs_feed_count_field"',
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
			$url = qa_opt('cs_feed_url');
			$count=(int)qa_opt('cs_feed_count');
			$title=qa_opt('cs_feed_title');

			$themeobject->output('<aside class="qa-feed-widget">');
				$themeobject->output('<H2 class="qa-feed-header" style="margin-top:0; padding-top:0;">'.$title.'</H2>');

			$file = Q_THEME_DIR . '/cache/feed_' . $title . '_content.txt';
			$modified = @filemtime( $file );
			$now = time();
			$interval = 3600; // 1 hour
			// Cache File
			if ( empty($modified) || ( ( $now - $modified ) > $interval ) ) {
				// read live content
				$content = file_get_contents($url);
				if ( $content ) {
				// cache content
					$cache = fopen( $file, 'w' );
					fwrite($cache, $content);
					fclose( $cache );
				}
			}else{
				//read content from cache
				$content = file_get_contents( $file );
			}


			$x = new SimpleXmlElement($content);  
			echo '<ul class="qa-feed-list">'; 
			$i=0;
			foreach($x->channel->item as $entry) {  
				echo "<li class=\"qa-feed-item\"><a href='$entry->link' title='$entry->title'>" . $entry->title . "</a></li>";  
				$i++;
				if ($i>=$count)
					break;
			}  
			echo "</ul>";  
			
			
			$themeobject->output('</aside>');		}
	
	}
/*
	Omit PHP closing tag to help avoid accidental output
*/