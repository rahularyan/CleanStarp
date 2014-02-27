<?php
/* don't allow this page to be requested directly from browser */	
if (!defined('QA_VERSION')) {
		header('Location: /');
		exit;
}

if(!(bool)qa_opt('cs_custom_style_created')){
	// Background customizations
	$p_url = $this->theme_url . '/images/patterns/';
	$css = '';
	// Body
	$bg_image = qa_opt('cs_bg_select');
	if ($bg_image=='bg_default')
		$bg='';
	elseif ($bg_image=='bg_color')
		$bg='background: none repeat scroll 0 0 ' . qa_opt('cs_bg_color') . ' !important;';
	else 
		$bg='background: url("' . $p_url . $bg_image . '.png") !important;';
	$text_color = qa_opt('cs_text_color');
	if (!(empty($text_color)))
		$bg.= 'color:' . $text_color . ';';
	if (!(empty($bg))) {
		$css .= 'body {' . $bg . '}';
		$css .= '.left-sidebar {background-image: url("' . $p_url . $bg_image . '.png") !important;}';
	}


	// links color
	$color = qa_opt('cs_link_color');
	if (!(empty($color)))
		$css.= 'a{color:' . $color . ';}';
	$color = qa_opt('cs_link_hover_color');
	if (!(empty($color)))
		$css.= 'a:hover{color:' . $color . ';}';

	// navigation color
	$color = qa_opt('cs_nav_link_color');
	if (!(empty($color)))
		$css.= '.qa-nav-main-link, .qa-nav-main-item .qa-nav-main-link.qa-nav-main-selected, .left-sidebar .qa-nav-main-list .qa-nav-main-item .qa-nav-main-link{color:' . $color . ';}';
	$color = qa_opt('cs_nav_link_color_hover');
	if (!(empty($color)))
		$css.= '.qa-nav-main-link:hover, .qa-nav-main-item:hover .qa-nav-main-link.qa-nav-main-selected, .left-sidebar .qa-nav-main-list .qa-nav-main-item .qa-nav-main-link:hover{color:' . $color . ';}';
		
	// sub navigation color
	$color = qa_opt('cs_subnav_link_color');
	if (!(empty($color)))
		$css.= '.qa-nav-sub-link{color:' . $color . ';}';
	$color = qa_opt('cs_subnav_link_color_hover');
	if (!(empty($color)))
		$css.= '.qa-nav-sub-link:hover{color:' . $color . ';}';

	// question color
	$color = qa_opt('cs_q_link_color');
	if (!(empty($color)))
		$css.= '.qa-q-item-title > a{color:' . $color . ';}';
	$color = qa_opt('cs_q_link_hover_color');
	if (!(empty($color)))
		$css.= '.qa-q-item-title > a:hover{color:' . $color . ';}';
		
	// Selection Highlight color
	$color = qa_opt('cs_highlight_color');
	if (!(empty($color)))
		$css.= '::selection {color: ' . $color . ';} ::-moz-selection {color: ' . $color . ';}';
	$color = qa_opt('cs_highlight_bg_color');
	if (!(empty($color)))
		$css.= '::selection {background: ' . $color . ';} ::-moz-selection {background: ' . $color . ';}';

	// Typography
	$typo_elements= array('body', 'h1', 'h2', 'h3', 'h4', 'h5', 'p', 'span', 'quote');
	foreach ($typo_elements as $elem){
		$family = qa_opt('typo_options_family_' . $elem);
		$backup = qa_opt('typo_options_backup_' . $elem);
		$style = qa_opt('typo_options_style_' . $elem);
		$size = qa_opt('typo_options_size_' . $elem );
		$height = qa_opt('typo_options_linehight_' . $elem);
		
		if(($family == '') || ($backup=='')){$connector = '';}else{$connector = ', ';}
		$font_family = $family . $connector . $backup;
		
		$insider = '';
		if (!empty($font_family))
			$insider.= 'font-family:' . $font_family . ';';
		if (strpos($style, 'italic')) {
			$font_style = 'italic';
		}
		$weight = str_replace('italic', '', $style);
		if (!empty($font_style))
			$insider.= 'font-style:' . $font_style . ';';
		if (!empty($weight))
			$insider.= 'font-weight:' . $weight . ';';
		if (!empty($size))
			$insider.= 'font-size:' . $size . 'px;';
		if (!empty($height))
			$insider.= 'line-height:' . $height . 'px;';
			
		if((!empty($insider)))
			$css.= $elem . '{' . $insider . '}';
	}
	file_put_contents(Q_THEME_DIR.'/css/dynamic.css', $css);
	qa_opt('cs_custom_style_created', true);
}


/*
	Omit PHP closing tag to help avoid accidental output
*/