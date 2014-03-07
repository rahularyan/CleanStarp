<?php
	class cs_site_status_widget {
		function cs_widget_form()
		{
			
			return array(
				'style' => 'wide',
				'fields' => array(
					'cs_ss_type' => array(
						'label' => 'Type of chart',
						'type' => 'select',
						'tags' => 'name="cs_ss_type"',
						'value' => 'bar',
						'options' => array(
							'bar' => 'bar',
							'pie' => 'pie',
						)
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
		function cs_stats_chart($type = 'bar'){
			$o = '<div class="site-status-inner clearfix">';
			
			if($type == 'bar'){
			$o .='<div class="bar-float"><div class="sparkline" data-type="bar" data-bar-color="#E45840" data-bar-width="30" data-height="80">
					<!--'.qa_opt('cache_qcount').','.qa_opt('cache_acount').','.qa_opt('cache_ccount').','.qa_opt('cache_unaqcount').','.qa_opt('cache_unselqcount').'--></div>
                    <ul class="list-inline text-muted axis">
						<li>Que</li><li>Ans</li><li>Com</li><li>UA</li><li>US</li>
					</ul></div>
					<div class="acti-indicators">
						<ul>
					<li><i class="fa fa-circle text-info" style="color:#233445"></i> Questions <span>'.qa_opt('cache_qcount').'</span></li>
					<li><i class="fa fa-circle text-info" style="color:#3fcf7f"></i> Answers <span>'.qa_opt('cache_acount').'</span></li>
					<li><i class="fa fa-circle text-info" style="color:#FF5F5F"></i> Comments <span>'.qa_opt('cache_ccount').'</span></li>
					<li><i class="fa fa-circle text-info" style="color:#13C4A5"></i> Unanswered <span>'.qa_opt('cache_unaqcount').'</span></li>
					<li><i class="fa fa-circle text-info" style="color:#F4C414"></i> Unselected <span>'.qa_opt('cache_unselqcount').'</span></li>
				</ul>
					</div>';
			}else{
			$o .= '<div class="bar-float"><div class="sparkline pieact inline" data-type="pie" data-height="130" data-slice-colors="[\'#233445\',\'#3fcf7f\',\'#ff5f5f\',\'#f4c414\',\'#13c4a5\']">'.qa_opt('cache_qcount').','.qa_opt('cache_acount').','.qa_opt('cache_ccount').','.qa_opt('cache_unaqcount').','.qa_opt('cache_unselqcount').'</div>
				<div class="line pull-in"></div></div>
				<div class="acti-indicators">
				<ul>
					<li><i class="fa fa-circle text-info" style="color:#233445"></i> Questions <span>'.qa_opt('cache_qcount').'</span></li>
					<li><i class="fa fa-circle text-info" style="color:#3fcf7f"></i> Answers <span>'.qa_opt('cache_acount').'</span></li>
					<li><i class="fa fa-circle text-info" style="color:#FF5F5F"></i> Comments <span>'.qa_opt('cache_ccount').'</span></li>
					<li><i class="fa fa-circle text-info" style="color:#13C4A5"></i> Unanswered <span>'.qa_opt('cache_unaqcount').'</span></li>
					<li><i class="fa fa-circle text-info" style="color:#F4C414"></i> Unselected <span>'.qa_opt('cache_unselqcount').'</span></li>
				</ul>
				</div>';
			}
			$o .= '</div>';
			return $o;
		}
		function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
		{
			$widget_opt = @$themeobject->current_widget['Site Status']['options'];
			$type = (@$widget_opt['cs_ss_type'] == 'pie' ? 'pie' : 'bar');
			if(@$themeobject->current_widget['Site Status']['locations']['show_title'])
				$themeobject->output('<h3 class="widget-title">Site Status</h3>');
				
			$themeobject->output('<div class="ra-site-status-widget">');
			$themeobject->output($this->cs_stats_chart($type));
			$themeobject->output('</div>');
		}
	
	}
/*
	Omit PHP closing tag to help avoid accidental output
*/