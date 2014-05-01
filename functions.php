<?php
	/* don't allow this page to be requested directly from browser */	
	if (!defined('QA_VERSION')) {
			header('Location: /');
			exit;
	}

	cs_event_hook('enqueue_css', NULL, 'cs_enqueue_css');
	function cs_enqueue_css($css_src){
	
		//$css_src['cs_bootstrap'] = Q_THEME_URL . '/css/bootstrap.css';		
		$css_src['cs_responsive'] = Q_THEME_URL . '/css/responsive.css';
		$css_src['cs_main'] = Q_THEME_URL . '/css/main.css';
		$css_src['cs_color'] = Q_THEME_URL . '/css/theme-green.css';
		
		if (qa_opt('cs_styling_rtl'))
			$css_src['cs_rtl'] = Q_THEME_URL . '/css/rtl.css';

		return  $css_src;
	}
	cs_event_hook('enqueue_scripts', NULL, 'cs_enqueue_scripts');
	function cs_enqueue_scripts($src){		
		$src['jquery-ui'] = Q_THEME_URL . '/js/jquery-ui.min.js';
		$src['bootstrap_js'] = Q_THEME_URL . '/js/bootstrap.js';
		
		$src['sparkline'] = Q_THEME_URL . '/js/jquery.sparkline.min.js';		
		$src['cs_theme'] = Q_THEME_URL . '/js/theme.js';


		return  $src;
	}
	
