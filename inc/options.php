<?php
/* don't allow this page to be requested directly from browser */	
if (!defined('QA_VERSION')) {
		header('Location: /');
		exit;
}
//class qa_html_theme extends qa_html_theme_base
class qa_html_theme_layer extends qa_html_theme_base {

	var $theme_directory;
	var $theme_url;
	function qa_html_theme_layer($template, $content, $rooturl, $request)
	{
		global $qa_layers;
		$this->theme_directory = $qa_layers['Theme Options']['directory'];
		$this->theme_url = $qa_layers['Theme Options']['urltoroot'];
		qa_html_theme_base::qa_html_theme_base($template, $content, $rooturl, $request);
	}
	
	function option_default($option)
	{
		if ($option=='option_ra_home_layout'):
			return 'modern';
		elseif($option == 'ra_logo'):
			return qa_opt('site_url').'qa-theme/'.qa_get_site_theme().'/images/logo.png';
		endif;
	}
	
	function doctype(){
		// Setup Navigation
		global $qa_request;
		$this->content['navigation']['main']['themeoptions'] = array(
			'label' => 'Theme Options',
			'url' => qa_path_html('themeoptions'),
		);
		if($qa_request == 'themeoptions') {
			$this->content['navigation']['main']['themeoptions']['selected'] = true;
			$this->content['navigation']['main']['selected'] = true;
			$this->template="themeoptions";
			$this->content['site_title']="Theme Options";
			$this->content['error']="";
			$this->content['suggest_next']="";
			$this->content['title']="Theme Options";
			//$this->content['custom']='';
		
			$saved=false;
			if (qa_clicked('ra_save_button')) {	
				if ($_FILES['ra_logo_field']['size'] > 0){
					if(getimagesize($_FILES['ra_logo_field']['tmp_name']) >0){
						$url		= qa_opt('site_url').'qa-theme/'.qa_get_site_theme().'/images/';
						$uploaddir 	= QA_THEME_DIR.qa_get_site_theme().'/images/';
						$uploadfile = $uploaddir . basename($_FILES['ra_logo_field']['name']);
						move_uploaded_file($_FILES['ra_logo_field']['tmp_name'], $uploadfile);
						
						qa_opt('ra_logo', $url.$_FILES['ra_logo_field']['name']);
					}
				}
				if ($_FILES['ra_favicon_field']['size'] > 0){
					if(getimagesize($_FILES['ra_favicon_field']['tmp_name']) >0){
						$url		= qa_opt('site_url').'qa-theme/'.qa_get_site_theme().'/images/';
						$uploaddir 	= QA_THEME_DIR.qa_get_site_theme().'/images/';
						$uploadfile = $uploaddir . basename($_FILES['ra_favicon_field']['name']);
						move_uploaded_file($_FILES['ra_favicon_field']['tmp_name'], $uploadfile);
						
						qa_opt('ra_favicon', $url.$_FILES['ra_favicon_field']['name']);
					}
				}
				// Advertisment
				$AdsCount = (int)qa_post_text('adv_number'); // number of advertisement items
				$ads=array();
				$i=0;
				while(($AdsCount>0) and ($i<100)){// don't create an infinite loop
					if (null !== qa_post_text('adv_adsense_' . $i)){
						// add adsense ads
						$ads[$i]['adv_adsense'] = qa_post_text('adv_adsense_' . $i);
						$ads[$i]['adv_location'] = qa_post_text('adv_location_' . $i);
						$AdsCount--;
					}elseif ( (@getimagesize(@$_FILES['ra_adv_image_' . $i]['tmp_name']) >0) or (null !== qa_post_text('adv_image_title_' . $i)) or (null !== qa_post_text('adv_image_link_' . $i)) or (null !== qa_post_text('adv_location_' . $i)) ) {
						// add static ads
						if(@getimagesize(@$_FILES['ra_adv_image_' . $i]['tmp_name']) >0){
							$url		= qa_opt('site_url').'qa-theme/'.qa_get_site_theme().'/images/';
							$uploaddir 	= QA_THEME_DIR.qa_get_site_theme().'/images/';
							$uploadfile = $uploaddir . basename($_FILES['ra_adv_image_' . $i]['name']);
							move_uploaded_file($_FILES['ra_adv_image_' . $i]['tmp_name'], $uploadfile);
							$ads[$i]['adv_image'] = $url.$_FILES['ra_adv_image_' . $i]['name'];
						}else if(null !== qa_post_text('adv_image_url_' . $i)){
							$ads[$i]['adv_image'] =  qa_post_text('adv_image_url_' . $i);
						}
						$ads[$i]['adv_image_title'] = qa_post_text('adv_image_title_' . $i);
						$ads[$i]['adv_image_link'] = qa_post_text('adv_image_link_' . $i);
						$ads[$i]['adv_location'] = qa_post_text('adv_location_' . $i);
						$AdsCount--;
					}
					$i++;
				}
				qa_opt('ra_advs',json_encode($ads));
				
				qa_opt('enable_adv_list', (bool)qa_post_text('option_ads_below_question_title'));
				qa_opt('ads_below_question_title', base64_encode($_REQUEST['option_ads_below_question_title']));
				qa_opt('ads_after_question_content', base64_encode($_REQUEST['option_ads_after_question_content']));

				//general
				qa_opt('google_analytics', qa_post_text('option_google_analytics'));	
				qa_opt('ra_colla_comm', (bool)qa_post_text('option_ra_colla_comm'));
				qa_opt('show_real_name', (bool)qa_post_text('option_show_real_name'));
				qa_opt('users_table_layout', (bool)qa_post_text('option_users_table_layout'));
				qa_opt('theme_layout', qa_post_text('option_theme_layout'));
				
				//Layout
				qa_opt('ra_home_layout', qa_post_text('option_ra_home_layout'));				
				qa_opt('ra_enable_except', (bool)qa_post_text('option_ra_enable_except'));
				qa_opt('ra_except_len', (int)qa_post_text('option_ra_except_len'));
				qa_opt('horizontal_voting_btns', (bool)qa_post_text('option_horizontal_voting_btns'));
				qa_opt('enble_back_to_top', (bool)qa_post_text('option_enble_back_to_top'));
				qa_opt('back_to_top_location', qa_post_text('option_back_to_top_location'));
				qa_opt('ra_enable_avatar_lists', (bool)qa_post_text('option_ra_enable_avatar_lists'));
				if (qa_opt('ra_enable_avatar_lists'))
					qa_opt('avatar_q_list_size',35);
				else
					qa_opt('avatar_q_list_size',0); // set avatar size to zero so Q2A won't load them
				qa_opt('show_view_counts', (bool)qa_post_text('option_ra_enable_views_lists'));
				qa_opt('show_tags_list', (bool)qa_post_text('option_show_tags_list'));

				// Styling
				qa_opt('styling_duplicate_question', (bool)qa_post_text('option_styling_duplicate_question'));
				qa_opt('styling_solved_question', (bool)qa_post_text('option_styling_solved_question'));
				qa_opt('styling_closed_question', (bool)qa_post_text('option_styling_closed_question'));
				qa_opt('styling_open_question', (bool)qa_post_text('option_styling_open_question'));
				qa_opt('bg_select', qa_post_text('option_bg_select'));
				qa_opt('bg_color', qa_post_text('option_bg_color'));
				qa_opt('text_color', qa_post_text('option_text_color'));
				qa_opt('border_color', qa_post_text('option_border_color'));
				qa_opt('q_link_color', qa_post_text('option_q_link_color'));
				qa_opt('q_link_hover_color', qa_post_text('option_q_link_hover_color'));
				qa_opt('nav_link_color', qa_post_text('option_nav_link_color'));
				qa_opt('nav_link_color_hover', qa_post_text('option_nav_link_color_hover'));
				qa_opt('subnav_link_color', qa_post_text('option_subnav_link_color'));
				qa_opt('subnav_link_color_hover', qa_post_text('option_subnav_link_color_hover'));
				qa_opt('link_color', qa_post_text('option_link_color'));
				qa_opt('link_hover_color', qa_post_text('option_link_hover_color'));
				qa_opt('highlight_color', qa_post_text('option_highlight_color'));
				qa_opt('highlight_bg_color', qa_post_text('option_highlight_bg_color'));
				require_once($this->theme_directory . '/inc/styles.php'); // Generate customized CSS styling				
				//color
				qa_opt('ra_primary_color', qa_post_text('option_ra_primary_color'));	
				qa_opt('ra_nav_bg', qa_post_text('option_ra_nav_bg'));	
				qa_opt('ask_btn_bg', qa_post_text('option_ask_btn_bg'));	
				qa_opt('selected-ans-bg', qa_post_text('option_selected-ans-bg'));	
				qa_opt('hero-bg', qa_post_text('option_hero-bg'));	
				qa_opt('tags-bg', qa_post_text('option_tags-bg'));	
				qa_opt('vote-positive-bg', qa_post_text('option_vote-positive-bg'));	
				qa_opt('vote-negative-bg', qa_post_text('option_vote-negative-bg'));	
				qa_opt('vote-default-bg', qa_post_text('option_vote-default-bg'));	
				qa_opt('post-status-open', qa_post_text('option_post-status-open'));	
				qa_opt('post-status-selected', qa_post_text('option_post-status-selected'));	
				qa_opt('post-status-closed', qa_post_text('option_post-status-closed'));	
				qa_opt('post-status-duplicate', qa_post_text('option_post-status-duplicate'));	
				qa_opt('favourite-btn-bg', qa_post_text('option_favourite-btn-bg'));	
				qa_opt('bottom-bg', qa_post_text('option_bottom-bg'));	
				
				// Typography
				qa_opt('ra_fonts', qa_post_text('option_ra_fonts'));	
				qa_opt('ra_body_font', qa_post_text('option_ra_body_font'));	
				qa_opt('ra_h_font', qa_post_text('option_ra_h_font'));	
				qa_opt('q-list-ff', qa_post_text('option-q-list-ff'));	

				//list
				qa_opt('ra_list_layout', qa_post_text('option_ra_list_layout'));

				
				// Navigation
				qa_opt('ra_nav_fixed', (bool)qa_post_text('option_ra_nav_fixed'));	
				qa_opt('ra_show_icon', (bool)qa_post_text('option_ra_show_icon'));	
				qa_opt('ra_nav_parent_font_size', qa_post_text('option_ra_nav_parent_font_size'));	
				qa_opt('ra_nav_child_font_size', qa_post_text('option_ra_nav_child_font_size'));	
				
				// bootstrap							
				qa_opt('ra_ticker_data', qa_post_text('option_ra_ticker_data'));				

				
				qa_opt('footer_copyright', qa_post_text('option_footer_copyright'));
				
				// Advertisment
				$SocialCount = (int)qa_post_text('social_count'); // number of advertisement items
				$social_links=array();
				$i=0;
				while(($SocialCount>0) and ($i<100)){ // don't create an infinite loop
					if (null !== qa_post_text('social_link_' . $i)){
						$social_links[$i]['social_link'] = qa_post_text('social_link_' . $i);
						$social_links[$i]['social_title'] = qa_post_text('social_title_' . $i);
						$social_links[$i]['social_icon'] = qa_post_text('social_icon_' . $i);
						if ($social_links[$i]['social_icon'] == '1'){
							if(@getimagesize(@$_FILES['ra_social_image_' . $i]['tmp_name']) >0){
								$url		= Q_THEME_URL.'/images/';
								$uploaddir 	= Q_THEME_DIR.'/images/';
								$uploadfile = $uploaddir . basename($_FILES['ra_social_image_' . $i]['name']);
								move_uploaded_file($_FILES['ra_social_image_' . $i]['tmp_name'], $uploadfile);
								$social_links[$i]['social_icon_file'] = $url.$_FILES['ra_social_image_' . $i]['name'];
							}else if(null !== qa_post_text('social_image_url_' . $i)){
								$social_links[$i]['social_icon_file'] =  qa_post_text('social_image_url_' . $i);
							}
						}
						$SocialCount--;
					}
					$i++;
				}
				qa_opt('ra_social_list',json_encode($social_links));
				qa_opt('ra_social_enable', (bool)qa_post_text('option_ra_social_enable'));
				$saved=true;
			}
$saved ? 'Settings saved' : null;

// Load Advertisements
$advs = json_decode( qa_opt('ra_advs') , true);
$i = 0;
$adv_content = '';
if(isset($advs))
	foreach($advs as $k => $adv){
		if (true){ // use list to choose location of advertisement
			$list_options = '';
			for ($count=1; $count <= qa_opt('page_size_qs'); $count++){
					$list_options .= '<option value="' . $count . '"'.(($count==@$adv['adv_location']) ? ' selected' : '').'>' . $count . '</option>';
			}
			$adv_location = '<select id="adv_location_' . $i . '" name="adv_location_' . $i . '" class="qa-form-wide-select">' . $list_options . '</select>';
		}else{
			$adv_location = '<input id="adv_location_' . $i . '" name="adv_location_' . $i . '" class="form-control" value="" placeholder="Position of ads in list" />';
		}
		if (isset($adv['adv_adsense'])){
			$adv_content .= '<tr id="adv_box_' . $i . '">
			<th class="qa-form-tall-label">
				Advertisment #' . ($i+1) . '
				<span class="description">Google Adsense Code</span>
			</th>
			<td class="qa-form-tall-data">
				<input class="form-control" id="adv_adsense_' . $i . '" name="adv_adsense_' . $i . '" type="text" value="' . $adv['adv_adsense'] . '">
				<span class="description">Display After this number of questions</span>
				' . $adv_location .'
				<button advid="' . $i . '" id="advremove" name="advremove" class="qa-form-tall-button pull-right btn" type="submit" onclick="return advremove(this);">Remove This Advertisement</button></td>
			</tr>';
		} else {
			if (!empty($adv['adv_image']))
				$image = '<img src="' . $adv['adv_image'] . '" class="image-preview">';
			else
				$image = '';
			$adv_content .= '<tr id="adv_box_' . $i . '">
			<th class="qa-form-tall-label">
				Advertisement #' . ($i+1) . '
				<span class="description">static advertisement</span>
			</th>
			<td class="qa-form-tall-data">
				<span class="description">upload advertisement image</span>
					<div class="clearfix"></div>
					' . $image . '<input type="file" class="btn btn-success" id="ra_adv_image_' . $i . '" name="ra_adv_image_' . $i . '">
					<span class="description">Image Title</span>
					
					<input class="form-control" type="text" id="adv_image_title_' . $i . '" name="adv_image_title_' . $i . '" value="' . @$adv['adv_image_title'] . '">
					<span class="description">Image link</span>
					
					<input class="form-control" id="adv_image_link_' . $i . '" name="adv_image_link_' . $i . '" type="text" value="' . @$adv['adv_image_link'] . '">
					<span class="description">Display After this number of questions</span>
					
					' . $adv_location .'
					
					<input type="hidden" value="' . @$adv['adv_image'] . '" id="adv_image_url_' .  $i . '" name="adv_image_url_' . $i . '">
					
					<button advid="' . $i . '" id="advremove" name="advremove" class="qa-form-tall-button pull-right btn" type="submit" onclick="return advremove(this);">Remove This Advertisement</button>
			</td>
			</tr>';
		} 
		$i++;
	}
$adv_content .=  '<input type="hidden" value="' . $i . '" id="adv_number" name="adv_number">';
$adv_content .=  '<input type="hidden" value="' . qa_opt('page_size_qs') . '" id="question_list_count" name="question_list_count">';
// Load Advertisements
$i = 0;
$social_content =  '';
$social_fields = json_decode( qa_opt('ra_social_list') , true);
if(isset($social_fields))
	foreach($social_fields as $k => $social_field){
		$list_options = '<option class="icon-wrench" value="1"'.((@$social_field['social_icon']=='1') ? ' selected' : '').'>Upload Social Icon</option>';
		foreach(ra_social_icons() as $icon => $name){
			$list_options .= '<option class="'.$icon.'" value="' . $icon . '"'.(($icon==@$social_field['social_icon']) ? ' selected' : '').'>' . $name . '</option>';
		}
		$social_icon_list = '<select id="social_icon_' . $i . '" name="social_icon_' . $i . '" class="qa-form-wide-select  social-select" sociallistid="' . $i . '">' . $list_options . '</select>';
		if (isset($social_field['social_link'])){
			if ( (!empty($social_field['social_icon_file'])) and (@$social_field['social_icon']=='1') )
				$image = '<img src="' . $social_field['social_icon_file'] . '" class="image-preview">';
			else
				$image = '';
			$social_content .= '<tr id="adv_box_' . $i . '">
			<th class="qa-form-tall-label">
				Social Link #' . ($i+1) . '
				<span class="description">choose Icon and link to your social profile</span>
			</th>
			<td class="qa-form-tall-data">
				<span class="description">Social Profile Link</span>
				<input class="form-control" id="social_link_' . $i . '" name="social_link_' . $i . '" type="text" value="' . $social_field['social_link'] . '">
				<span class="description">Link Title</span>
				<input class="form-control" id="social_title_' . $i . '" name="social_title_' . $i . '" type="text" value="' . $social_field['social_title'] . '">
				<span class="description">Choose Social Icon</span>
				' . $social_icon_list .'
				<div class="social_icon_file_' . $i . '"'.((@$social_field['social_icon']=='1') ? '' : ' style="display:none;"').'>
					<span class="description">upload Social Icon</span>
					<div class="clearfix"></div>
					' . $image . '<input id="ra_social_image_' . $i . '" class="btn btn-success" type="file" name="ra_social_image_' . $i . '">
					<input type="hidden" value="' . @$social_field['social_icon_file'] . '" id="social_image_url_' .  $i . '" name="social_image_url_' . $i . '">
					<button id="social_remove" class="qa-form-tall-button pull-right btn" onclick="return socialremove(this);" type="submit" name="social_remove" socialid="' .  $i . '">Remove This Link</button>
				</div>
			</tr>';
		}
		$i++;
	}
$social_content .=  '<input type="hidden" value="' . $i . '" id="social_count" name="social_count">';
// Background list
// List of Backgrounds
	$p_path = $this->theme_directory . '/images/patterns';
	$bg_images=array();
	$list_options = '';
	$files = scandir($p_path, 1);
	$list_options .= '<option class="icon-wrench" value="bg_default"'.((qa_opt('bg_select')=='bg_default') ? ' selected' : '').'>Default Background</option>';
	$list_options .= '<option class="icon-wrench" value="bg_color"'.((qa_opt('bg_select')=='bg_color') ? ' selected' : '').'>only use Background Color</option>';
	 //@$bg_images[qa_opt('qat_bg_image_index')
	foreach ($files as $file) 
		if (!((empty($file)) or($file=='.') or ($file=='..'))){
			$image = preg_replace("/\\.[^.]*$/", "", $file);
			$bg_images[] = $image;
			$list_options .= '<option value="' . $image . '">' . $image . '</option>';
			}
	$bg_select = '<select id="option_bg_select" name="option_bg_select" class="qa-form-wide-select"'.((qa_opt('bg_select')==$image) ? ' selected' : '').'>' . $list_options . '</select>';

$ra_page = '
<form class="form-horizontal" enctype="multipart/form-data" method="post">
	<div class="qa-part-tabs-nav">
		<ul class="ra-option-tabs nav nav-tabs">
			<li>
				<a href="#" data-toggle=".qa-part-form-tc-general">General</a>
			</li>
			<li>
				<a href="#" data-toggle=".qa-part-form-tc-layout">Layouts</a>
			</li>
			<li>
				<a href="#" data-toggle=".qa-part-form-tc-styling">Styling</a>
			</li>
			<li>
				<a href="#" data-toggle=".qa-part-form-tc-typo">Typography</a>
			</li>
			<li>
				<a href="#" data-toggle=".qa-part-form-tc-social">Social</a>
			</li>
			<li>
				<a href="#" data-toggle=".qa-part-form-tc-ads">Ads</a>
			</li>
			<li>
				<a href="#" data-toggle=".qa-part-form-tc-footer">Footer</a>
			</li>
			<li>
				<a href="#" data-toggle=".qa-part-form-tc-widget">Widget</a>
			</li>
		</ul>
	</div>
	<div class="qa-part-form-tc-general">
		<h3>General Settings</h3>
		<table class="qa-form-tall-table options-table">
			<tbody>
			<tr>
				<th class="qa-form-tall-label">
					Logo
					<span class="description">Upload your own logo.</span>
				</th>
				<td class="qa-form-tall-data">
					<span class="qa-form-tall-static"><img src="'.qa_opt('ra_logo').'" class="image-preview"><input type="file" class="btn btn-success" id="ra_logo_field" name="ra_logo_field"></span>
				</td>
			</tr>
			<tr>
				<th class="qa-form-tall-label">
					Favicon
					<span class="description">favicon image (32px32px).</span>
				</th>
				<td class="qa-form-tall-data">
					<span class="qa-form-tall-static"><img src="'.qa_opt('ra_favicon').'" class="image-preview"><input type="file" class="btn btn-success" id="ra_favicon_field" name="ra_favicon_field"></span>
				</td>
			</tr>
			</tbody><tbody id="google_analytics">
				<tr>
					<th class="qa-form-tall-label">
						Analytics tracking
						<span class="description">Paste your Google Analytics or other tracking code. This will be loaded in the footer.</span>
					</th>
					<td class="qa-form-tall-data">
						<textarea class="form-control" cols="40" rows="3" name="option_google_analytics">' . qa_opt('google_analytics') . '</textarea>
					</td>
				</tr>
			</tbody>
			<tbody>
				<tr>
					<th class="qa-form-tall-label">
						Collapsible comments
						<span class="description">ADD DETAIL.</span>
					</th>
					<td class="qa-form-tall-label">
						<div class="on-off-checkbox-container">
								<input type="checkbox" class="on-off-checkbox" value="1"' . (qa_opt('ra_colla_comm') ? ' checked=""' : '') . ' id="on-off-checkbox" name="option_ra_colla_comm">
							<label for="on-off-checkbox">
							</label>
						</div>
					</td>
				</tr>
			</tbody><tbody id="show_real_name">
				<tr>
					<th class="qa-form-tall-label">
						Show Real name
						<span class="description">ADD DETAIL.</span>
					</th>
					<td class="qa-form-tall-label">
						<div class="on-off-checkbox-container">
								<input type="checkbox" class="on-off-checkbox" value="1"' . (qa_opt('show_real_name') ? ' checked=""' : '') . ' id="option_show_real_name" name="option_show_real_name">
							<label for="option_show_real_name">
							</label>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="qa-part-form-tc-layout">
		<h3>Layout Settings</h3>
		<table class="qa-form-tall-table options-table">
			<tbody>
				<tr>
					<th class="qa-form-tall-label">
						Site Layout
						<span class="description">Select between wide or boxed site layout</span>
					</th>
					<td class="qa-form-tall-label">
						<input class="theme-option-radio" type="radio"' . (qa_opt('theme_layout')=='wide' ? ' checked=""' : '') . ' id="option_theme_layout_wide" name="option_theme_layout" value="wide">
						   <label for="option_theme_layout_wide">Wide</label>
						<input class="theme-option-radio" type="radio"' . (qa_opt('theme_layout')=='boxed' ? ' checked=""' : '') . ' id="option_theme_layout_boxed" name="option_theme_layout" value="boxed">
						   <label for="option_theme_layout_boxed">boxed</label> 
					</td>
				</tr>
			</tbody>
			<tbody>
				<tr>
					<th class="qa-form-tall-label">
						User list in table
						<span class="description">ADD DETAIL.</span>
					</th>
					<td class="qa-form-tall-label">
						<div class="on-off-checkbox-container">
								<input type="checkbox" class="on-off-checkbox" value="1"' . (qa_opt('users_table_layout') ? ' checked=""' : '') . ' id="option_users_table_layout" name="option_users_table_layout">
							<label for="option_users_table_layout">
							</label>
						</div>
					</td>
				</tr>
			</tbody>
			<tbody>
				<tr>
					<th class="qa-form-tall-label">
						Fixed Navigation
						<span class="description">ADD DETAIL.</span>
					</th>
					<td class="qa-form-tall-label">
						<div class="on-off-checkbox-container">
								<input type="checkbox" class="on-off-checkbox" value="1"' . (qa_opt('ra_nav_fixed') ? ' checked=""' : '') . ' id="option_ra_nav_fixed" name="option_ra_nav_fixed">
								<label for="option_ra_nav_fixed"></label>
						</div>
					</td>
				</tr>
				<tr>
					<th class="qa-form-tall-label">
						Show menu Icon
						<span class="description">ADD DETAIL.</span>
					</th>
					<td class="qa-form-tall-label">
						<div class="on-off-checkbox-container">
								<input type="checkbox" class="on-off-checkbox" value="1"' . (qa_opt('ra_show_icon') ? ' checked=""' : '') . ' id="option_ra_show_icon" name="option_ra_show_icon">
								<label for="option_ra_show_icon"></label>
						</div>
					</td>
				</tr>
			</tbody>
			<tbody>
				<tr><td><h3>Question Lists</h3></td></tr>
				<tr>
					<th class="qa-form-tall-label">
						Question Excerpt
						<span class="description">Toggle question description in question lists.</span>
					</th>
					<td class="qa-form-tall-label">
						<div class="on-off-checkbox-container">
								<input type="checkbox" class="on-off-checkbox" value="1"' . (qa_opt('ra_enable_except') ? ' checked=""' : '') . ' id="option_ra_enable_except" name="option_ra_enable_except">
								<label for="option_ra_enable_except"></label>
						</div>
					</td>
				</tr>
				<tr id="ra_except_length">
					<th class="qa-form-tall-label">
						Excerpt Length
						<span class="description">Length of questions description in question lists</span>
					</th>
					<td class="qa-form-tall-label">
						<input class="qa-form-wide-number" type="text" value="' . qa_opt('ra_except_len') . '"  id="option_ra_except_len" name="option_ra_except_len">
					</td>
				</tr>
				<tr>
					<th class="qa-form-tall-label">
						Avatars in lists
						<span class="description">Toggle avatars in question lists.</span>
					</th>
					<td class="qa-form-tall-label">
						<div class="on-off-checkbox-container">
								<input type="checkbox" class="on-off-checkbox" value="1"' . (qa_opt('ra_enable_avatar_lists') ? ' checked=""' : '') . ' id="option_ra_enable_avatar_lists" name="option_ra_enable_avatar_lists">
								<label for="option_ra_enable_avatar_lists"></label>
						</div>
					</td>
				</tr>
				<tr>
					<th class="qa-form-tall-label">
						View Count
						<span class="description">Toggle View Count in question lists.</span>
					</th>
					<td class="qa-form-tall-label">
						<div class="on-off-checkbox-container">
								<input type="checkbox" class="on-off-checkbox" value="1"' . (qa_opt('show_view_counts') ? ' checked=""' : '') . ' id="option_ra_enable_views_lists" name="option_ra_enable_views_lists">
								<label for="option_ra_enable_views_lists"></label>
						</div>
					</td>
				</tr>
				<tr>
					<th class="qa-form-tall-label">
						Question Tags
						<span class="description">Toggle Tags in question lists.</span>
					</th>
					<td class="qa-form-tall-label">
						<div class="on-off-checkbox-container">
								<input type="checkbox" class="on-off-checkbox" value="1"' . (qa_opt('show_tags_list') ? ' checked=""' : '') . ' id="option_show_tags_list" name="option_show_tags_list">
								<label for="option_show_tags_list"></label>
						</div>
					</td>
				</tr>
			</tbody>
			<tbody>
				<tr>
					<th class="qa-form-tall-label">
						Horizontal Voting Buttons
						<span class="description">Switch between horizontal and vertical voting buttons</span>
					</th>
					<td class="qa-form-tall-label">
						<div class="on-off-checkbox-container">
								<input type="checkbox" class="on-off-checkbox" value="1"' . (qa_opt('horizontal_voting_btns') ? ' checked=""' : '') . ' id="option_horizontal_voting_btns" name="option_horizontal_voting_btns">
							<label for="option_horizontal_voting_btns">
							</label>
						</div>
					</td>
				</tr>
			</tbody>
			<tbody>
				<tr>
					<th class="qa-form-tall-label">
						Back to Top Button
						<span class="description">Enable Back to Top</span>
					</th>
					<td class="qa-form-tall-label">
						<div class="on-off-checkbox-container">
								<input type="checkbox" class="on-off-checkbox" value="1"' . (qa_opt('enble_back_to_top') ? ' checked=""' : '') . ' id="option_enble_back_to_top" name="option_enble_back_to_top">
							<label for="option_enble_back_to_top">
							</label>
						</div>
					</td>
					</tr>
					<tr id="back_to_top_location_container" ' . (qa_opt('enble_back_to_top') ? '' : ' style="display:none;"') . '>
					<th class="qa-form-tall-label">
						Back To Top\'s Position
						<span class="description">Back To Top button\'s Position</span>
					</th>
					<td class="qa-form-tall-label">
						<input class="theme-option-radio" type="radio"' . (qa_opt('back_to_top_location')=='nav' ? ' checked=""' : '') . ' id="option_back_to_top_nav" name="option_back_to_top_location" value="nav">
						   <label for="option_back_to_top_nav">Under Navigation</label>
						<input class="theme-option-radio" type="radio"' . (qa_opt('back_to_top_location')=='right' ? ' checked=""' : '') . ' id="option_back_to_top_right" name="option_back_to_top_location" value="right">
						   <label for="option_back_to_top_right">Bottom Right</label> 
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="qa-part-form-tc-styling">
		<h3>Colors</h3>
		<table class="qa-form-tall-table options-table">
			<tbody>
				<tr>
					<th class="qa-form-tall-label">
						Body background
					</th>
					<td class="qa-form-tall-label">
						' . $bg_select . '
					</td>
				</tr>
				<tr id="bg-color-container"'. ((qa_opt('bg_select')=='bg_color') ? '' : ' style="display:none;"') . '>
					<th class="qa-form-tall-label">
						Body Font Color
					</th>
					<td class="qa-form-tall-label">
						<input type="color" class="form-control" value="' . qa_opt('bg_color') . '" id="option_bg_color" name="option_bg_color">
					</td>
				</tr>
			</tbody>
			<tbody>
				<tr>
					<th class="qa-form-tall-label">
						Text color
					</th>
					<td class="qa-form-tall-label">
						<input type="color" class="form-control" value="' . qa_opt('text_color') . '" id="option_text_color" name="option_text_color">
					</td>
				</tr>
				<tr>
					<th class="qa-form-tall-label">
						Border color
					</th>
					<td class="qa-form-tall-label">
						<input type="color" class="form-control" value="' . qa_opt('border_color') . '" id="option_border_color" name="option_border_color">
					</td>
				</tr>
				<tr>
					<th class="qa-form-tall-label">
						Link color
					</th>
					<td class="qa-form-tall-label">
						Link Color<input type="color" class="form-control" value="' . qa_opt('link_color') . '" id="option_link_color" name="option_link_color">
						Hover Color<input type="color" class="form-control" value="' . qa_opt('link_hover_color') . '" id="option_link_hover_color" name="option_link_hover_color">
					</td>
				</tr>
				<tr>
					<th class="qa-form-tall-label">
						Question Link color
					</th>
					<td class="qa-form-tall-label">
						Link Color<input type="color" class="form-control" value="' . qa_opt('q_link_color') . '" id="option_q_link_color" name="option_q_link_color">
						Hover Color<input type="color" class="form-control" value="' . qa_opt('q_link_hover_color') . '" id="option_q_link_hover_color" name="option_q_link_hover_color">
					</td>
				</tr>
				<tr>
					<th class="qa-form-tall-label">
						Navigation Link color
					</th>
					<td class="qa-form-tall-label">
						Text Color<input type="color" class="form-control" value="' . qa_opt('nav_link_color') . '" id="option_nav_link_color" name="option_nav_link_color">
						Hover Color<input type="color" class="form-control" value="' . qa_opt('nav_link_color_hover') . '" id="option_nav_link_color_hover" name="option_nav_link_color_hover">
					</td>
				</tr>
				<tr>
					<th class="qa-form-tall-label">
						Sub Navigation Link color
					</th>
					<td class="qa-form-tall-label">
						Text Color<input type="color" class="form-control" value="' . qa_opt('subnav_link_color') . '" id="option_subnav_link_color" name="option_subnav_link_color">
						Hover Color<input type="color" class="form-control" value="' . qa_opt('subnav_link_color_hover') . '" id="option_subnav_link_color_hover" name="option_subnav_link_color_hover">
					</td>
				</tr>
				<tr>
					<th class="qa-form-tall-label">
						Highlight Text color
					</th>
					<td class="qa-form-tall-label">
						<input type="color" class="form-control" value="' . qa_opt('highlight_color') . '" id="option_highlight_color" name="option_highlight_color">
					</td>
				</tr>
				<tr>
					<th class="qa-form-tall-label">
						Highlight background color
					</th>
					<td class="qa-form-tall-label">
						<input type="color" class="form-control" value="' . qa_opt('highlight_bg_color') . '" id="option_highlight_bg_color" name="option_highlight_bg_color">
					</td>
				</tr>
			</tbody>
		</table>
		<h3>Background color of questions</h3>
		<table class="qa-form-tall-table options-table">
			<tbody>
				<tr>
					<th class="qa-form-tall-label">
						Open Questions
						<span class="description">Color Open Questions in question lists</span>
					</th>
					<td class="qa-form-tall-label">
						<div class="on-off-checkbox-container">
								<input type="checkbox" class="on-off-checkbox" value="1"' . (qa_opt('styling_open_question') ? ' checked=""' : '') . ' id="option_styling_open_question" name="option_styling_open_question">
							<label for="option_styling_open_question">
							</label>
						</div>
					</td>
				</tr>
				<tr>
					<th class="qa-form-tall-label">
						Closed Questions
						<span class="description">Color Closed Questions in question lists</span>
					</th>
					<td class="qa-form-tall-label">
						<div class="on-off-checkbox-container">
								<input type="checkbox" class="on-off-checkbox" value="1"' . (qa_opt('styling_closed_question') ? ' checked=""' : '') . ' id="option_styling_closed_question" name="option_styling_closed_question">
							<label for="option_styling_closed_question">
							</label>
						</div>
					</td>
				</tr>
				<tr>
					<th class="qa-form-tall-label">
						Resolved Questions
						<span class="description">Color Resolved Questions in question lists</span>
					</th>
					<td class="qa-form-tall-label">
						<div class="on-off-checkbox-container">
								<input type="checkbox" class="on-off-checkbox" value="1"' . (qa_opt('styling_solved_question') ? ' checked=""' : '') . ' id="option_styling_solved_question" name="option_styling_solved_question">
							<label for="option_styling_solved_question">
							</label>
						</div>
					</td>
				</tr>
				<tr>
					<th class="qa-form-tall-label">
						Resolved Questions
						<span class="description">Color Duplicate Questions in question lists</span>
					</th>
					<td class="qa-form-tall-label">
						<div class="on-off-checkbox-container">
								<input type="checkbox" class="on-off-checkbox" value="1"' . (qa_opt('styling_duplicate_question') ? ' checked=""' : '') . ' id="option_styling_duplicate_question" name="option_styling_duplicate_question">
							<label for="option_styling_duplicate_question">
							</label>
						</div>
					</td>
				</tr>
				</tbody>
				<tbody>
				<tr>
					<th class="qa-form-tall-label">
						Ask button background
						<span class="description">ADD DETAIL.</span>
					</th>
					<td class="qa-form-tall-label">
						<input type="text" class="form-control" value="' . qa_opt('ask_btn_bg') . '" id="option_ask_btn_bg" name="option_ask_btn_bg">
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="qa-part-form-tc-typo">
		<table class="qa-form-tall-table options-table">
			<tbody><tr>
				<th class="qa-form-tall-data">
					<input type="text" class="form-control" value="">
				</th>
			</tr>
			<tr>
				<th class="qa-form-tall-label">
					Typo
				</th>
			</tr>
			<tr>
				<th class="qa-form-tall-data">
					<input type="text" class="form-control" value="" typo="">
				</th>
			</tr>
			<tr>
				<th class="qa-form-tall-label">
					Google fonts
				</th>
			</tr>
			<tr>
				<th class="qa-form-tall-data">
					<textarea class="form-control" cols="40" rows="5" name="option_ra_fonts">' . qa_opt('ra_fonts') . '</textarea>
				</th>
			</tr>
			<tr>
				<th class="qa-form-tall-label">
					Body font family
				</th>
			</tr>
			<tr>
				<th class="qa-form-tall-data">
					<input type="text" class="form-control" value="' . qa_opt('ra_body_font') . '" name="option_ra_body_font">
				</th>
			</tr>
			</tbody><tbody id="ra_h_font">
				<tr>
					<th class="qa-form-tall-label">
						Heading font family
					</th>
				</tr>
				<tr>
					<th class="qa-form-tall-data">
						<input type="text" class="form-control" value="' . qa_opt('ra_h_font') . '" name="option_ra_h_font">
					</th>
				</tr>
			</tbody>
			<tbody id="q-list-ff">
				<tr>
					<th class="qa-form-tall-label">
						List font family
					</th>
				</tr>
				<tr>
					<th class="qa-form-tall-data">
						<input type="text" class="form-control" value="' . qa_opt('q-list-ff') . '" name="option-q-list-ff">
					</th>
				</tr>
			</tbody>
			<tbody><tr>
				<th class="qa-form-tall-data">
					<input type="text" class="form-control" value="">
				</th>
			</tr>
			<tr>
				<th class="qa-form-tall-label">
					Colors
				</th>
			</tr>
			<tr>
				<th class="qa-form-tall-data">
					<input type="text" class="form-control" value="" colors="">
				</th>
			</tr>
			</tbody><tbody id="ra_nav_bg">
				<tr>
					<th class="qa-form-tall-label">
						Nav Background
					</th>
				</tr>
				<tr>
					<th class="qa-form-tall-data">
						<input type="text" class="form-control" value="' . qa_opt('ra_nav_bg') . '" name="option_ra_nav_bg">
					</th>
				</tr>
			</tbody>
			<tbody id="ra_primary_color">
				<tr>
					<th class="qa-form-tall-label">
						Parimary color
					</th>
				</tr>
				<tr>
					<th class="qa-form-tall-data">
						<input type="text" class="form-control" value="' . qa_opt('ra_primary_color') . '" name="option_ra_primary_color">
					</th>
				</tr>
			</tbody>
			<tbody id="hero-bg">
				<tr>
					<th class="qa-form-tall-label">
						Hero background
					</th>
				</tr>
				<tr>
					<th class="qa-form-tall-data">
						<input type="text" class="form-control" value="' . qa_opt('hero-bg') . '" name="option_hero-bg">
					</th>
				</tr>
			</tbody>
			<tbody id="vote-positive-bg">
				<tr>
					<th class="qa-form-tall-label">
						Positive vote bg
					</th>
				</tr>
				<tr>
					<th class="qa-form-tall-data">
						<input type="text" class="form-control" value="' . qa_opt('vote-positive-bg') . '" name="option_vote-positive-bg">
					</th>
				</tr>
			</tbody>
			<tbody id="vote-negative-bg">
				<tr>
					<th class="qa-form-tall-label">
						Negative vote bg
					</th>
				</tr>
				<tr>
					<th class="qa-form-tall-data">
						<input type="text" class="form-control" value="' . qa_opt('vote-negative-bg') . '" name="option_vote-negative-bg">
					</th>
				</tr>
			</tbody>
			<tbody id="vote-default-bg">
				<tr>
					<th class="qa-form-tall-label">
						Default vote bg
					</th>
				</tr>
				<tr>
					<th class="qa-form-tall-data">
						<input type="text" class="form-control" value="' . qa_opt('vote-default-bg') . '" name="option_vote-default-bg">
					</th>
				</tr>
			</tbody>
			<tbody id="bottom-bg">
				<tr>
					<th class="qa-form-tall-label">
						Bottom Bg
					</th>
				</tr>
				<tr>
					<th class="qa-form-tall-data">
						<input type="text" class="form-control" value="' . qa_opt('bottom-bg') . '" name="option_bottom-bg">
					</th>
				</tr>
			</tbody>
			<tbody><tr>
				<th class="qa-form-tall-data">
					<input type="text" class="form-control" value="">
				</th>
			</tr>
			<tr>
				<th class="qa-form-tall-label">
					List
				</th>
			</tr>
			<tr>
				<th class="qa-form-tall-data">
					<input type="text" class="form-control" value="" list="">
				</th>
			</tr>
			</tbody>
			<tbody><tr>
				<th class="qa-form-tall-data">
					<input type="text" class="form-control" value="">
				</th>
			</tr>
			<tr>
				<th class="qa-form-tall-label">
					Navigation
				</th>
			</tr>
			<tr>
				<th class="qa-form-tall-data">
					<input type="text" class="form-control" value="" navigation="">
				</th>
			</tr>
			<tr>
				<th class="qa-form-tall-label">
					Nav parent font size
					&nbsp;
					<input type="text" class="qa-form-tall-number" value="' . qa_opt('ra_nav_parent_font_size') . '" id="option_ra_nav_parent_font_size" name="option_ra_nav_parent_font_size">
				</th>
			</tr>
			<tr>
				<th class="qa-form-tall-label">
					Nav child font size
					&nbsp;
					<input type="text" class="qa-form-tall-number" value="' . qa_opt('ra_nav_child_font_size') . '" id="option_ra_nav_child_font_size" name="option_ra_nav_child_font_size">
				</th>
			</tr>
			<tr>
				<th class="qa-form-tall-data">
					<input type="text" class="form-control" value="">
				</th>
			</tr>
			<tr>
				<th class="qa-form-tall-label">
					Bootstrap
				</th>
			</tr>
			<tr>
				<th class="qa-form-tall-data">
					<input type="text" class="form-control" value="" bootstrap="">
				</th>
			</tr>
			</tbody><tbody id="ra_body_bg">
				<tr>
					<th class="qa-form-tall-label">
						Body Background
					</th>
				</tr>
				<tr>
					<th class="qa-form-tall-data">
						<input type="text" class="form-control" value="' . qa_opt('ra_body_bg') . '" name="option_ra_body_bg">
					</th>
				</tr>
			</tbody>
			<tbody id="font-size-base">
				<tr>
					<th class="qa-form-tall-label">
						Base font size
					</th>
				</tr>
				<tr>
					<th class="qa-form-tall-data">
						<input type="text" class="form-control" value="' . qa_opt('font-size-base') . '" name="option-font-size-base">
					</th>
				</tr>
			</tbody>
			<tbody id="ra_base_fontfamily">
				<tr>
					<th class="qa-form-tall-label">
						Base font family
					</th>
				</tr>
				<tr>
					<th class="qa-form-tall-data">
						<input type="text" class="form-control" value="' . qa_opt('ra_base_fontfamily') . '" name="option_ra_base_fontfamily">
					</th>
				</tr>
			</tbody>
			<tbody id="ra_base_lineheight">
				<tr>
					<th class="qa-form-tall-label">
						Base line height
					</th>
				</tr>
				<tr>
					<th class="qa-form-tall-data">
						<input type="text" class="form-control" value="' . qa_opt('ra_base_lineheight') . '" name="option_ra_base_lineheight">
					</th>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="qa-part-form-tc-social">
		<table class="qa-form-tall-table options-table">
			<tbody>
				<tr>
					<th class="qa-form-tall-label">
						Social Toolbar
						<span class="description">Enable social links in your site\'s header.</span>
					</th>
					<td class="qa-form-tall-label">
						<div class="on-off-checkbox-container">
							<input type="checkbox" class="on-off-checkbox" value="1"' . (qa_opt('ra_social_enable') ? ' checked=""' : '') . ' id="option_ra_social_enable" name="option_ra_social_enable">
							<label for="option_ra_social_enable"></label>
						</div>
					</td>
				</tr>
			</tbody>
			<tbody>
				<tr>
					<th class="qa-form-tall-label">
						Add New Social Links
						<span class="description">Add a new social link</span>
					</th>
					<td class="qa-form-tall-label text-center">
						<button type="submit" id="add_social" name="add_social" class="qa-form-tall-button btn">Add Social Links</button>
					</td>
				</tr>
			</tbody>
			<tbody id="social_container">
				' . $social_content . '	
			</tbody>
		</table>
	</div>
	<div class="qa-part-form-tc-ads">
		<h3>Advertisment in question list</h3>
		<table class="qa-form-tall-table options-table">
			<tbody>
				<tr>
					<th class="qa-form-tall-label">
						Advertisement in Lists
						<span class="description">Enable Advertisement in question lists</span>
					</th>
					<td class="qa-form-tall-label">
						<div class="on-off-checkbox-container">
							<input type="checkbox" class="on-off-checkbox" value="1"' . (qa_opt('enable_adv_list') ? ' checked=""' : '') . ' id="option_enable_adv_list" name="option_enable_adv_list">
							<label for="option_enable_adv_list"></label>
						</div>
					</td>
				</tr>
			</tbody>
			<tbody id="ads_container" ' . (qa_opt('enable_adv_list') ? '' : ' style="display:none;"') . '>
				<tr>
					<th class="qa-form-tall-label">
						Add Advertisement
						<span class="description">Create advertisement with static or Google Adsense</span>
					</th>
					<td class="qa-form-tall-label text-center">
						<button type="submit" id="add_adv" name="add_adv" class="qa-form-tall-button btn">Add Advertisement</button>
						<button type="submit" id="add_adsense" name="add_adsense" class="qa-form-tall-button btn">Add Google Adsense</button>
					</td>
				</tr>
			' . $adv_content . '
			</tbody>
			
		</table>
		<h3>Advertisement in question page</h3>
		<table class="qa-form-tall-table options-table">
			<tbody><tr>
				<th class="qa-form-tall-label">
					Under question title
					<span class="description">Advertisement below Question Title</span>
				</th>
				<td class="qa-form-tall-label">
					<textarea class="form-control" cols="40" rows="5" name="option_ads_below_question_title">' . base64_decode( qa_opt('ads_below_question_title') ) .'</textarea>
				</td>
			</tr>
			<tr>
				<th class="qa-form-tall-label">
					After question content
					<span class="description">this advertisement will show up between Question & Answer</span>
				</th>
				<td class="qa-form-tall-label">
					<textarea class="form-control" cols="40" rows="5" name="option_ads_after_question_content">' . base64_decode( qa_opt('ads_after_question_content') ) .'</textarea>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
	<div class="qa-part-form-tc-footer">
	<table class="qa-form-tall-table options-table">
		<tbody>
			<tr>
				<th class="qa-form-tall-label">
					Text at right side of footer
					<span class="description">you can add links or images by entering html code</span>
				</th>
				<td class="qa-form-tall-label">
					<input id="option_footer_copyright" class="form-control" type="text" name="option_footer_copyright" value="' . qa_opt('footer_copyright') . '">
				</td>
			</tr>
		</tbody>
	</table>
	</div>	
	<div class="qa-part-form-tc-widget">
	<table class="qa-form-tall-table options-table">
		<tbody>
			<tr>
				<th class="qa-form-tall-label">
					Ticker Data from
					<span class="description">Select from where you want to get data</span>
				</th>
				<td class="qa-form-tall-label">
					<select id="option_ra_ticker_data" class="form-control" name="option_ra_ticker_data">
						<option value="tags" ' . (qa_opt('ra_ticker_data') =='tags' ? 'selected':'' ) . '>Tags</option>
						<option value="categories" ' . (qa_opt('ra_ticker_data') =='categories' ? 'selected':'' ) . '>Categories</option>
					</select>
				</td>
			</tr>
		</tbody>
	</table>
	</div>
<div class="form-button-sticky-footer">
	<div class="form-button-holder">
		<input type="submit" class="qa-form-tall-button btn-primary" title="" value="Save Changes" name="ra_save_button">
		<input type="submit" class="qa-form-tall-button" title="" value="Reset to Default" name="ra_reset_button">
	</div>
</div>
</form>
';
			$this->content['custom'] = $ra_page;
		}
		qa_html_theme_base::doctype();
	}	
	
