<?php
/* don't allow this page to be requested directly from browser */	
if (!defined('QA_VERSION')) {
		header('Location: /');
		exit;
}

// Background customizations
$p_url = $this->theme_url . '/images/patterns/';
$css = '';
// Body
$bg_image = qa_opt('bg_select');
if ($bg_image=='bg_default')
	$bg='';
elseif ($bg_image=='bg_color')
	$bg='background: none repeat scroll 0 0 ' . qa_opt('bg_color') . ' !important;';
else 
	$bg='background: url("' . $p_url . '/' . $bg_image . '.png" !important;)';
$text_color = qa_opt('text_color');
if (!(empty($text_color)))
	$bg.= 'color:' . $text_color . ';';
if (!(empty($bg))) $css .= 'body {' . $bg . '}';

// Borders
$color = qa_opt('border_color');
if (!(empty($color)))
	$css.= '.qa-main, .page-title, .qa-main .qa-nav-sub, .left-sidebar .qa-nav-main-list, .left-sidebar .qa-nav-sub-list,.left-sidebar .qa-nav-main-list .qa-nav-main-item .qa-nav-main-link:before,.left-sidebar .qa-nav-main-list .qa-nav-main-item .qa-nav-main-link, .left-sidebar .qa-nav-sub-list .qa-nav-sub-item .qa-nav-sub-link{border-color:' . $color . ' !important;}';

// links color
$color = qa_opt('link_color');
if (!(empty($color)))
	$css.= 'a{color:' . $color . ';}';
$color = qa_opt('link_hover_color');
if (!(empty($color)))
	$css.= 'a:hover{color:' . $color . ';}';

// navigation color
$color = qa_opt('nav_link_color');
if (!(empty($color)))
	$css.= '.qa-nav-main-link, .qa-nav-main-item .qa-nav-main-link.qa-nav-main-selected, .left-sidebar .qa-nav-main-list .qa-nav-main-item .qa-nav-main-link{color:' . $color . ';}';
$color = qa_opt('nav_link_color_hover');
if (!(empty($color)))
	$css.= '.qa-nav-main-link:hover, .qa-nav-main-item:hover .qa-nav-main-link.qa-nav-main-selected, .left-sidebar .qa-nav-main-list .qa-nav-main-item .qa-nav-main-link:hover{color:' . $color . ';}';
	
// sub navigation color
$color = qa_opt('subnav_link_color');
if (!(empty($color)))
	$css.= '.qa-nav-sub-link{color:' . $color . ';}';
$color = qa_opt('subnav_link_color_hover');
if (!(empty($color)))
	$css.= '.qa-nav-sub-link:hover{color:' . $color . ';}';

// question color
$color = qa_opt('q_link_color');
if (!(empty($color)))
	$css.= '.qa-q-item-title > a{color:' . $color . ';}';
$color = qa_opt('q_link_hover_color');
if (!(empty($color)))
	$css.= '.qa-q-item-title > a:hover{color:' . $color . ';}';
	
// Selection Highlight color
$color = qa_opt('highlight_color');
if (!(empty($color)))
	$css.= '::selection {color: ' . $color . ';} ::-moz-selection {color: ' . $color . ';};}';
$color = qa_opt('highlight_bg_color');
if (!(empty($color)))
	$css.= '::selection {background: ' . $color . ';} ::-moz-selection {background: ' . $color . ';};}';



	
qa_opt('ra_custom_style', $css);


/*
	Omit PHP closing tag to help avoid accidental output
*/