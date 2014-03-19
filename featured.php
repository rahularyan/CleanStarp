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
		$this->theme_directory = $qa_layers['Featured']['directory'];
		$this->theme_url = $qa_layers['Featured']['urltoroot'];
		qa_html_theme_base::qa_html_theme_base($template, $content, $rooturl, $request);
	}

	function doctype(){

		global $qa_request;
		$this->content['navigation']['main']['featured'] = array(
			'label' => 'Featured',
			'url' => qa_path_html('featured'),
			'icon' => 'icon-puzzle',
		);

		
		if( strpos($qa_request,'featured') !== false ) {
			
			$this->content['navigation']['main']['featured']['selected'] = true;
	
			$this->content['navigation']['main']['selected'] = true;			
			$this->template="featured";
			$this->content['site_title']="featured";
			$this->content['error']="";
			$this->content['suggest_next']="";
			$this->content['title']="";
			
			require_once QA_INCLUDE_DIR.'qa-app-posts.php';
			
			$posts = qa_db_read_all_assoc(qa_db_query_sub('SELECT * FROM ^postmetas, ^posts INNER JOIN ^users ON ^posts.userid=^users.userid WHERE ^posts.type=$ and ( ^postmetas.postid = ^posts.postid and ^postmetas.title = "featured_question" ) ORDER BY ^posts.created DESC LIMIT #', 'Q', '12'));	
			
			$this->content['featured_list']=$posts; 
		}
		qa_html_theme_base::doctype();
	}	
	function head_css()
    {
        qa_html_theme_base::head_css();
       
		if ($this->request == 'featured')
			$this->output('<link rel="stylesheet" type="text/css" href="' . Q_THEME_URL . '/css/tip_cards.css"/>');
    }
	function head_script()
    {
        qa_html_theme_base::head_script();
		
		if ($this->request == 'featured')
			$this->output('<script type="text/javascript" src="' . Q_THEME_URL . '/js/jquery.tip_cards.min.js"></script>');
        
    }
	
	function main()
    {
        if ($this->request == 'featured') {
            $content = $this->content;
            $this->output('<div class="qa-main qa-template-featured clearfix"><div class="col-sm-12">');
            $this->output('<h1 class="page-title">', $this->content['title'], '</h1>');
            $this->main_parts($content);
            $this->output('</div></div> <!-- END qa-main -->', '');
        } else
            qa_html_theme_base::main();
    }
	function main_part($key, $part)
    {
        if (($this->request == 'featured') && ($key == 'featured_list')) {
            $this->featued_box_items($part);
        } else
            qa_html_theme_base::main_part($key, $part);
    }
	function featued_box_items($q_items)
	{
		$this->output('<ul class="tips">');
		foreach ($q_items as $k => $q_item)
			$this->featued_box($q_item, $k);
		$this->output('</ul>');
	}
	
	function featued_box($q_item, $k)
	{

		$k = $k+1;
		$this->output('<li '.@$q_item['tags'].'>');
	
		$this->output('<div class="tc_front">');		
		$this->output('<a class="open-tip" href="#tip'.$q_item['postid'].'">'.$k.'. '.$q_item['title'].'</a>');	
		$this->output('<a class="cat-in icon-folder-close" href="#">Replace This</a>');	
		$this->output('</div>
		
			<div class="tc_back"></div>
			<div id="tip'.$q_item['postid'].'" class="tip">
				<div class="tc_front">
					<h1>'.$q_item['title'].'</h1>
					<p>'.cs_truncate(strip_tags($q_item['content']), 100).'</p>
				</div>
				<div class="tc_back">
					<p>'.$q_item['title'].'</p>
					<a class="cat-in icon-folder-close" href="#">Replace This</a>
					<div class="bottom-line">
						' . cs_get_post_avatar($q_item, $q_item['userid'], 40, true).'
					</div>
				</div>
			</div>
		');
  

		$this->output('</li>', '');
	}
	
}


/*
	Omit PHP closing tag to help avoid accidental output
*/