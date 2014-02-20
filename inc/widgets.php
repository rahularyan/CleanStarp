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
		$this->theme_directory = $qa_layers['Theme Widgets']['directory'];
		$this->theme_url = $qa_layers['Theme Widgets']['urltoroot'];
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
		//var_dump($qa_request);
		$this->content['navigation']['main']['widgets'] = array(
			'label' => 'Theme Widgets',
			'url' => qa_path_html('widgets'),
		);
		if($qa_request == 'widgets') {
			$this->content['navigation']['main']['widgets']['selected'] = true;
			$this->content['navigation']['main']['selected'] = true;
			$this->template="widgets";
			$this->content['site_title']="Theme Widgets";
			$this->content['error']="";
			$this->content['suggest_next']="";
			$this->content['title']="Theme Widgets";
			//$this->content['custom']='';
		
			$saved=false;
			if (qa_clicked('ra_save_button')) {	
					
				$saved=true;
			}
			$saved ? 'Settings saved' : null;
			
			$ra_page = '
				<div id="ra-widgets" class="row">
					<div class="widget-list col-sm-4">
						'. $this->ra_get_widgets() .'
					</div>
					<div class="widget-postions col-sm-8">
						'.$this->ra_get_widgets_positions().'
					</div>
				</div>
			';
			$this->content['custom'] = $ra_page;
		}
		qa_html_theme_base::doctype();
	}	
	
		function main()
		{
			if($this->request == 'widgets') {
				$content=$this->content;
				$this->output('<div class="qa-main theme-widgets clearfix"><div class="col-sm-12">');
				$this->output(
					'<h1 class="page-title">',
					$this->content['title'],
					'</h1>'
				);
				$this->main_parts($content);
				$this->output('</div></div> <!-- END qa-main -->', '');
				$this->footer();
			}else
				qa_html_theme_base::main();
		}
		function main_part($key, $part)
		{
			if( ($this->request == 'widgets') && ($key == 'custom') ){
				$this->output_raw($part);
			}else
				qa_html_theme_base::main_part($key, $part);
		}
		
		function ra_get_widgets(){
			ob_start();
			foreach(qa_load_modules_with('widget', 'allow_template') as $k => $widget){
				?>
				<div class="draggable-widget" data-name="<?php echo $k; ?>">					
					<div class="widget-title"><?php echo $k; ?> 
						<div class="drag-handle icon-move"></div>
						<div class="widget-delete icon-trash"></div>
						<div class="widget-template-to icon-list"></div>					
					</div>
					<div class="select-template">
						<span>Select where you want to show</span>
						<?php
							foreach(ra_get_template_array() as $k => $t){
								echo '												
									<div class="checkbox">
										<label>
											<input type="checkbox" name="'.$k.'"> '.$t.'
										</label>
									</div>
								';
							}
						?>
					</div>
				</div>
				<?php
			}
			return ob_get_clean();
		}		
		
		function ra_get_widgets_positions(){
			$widget_positions = unserialize(qa_opt('ra_widgets_positions'));
			
			$widgets = unserialize(qa_opt('ra_widgets'));
			
			ob_start();
			if(is_array($widget_positions)){
				foreach($widget_positions as $name => $description){
				
					?>
					<div class="widget-canvas" data-name="<?php echo $name; ?>">		
						<div  class="position-header">		
							<?php echo $name; ?><span class="position-description"><?php echo $description; ?></span>							
							<i class="position-toggler icon-angle-down"></i>
							<div class="widget-save icon-ok"> Save</div>
						</div>
						<div class="position-canvas" data-name="<?php echo $name; ?>">
							<?php
								if(isset($widgets[$name]) && !empty($widgets[$name]))
									foreach($widgets[$name] as $name => $template){ ?>
										<div class="draggable-widget" data-name="<?php echo $name; ?>">	
											<div class="widget-title"><?php echo $name; ?> 
												<div class="drag-handle icon-move"></div>
												<div class="widget-delete icon-trash"></div>
												<div class="widget-template-to icon-list"></div>		
											</div>
											<div class="select-template">
												<span>Select where you want to show</span>
												<?php
													foreach(ra_get_template_array() as $k => $t){
														$checked = @$template[$k] ? 'checked' : '';
														echo '												
															<div class="checkbox">
																<label>
																	<input type="checkbox" name="'.$k.'" '.$checked.'> '.$t.'
																</label>
															</div>
														';
													}
												?>
											</div>
										</div>									
									<?php
									}
									
							?>
						</div>
					</div>
					<?php
				}
			}
			return ob_get_clean();
		}
		
}


/*
	Omit PHP closing tag to help avoid accidental output
*/