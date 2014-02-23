<?php
	class cs_activity_widget {

		function ra_widget_form()
		{
			
			return array(
				'style' => 'wide',
				'fields' => array(
					'ra_tc_count' => array(
						'label' => 'Numbers of user',
						'type' => 'number',
						'tags' => 'name="ra_tc_count"',
						'value' => '10',
					),
					'ra_tc_avatar' => array(
						'label' => 'Avatar Size',
						'type' => 'number',
						'tags' => 'name="ra_tc_avatar"',
						'value' => '30',
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

		function list_events($query, $events_type, $limit) {
			$countEvents = 0;
			$o = '<ul class="ra-activity">';
			while ( ($row = qa_db_read_one_assoc($query,true)) !== null ) {
				if(in_array($row['event'], $events_type)) {
					$qTitle = '';			

					$toURL = str_replace("\t","&",$row['params']);
				
					parse_str($toURL, $data);  
					
					$linkToPost = "-";
					
					$postid = (isset($data['postid'])) ? $data['postid'] : null;
					if($postid !== null) {
						$getPostType = mysql_fetch_array( qa_db_query_sub("SELECT type,parentid FROM `^posts` WHERE `postid` = #", $postid) );
						$postType = $getPostType[0]; // type, and $getPostType[1] is parentid
						if($postType=="A") {
							$getQtitle = mysql_fetch_array( qa_db_query_sub("SELECT title FROM `^posts` WHERE `postid` = # LIMIT 1", $getPostType[1]) );
							$qTitle = (isset($getQtitle[0])) ? $getQtitle[0] : "";
							// get correct public URL
							$activity_url = qa_path_html(qa_q_request($getPostType[1], $qTitle), null, qa_opt('site_url'), null, null);
							$linkToPost = $activity_url."?show=".$postid."#a".$postid;
						}
						else if($postType=="C") {
							// get question link from answer
							$getQlink = mysql_fetch_array( qa_db_query_sub("SELECT parentid,type FROM `^posts` WHERE `postid` = # LIMIT 1", $getPostType[1]) );
							$linkToQuestion = $getQlink[0];
							if($getQlink[1]=="A") {
								$getQtitle = mysql_fetch_array( qa_db_query_sub("SELECT title FROM `^posts` WHERE `postid` = # LIMIT 1", $getQlink[0]) );
								$qTitle = (isset($getQtitle[0])) ? $getQtitle[0] : "";
								// get correct public URL
								$activity_url = qa_path_html(qa_q_request($linkToQuestion, $qTitle), null, qa_opt('site_url'), null, null);
								$linkToPost = $activity_url."?show=".$postid."#c".$postid;
							}
							else {
								// default: comment on question
								$getQtitle = mysql_fetch_array( qa_db_query_sub("SELECT title FROM `^posts` WHERE `postid` = # LIMIT 1", $getPostType[1]) );
								$qTitle = (isset($getQtitle[0])) ? $getQtitle[0] : "";
								// get correct public URL
								$activity_url = qa_path_html(qa_q_request($getPostType[1], $qTitle), null, qa_opt('site_url'), null, null);
								$linkToPost = $activity_url."?show=".$postid."#c".$postid;
							}
						}
						// if question is hidden, do not show frontend!
						else if($postType=="Q_HIDDEN") {
							$qTitle = '';
						}
						else {
							// question has correct postid to link
							// $questionTitle = (isset($data['title'])) ? $data['title'] : "";
							$getQtitle = mysql_fetch_array( qa_db_query_sub("SELECT title FROM `^posts` WHERE `postid` = # LIMIT 1", $postid) );
							$qTitle = (isset($getQtitle[0])) ? $getQtitle[0] : "";
							// get correct public URL
							// $activity_url = qa_path_html(qa_q_request($getPostType[1], $qTitle), null, qa_opt('site_url'), null, null);
							$activity_url = qa_path_html(qa_q_request($postid, $qTitle), null, qa_opt('site_url'), null, null);
							$linkToPost = $activity_url;
						}
					}elseif($row['event'] == 'badge_awarded' && function_exists('qa_get_badge_type_by_slug')){
						$toURL = str_replace("\t","&",$row['params']);			
						parse_str($toURL, $data);
						
						$badge = qa_get_badge_type_by_slug($data['badge_slug']);
						$badge_type = $badge['slug'];
						$badge_name = qa_opt('badge_'.$data['badge_slug'].'_name');
						$var = qa_opt('badge_'.$data['badge_slug'].'_var');
						$qTitle =  $badge_name.' - '.qa_badge_desc_replace($data['badge_slug'],$var,false);
						$linkToPost = qa_path_html('user/'.$row['handle']);
					}
					
					$username = (is_null($row['handle'])) ? _ra_lang('Anonymous') : htmlspecialchars($row['handle']);
					$usernameLink = (is_null($row['handle'])) ? _ra_lang('Anonymous') : '<a target="_blank" class="qa-user-link" href="'.qa_opt('site_url').'user/'.$row['handle'].'">'.ra_name($row['handle']).'</a>';
					
					// set event name and css class
					$eventName = '';
					if($row['event']=="q_post") {
						$eventName = _ra_lang('asked');
					}
					else if($row['event']=="a_post") {
						$eventName = _ra_lang('answered');
					}
					else if($row['event']=="c_post") {
						$eventName = _ra_lang('commented');	
					}
					else if($row['event']=="a_select") {
						$eventName = _ra_lang('selected an answer');
					}				
					else if($row['event']=="badge_awarded") {
						$eventName = _ra_lang('earned a badge');
					}			
					
					// set event icon class
					
					if($row['event']=="q_post") {
						$event_icon = 'icon-question question';
					}
					else if($row['event']=="a_post") {
						$event_icon = 'icon-chat-3 ans';
					}
					else if($row['event']=="c_post") {
						$event_icon = 'icon-chat-2 comment';
					}
					else if($row['event']=="a_select") {
						$event_icon = 'icon-checkmark selected';
					}
					else if($row['event']=="badge_awarded") {
						$event_icon = 'icon-badge badge-icon '.@$badge_type;
					}
					$date = new DateTime($row['datetime']);

					$timeCode = implode('', qa_when_to_html( $date->getTimestamp(), qa_opt('show_full_date_days')));
					$time = '<span class="time">'.$timeCode.'</span>';
					
					// if question title is empty, question got possibly deleted, do not show frontend!
					if($qTitle=='') {
						continue;
					}

					$qTitleShort = mb_substr($qTitle,0,22,'utf-8'); // shorten question title to 22 chars
					$qTitleShort2 = (strlen($qTitle)>50) ? htmlspecialchars(mb_substr($qTitle,0,50,'utf-8')) .'&hellip;' : htmlspecialchars($qTitle); // shorten question title			

					$o .= '<li class="event-item">';
					$o .= '<div class="event-icon pull-left '.$event_icon.'"></div>';
					$o .= '<div class="event-inner">';	
					
					$o .= '<div class="avatar pull-left" data-handle="'.@$row['handle'].'" data-id="'. qa_handle_to_userid($row['handle']).'"><img src="'.ra_get_avatar(@$row['handle'], 15, false).'" /></div>';
						
					$o .= '<div class="event-content">';			
					$o .= '<strong>'.$usernameLink.' '.$eventName.' '.$time.'</strong>';			
					
					if($row['event']=="badge_awarded")
						$o .= '<strong class="event-title">'.$qTitleShort2.'</strong>';						
					else
						$o .= '<a class="event-title" href="'.$linkToPost.'">'.$qTitleShort2.'</a>';
					
					$o .= '</div>';	
					$o .= '</div>';	
					$o .= '</li>';
					$countEvents++;
					if($countEvents>=$limit) {
						break;
					}
				}
			}
			$o .= '</ul>';
			return $o;
		} 
		function ra_events($limit =10, $events_type = false){
			if(!$events_type)
				$events_type = array('q_post', 'a_post', 'c_post', 'a_select', 'badge_awarded');
			
			// query last 3 events
			$query = qa_db_query_sub("SELECT datetime,ipaddress,handle,event,params FROM `^eventlog` WHERE `event`='q_post' OR `event`='a_post' OR `event`='c_post' OR `event`='a_select' OR `event`='badge_awarded' ORDER BY datetime DESC LIMIT $limit");

			$recentEvents = '';

			return $this->list_events($query, $events_type, $limit);
		}

		function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
		{
			$widget_opt = @$themeobject->current_widget['Activity']['options'];

			if(@$themeobject->current_widget['Activity']['locations']['show_title'])
				$themeobject->output('<h3 class="widget-title">Activity</h3>');
				
			$themeobject->output('<div class="ra-tags-widget">');
			$themeobject->output($this->ra_events(10));
			$themeobject->output('</div>');
		}
	
	}
/*
	Omit PHP closing tag to help avoid accidental output
*/