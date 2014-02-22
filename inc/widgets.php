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
				<div id="ra-widgets">
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
						<div class="widget-options icon-wrench"></div>
					</div>
					<div class="select-template">
						<label>
						<input type="checkbox" name="show_title" checked> Show widget title</label><br />
						<span>Select where you want to show</span>
						<?php
							$this->get_widget_template_checkbox();
						?>
					</div>
					<div class="widget-option">
						<?php $this->get_widget_form($k); ?>
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
												<div class="widget-options icon-wrench"></div>		
											</div>
											<div class="select-template">
											<input type="checkbox" name="show_title" <?php echo (@$template['locations']['show_title'] ? 'checked' : ''); ?>> Show widget title</label><br />
												<span>Select pages where you want to show</span>
												<?php
													foreach(ra_get_template_array() as $k => $t){
														$checked = @$template['locations'][$k] ? 'checked' : '';
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
											<div class="widget-option">
												<?php $this->get_widget_form($name, $template['options']); ?>
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
		
		function get_widget_template_checkbox(){
			foreach(ra_get_template_array() as $t_name => $t)
				$this->output( '												
					<div class="checkbox">
						<label>
							<input type="checkbox" name="'.$t_name.'" checked> '.$t.'
						</label>
					</div>
				');

		}
		function get_widget_form($name, $options = false){
			$module	=	qa_load_module('widget', $name);							
			if(is_object($module) && method_exists($module, 'ra_widget_form')){
				$fields = $module->ra_widget_form();
				
				if($options){
					foreach($options as $k => $opt){
						$fields['fields'][$k]['value'] = $opt;
					}
				}
				$this->form($fields); 
			}
		}
		
}


/*
	Omit PHP closing tag to help avoid accidental output
*/