		function main()
		{
			if($this->request == 'themeoptions') {
				$content=$this->content;
				$this->output('<div class="qa-main theme-options clearfix"><div class="col-sm-12">');
				$this->output(
					'<h1 class="page-title">',
					$this->content['title'],
					'</h1>'
				);
				$this->main_parts($content);
				$this->output('</div></div> <!-- END qa-main -->', '');
			}else
				qa_html_theme_base::main();
		}
		function main_part($key, $part)
		{
			if( ($this->request == 'themeoptions') && ($key == 'custom') ){
				$this->output_raw($part);
			}else
				qa_html_theme_base::main_part($key, $part);
		}
		function form_field($field, $style)
		{			
			
			if (@$field['type'] == 'ra_qaads_multi_text'){
				$this->form_prefix($field, $style);
				$this->ra_qaads_form_multi_text($field, $style);
				$this->form_suffix($field, $style);
			
			}else{
				qa_html_theme_base::form_field($field, $style); // call back through to the default function
			}			
		}
		
		function ra_qaads_form_multi_text($field, $style)
		{
			$this->output('<div class="ra-multitext"><div class="ra-multitext-append">');
			
			$i = 0;

			if((strlen($field['value'])!=0) && is_array(unserialize($field['value']))){
				$links = unserialize($field['value']);
				foreach($links as $k => $ads){
					
					$this->output('<div class="ra-multitext-list" data-id="'.$field['id'].'">');
					$this->output('<input name="'.$field['id'].'['.$k.'][name]" type="text" value="'.$ads['name'].'" class="ra-input name" placeholder="'.$field['input_label'].'" />');

					$this->output('<textarea name="'.$field['id'].'['.$k.'][code]" class="ra-input code"  placeholder="Your advertisement code.." />'.str_replace('\\', '',base64_decode($ads['code'])).'</textarea>');
					
					$this->output('<span class="ra-multitext-delete icon-trashcan btn btn-danger btn-xs">Remove</span>');
					$this->output('</div>');
				}
			}else{
				$this->output('<div class="ra-multitext-list" data-id="'.$field['id'].'">');
				$this->output('<input name="'.$field['id'].'[0][name]" type="text"  class="ra-input name" placeholder="'.$field['input_label'].'" />');
				$this->output('<textarea name="'.$field['id'].'[0][code]" class="ra-input code" placeholder="Your advertisement code.."></textarea>');
				
				$this->output('<span class="ra-multitext-delete icon-trashcan btn btn-danger btn-xs">Remove</span>');
				
				$this->output('</div>');
			}
			
			
			$this->output('</div></div>');
			$this->output('<span class="ra-multitext-add icon-plus btn btn-primary btn-xs" title="Add more">Add more</span>');
		}
		function ra_font_name($font_url){
			$patterns = array(
			  //replace the path root
			'!^http://fonts.googleapis.com/css\?!',
			  //capture the family and avoid and any following attributes in the URI.
			'!(family=[^&:]+).*$!',
			  //delete the variable name
			'!family=!',
			  //replace the plus sign
			'!\+!');
			$replacements = array(
			"",
			'$1',
			'',
			' ');
			
			$font = preg_replace($patterns,$replacements,$font_url);
			return $font;

		}
	
