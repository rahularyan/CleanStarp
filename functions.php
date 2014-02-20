<?php
	/* don't allow this page to be requested directly from browser */	
	if (!defined('QA_VERSION')) {
			header('Location: /');
			exit;
	}


function ra_lang($str){
	global $qa_content;
	if(isset($qa_content['lang'][strtolower($str)]))
		echo $qa_content['lang'][strtolower($str)];
	else
		echo $str;
}
function _ra_lang($str){
	global $qa_content;
	if(isset($qa_content['lang'][strtolower($str)]))
		return $qa_content['lang'][strtolower($str)];
	else
		return $str;
}


function ra_user_data($handle){
	$userid = qa_handle_to_userid($handle);
	$identifier=QA_FINAL_EXTERNAL_USERS ? $userid : $handle;
	if(defined('QA_WORDPRESS_INTEGRATE_PATH')){
		
		$u=qa_db_select_with_pending( 
			qa_db_user_rank_selectspec($handle),
			qa_db_user_points_selectspec($identifier)
		);
		$user = array();
		$user[]['points'] = $u[1]['points'];
		unset($u[1]['points']);
		$user[] = 0;
		$user[] = $u[1];
	}else{
		$user=qa_db_select_with_pending( 
			qa_db_user_account_selectspec($userid, true),
			qa_db_user_rank_selectspec($handle),
			qa_db_user_points_selectspec($identifier)
		);
	}
	return $user;
}	



function ra_get_avatar($handle, $size = 40, $html =true){
	$userid = qa_handle_to_userid($handle);
	if(defined('QA_WORDPRESS_INTEGRATE_PATH')){
		$img_html = get_avatar( qa_get_user_email($userid), $size);
	}else{
		if (!isset($handle)){
			if ( (qa_opt('avatar_allow_gravatar')||qa_opt('avatar_allow_upload')) && qa_opt('avatar_default_show') && strlen(qa_opt('avatar_default_blobid')) )
					$img_html=qa_get_avatar_blob_html(qa_opt('avatar_default_blobid'), qa_opt('avatar_default_width'), qa_opt('avatar_default_height'), $size);
		}else{
			
			$f = ra_user_data($handle);
			$img_html = 	QA_FINAL_EXTERNAL_USERS
				? qa_get_external_avatar_html($userid, $size, true)
				: qa_get_user_avatar_html($f[0]['flags'], $f[0]['email'], $handle, $f[0]['avatarblobid'], $size, $size, $size, true);
			
		}

	}
	preg_match( '@src="([^"]+)"@' , $img_html , $match );
	if($html)
		return '<a href="'.qa_path_html('user/'.$handle).'">'.(defined('QA_WORDPRESS_INTEGRATE_PATH') ?  '<img src="'.$match[1].'" />':$img_html).'</a>';		
	elseif(isset($match[1]))
		return $match[1];
}

function ra_post_type($id){
	$result = qa_db_read_one_value(qa_db_query_sub('SELECT type FROM ^posts WHERE postid=#', $id ),true);
	return $result;
}

function ra_post_status($item){
	// this will return a notice whether question is open, closed, duplicate or solved
	
	if (@$item['answer_selected'] || @$item['raw']['selchildid']){	
		$notice =   '<span class="post-status selected">'._ra_lang('Solved').'</span>' ;
	}elseif(@$item['raw']['closedbyid']){
		$type = ra_post_type(@$item['raw']['closedbyid']);
		if($type == 'Q')
			$notice =   '<span class="post-status duplicate">'._ra_lang('Duplicate').'</span>' ;	
		else
			$notice =   '<span class="post-status closed">'._ra_lang('Closed').'</span>' ;	
	}else{
		$notice =   '<span class="post-status open">'._ra_lang('Open').'</span>' ;	
	}
	return $notice;
}

function ra_get_excerpt($id){
	$result = qa_db_read_one_value(qa_db_query_sub('SELECT content FROM ^posts WHERE postid=#', $id ),true);
	return strip_tags($result);
}
function ra_truncate($string, $limit, $pad="...") {
	if(strlen($string) <= $limit) 
		return $string; 
	else{ 
		preg_match('/^.{1,'.$limit.'}\b/s', $string, $match);
		return $match[0].$pad;
	} 

}
		
function ra_user_profile($handle, $field =NULL){
	$userid = qa_handle_to_userid($handle);
	if(defined('QA_WORDPRESS_INTEGRATE_PATH')){
		return get_user_meta( $userid );
	}else{
		$query = qa_db_select_with_pending(qa_db_user_profile_selectspec($userid, true));
		
		if(!$field) return $query;
		if (isset($query[$field]))
			return $query[$field];
	}
	
	return false;
}	

function ra_user_badge($handle) {
	if(qa_opt('badge_active')){
	$userids = qa_handles_to_userids(array($handle));
	$userid = $userids[$handle];

	
	// displays small badge widget, suitable for meta
	
	$result = qa_db_read_all_values(
		qa_db_query_sub(
			'SELECT badge_slug FROM ^userbadges WHERE user_id=#',
			$userid
		)
	);

	if(count($result) == 0) return;
	
	$badges = qa_get_badge_list();
	foreach($result as $slug) {
		$bcount[$badges[$slug]['type']] = isset($bcount[$badges[$slug]['type']])?$bcount[$badges[$slug]['type']]+1:1; 
	}
	$output='<ul class="user-badge clearfix">';
	for($x = 2; $x >= 0; $x--) {
		if(!isset($bcount[$x])) continue;
		$count = $bcount[$x];
		if($count == 0) continue;

		$type = qa_get_badge_type($x);
		$types = $type['slug'];
		$typed = $type['name'];

		$output.='<li class="badge-medal '.$types.'"><i class="icon-badge" title="'.$count.' '.$typed.'"></i><span class="badge-pointer badge-'.$types.'-count" title="'.$count.' '.$typed.'"> '.$count.'</span></li>';
	}
	$output = substr($output,0,-1);  // lazy remove space
	$output.='</ul>';
	return($output);
	}
}
function ra_name($handle){
	return strlen(ra_user_profile($handle, 'name')) ? ra_user_profile($handle, 'name') : $handle;
}


