<?php
	/* don't allow this page to be requested directly from browser */	
	if (!defined('QA_VERSION')) {
			header('Location: /');
			exit;
	}
	
	
	if(isset($_REQUEST['ra_ajax'])){
		$action = 'ra_ajax_'.$_REQUEST['action'];
		if(function_exists($action))
			$action();
	}