		function ra_font_family(){
			$fonts = array();
			$fonts[] = '';
			$fonts['Georgia, Times New Roman, Times, serif'] = 'Serif Family';
			$fonts['Helvetica Neue, Helvetica, Arial, sans-serif'] = 'Sans Family';	
			$option_fonts = qa_opt('ra_fonts');
			if(!empty($option_fonts)){
				foreach (explode("\n", qa_opt('ra_fonts')) as $font){				
					$fonts[$this->ra_font_name($font).', Helvetica, Arial, sans-serif'] = $this->ra_font_name($font);
				}
			}
			return $fonts;
		}
		function head_script()
		{
			qa_html_theme_base::head_script();
			if($this->request == 'themeoptions'){
				$this->output('<script type="text/javascript" src="'.$this->rooturl.'/js/admin.js"></script>');
				$this->output('<script type="text/javascript" src="'.$this->rooturl.'/js/spectrum.js"></script>'); // color picker
			}
		}
		function head_css()
		{
			if($this->request == 'themeoptions'){
				$this->output('<link rel="stylesheet" type="text/css" href="'.$this->rooturl.'css/admin.css"/>');
				$this->output('<link rel="stylesheet" type="text/css" href="'.$this->rooturl.'css/spectrum.css"/>'); // color picker
			}
			qa_html_theme_base::head_css();
		}
		
		function q_list_items($q_items){
            if (qa_opt('enable_adv_list')) {
				$advs = json_decode( qa_opt('ra_advs') , true);
				foreach($advs as $k => $adv){
					$advertisments[@$adv['adv_location']][]=$adv;
				}
				$i=0;
				foreach ($q_items as $q_item){
					$this->q_list_item($q_item);
					if (isset($advertisments[$i])){
						foreach ($advertisments[$i] as $k=>$adv){
							$this->output('<div class="qm-advertisement">');
							if (isset($adv['adv_adsense']))
								$this->output($adv['adv_adsense']);
							else{
								if (isset($adv['adv_image']))
									$this->output('<a href="'. $adv['adv_image_link'] . '"><img src="'.$adv['adv_image'].'" title="'.$adv['adv_image_title'].'" alt="advert" /></a>');
								else
									$this->output('<a href="'. $adv['adv_image_link'] . '">'.$adv['adv_image_title'].'</a>');
							}
							$this->output('</div>');
						}
					}
					$i++;
				}
			}else
				qa_html_theme_base::q_list_items($q_items);
		}

}


/*
	Omit PHP closing tag to help avoid accidental output
*/