// output the list of selected post type
function ra_user_post_list($handle, $type, $limit){
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
function ra_post_link($id){
	$type = mysql_result(qa_db_query_sub('SELECT type FROM ^posts WHERE postid = "'.$id.'"'), 0);
	
	if($type == 'A')
		$id = mysql_result(qa_db_query_sub('SELECT parentid FROM ^posts WHERE postid = "'.$id.'"'),0);
	
	$post = qa_db_query_sub('SELECT title FROM ^posts WHERE postid = "'.$id.'"');
	return qa_q_path_html($id, mysql_result($post,0));
}	

function ra_tag_list($limit = 20){
	$populartags=qa_db_single_select(qa_db_popular_tags_selectspec(0, $limit));
			
	$i= 1;
	foreach ($populartags as $tag => $count) {							
		echo '<li><a class="icon-tag" href="'.qa_path_html('tag/'.$tag).'">'.qa_html($tag).'<span>'.filter_var($count, FILTER_SANITIZE_NUMBER_INT).'</span></a></li>';
	}
}

// output the list of selected post type
function ra_post_list($type, $limit){
	require_once QA_INCLUDE_DIR.'qa-app-posts.php';
	$post = qa_db_query_sub('SELECT * FROM ^posts WHERE ^posts.type=$ ORDER BY ^posts.created DESC LIMIT #', $type, $limit);	
	
	$output = '<ul class="question-list">';
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
		$output .= '<div class="pull-left avatar" data-handle="'.$handle.'" data-id="'. qa_handle_to_userid($handle).'">'.ra_get_avatar($handle, 35).'</div>';
		$output .= '<div class="list-right">';
		
		$output .= '<h5><a href="'.qa_path_html('user/'.$handle).'">'.ra_name($handle).'</a> '.$what.'</h5>';	

		if($type=='Q'){
			$output .= '<p><a href="'. qa_q_path_html($p['postid'], $p['title']) .'" title="'. $p['title'] .'">'.qa_html($p['title']).'</a></p>';
		}elseif($type=='A'){
			$output .= '<p><a href="'.ra_post_link($p['parentid']).'#a'.$p['postid'].'">'. substr(strip_tags($p['content']), 0, 50).'</a></p>';
		}else{
			$output .= '<p><a href="'.ra_post_link($p['parentid']).'#c'.$p['postid'].'">'. substr(strip_tags($p['content']), 0, 50).'</a></p>';
		}
		
		
		if ($type=='Q'){
			$output .= '<div class="counts"><div class="vote-count icon-chevron-up"><span>'.$p['netvotes'].'</span></div>';
			$output .= '<div class="ans-count icon-chat-4"><span>'.$p['acount'].'</span></div></div>';
		}elseif($type=='A'){
			$output .= '<div class="counts"><div class="vote-count icon-chevron-up"><span>'.$p['netvotes'].'</span></div>';
		}

		$output .= '</div>';	
		$output .= '</li>';
	}
	$output .= '</ul>';
	echo $output;
}
function ra_url_grabber($str) {
	preg_match_all(
	  '#<a\s
		(?:(?= [^>]* href="   (?P<href>  [^"]*) ")|)
		(?:(?= [^>]* title="  (?P<title> [^"]*) ")|)
		(?:(?= [^>]* target=" (?P<target>[^"]*) ")|)
		[^>]*>
		(?P<text>[^<]*)
		</a>
	  #xi',
	  $str,
	  $matches,
	  PREG_SET_ORDER
	);
	

	foreach($matches as $match) {
	 return '<a href="'.$match['href'].'" title="'.$match['title'].'">'.$match['text'].'</a>';
	}	
}

function ra_register_widget_position($widget_array){
	if(is_array($widget_array)){
		qa_opt('ra_widgets_positions', serialize($widget_array));
	}
	return;
}

function ra_position_active($name){
	$widgets = unserialize(qa_opt('ra_widgets'));
	$template = qa_request(1);
	$template = (!empty($template) ? $template : 'home' );
	if(is_array($widgets) && !empty($widgets[$name]) && isset($widgets[$name])){
		foreach ($widgets[$name] as $t){			
			if(isset($t[$template]) && (bool)$t[$template])
				return true;
		}
		
	}
	return false;
}

function ra_get_template_array(){
	return array(
		'qa' 			=> 'QA',
		'home' 			=> 'Home',
		'ask' 			=> 'Ask',
		'question' 		=> 'Question',
		'questions' 	=> 'Questions',
		'activity' 		=> 'Activity',
		'unanswered' 	=> 'Unanswered',
		'hot' 			=> 'Hot',
		'tags' 			=> 'Tags',
		'tag' 			=> 'Tag',
		'categories' 	=> 'Categories',
		'users' 		=> 'Users',
		'user' 			=> 'User',
		'account' 		=> 'Account',
		'favorite' 		=> 'Favorite',
		'user-wall' 	=> 'User Wall',
		'user-activity' => 'User Activity',
		'user-questions' => 'User Questions',
		'user-answers' 	=> 'User Answers',
		'custom' 		=> 'Custom',
		'login' 		=> 'Login',
		'feedback' 		=> 'Feedback',
		'updates' 		=> 'Updates',
		'search' 		=> 'Search',
		'admin' 		=> 'Admin'
	);
}
