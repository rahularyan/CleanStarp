// Tab for option page
function ra_question_meta(){
	$('#q_meta_save').click(function(e){
		$.ajax({
			data: {
				ra_ajax: true,
				ra_ajax_html: true,
				featured_image: $("#featured_image").val(),
				featured_question: $('#option_featured_question').attr('checked'),
				action: 'save_q_meta',
			},
			dataType: 'html',
			success: function (response) {
			},
		});	
		qa_hide_waiting(this);
		$('#question-meta').append('<span class="bg-success btn" id="q-meta-notice">Settings are saved.</span>');
		setTimeout(function() {$('#q-meta-notice').fadeOut(1000);}, 1000);
	});
}

function ra_tab(){
	jQuery('.ra-option-tabs li:first-child').addClass('active');
	jQuery('.ra-option-tabs li a').click(function(e){
		e.preventDefault();
		jQuery('.ra-option-tabs li').removeClass('active');
		jQuery(this).parent().addClass('active');
		var t = jQuery(this).data('toggle');
		jQuery('[class^="qa-part-form-tc-"]').hide();
		jQuery(t).show();
		
	});
}
function dropdown_override() {
	$('.main-menu .dropdown').hover(function() {
			$(this).stop(true, true).addClass('open');

	}, function() {

			$(this).stop(true, true).removeClass('open');

	});

	$('.main-menu .dropdown > a').click(function(){
		
			location.href = this.href;
	});

}
function ra_set_active_sub_nav(elem){
	$(elem).closest('.qa-nav-sub-list').find('li a').removeClass('qa-nav-sub-selected');
	$(elem).addClass('qa-nav-sub-selected');
}

function ra_ajax_sub_menu(elem){
	$(elem).click(function(e){
		e.preventDefault();
		ra_set_active_sub_nav(this);
		
		var url = $(this).attr('href');
		$.get( url, function( data ) {
			var html = $(data).find('.qa-part-q-list form');
			$('.qa-part-q-list').html(html);
			
		});
	});
}

function ra_vote_click(){
	$('body').delegate('.vote-up, .vote-down', 'click', function(){
		ra_ajax_loading(this);
		if (typeof ($(this).data('id')) != 'undefined'){
			var ens=$(this).data('id').split('_');
			var parent = $(this).parent();
			var postid=ens[1];
			var vote=parseInt(ens[2]);
			var code=$(this).data('code');
			var anchor=ens[3];
			
			qa_ajax_post('vote', {postid:postid, vote:vote, code:code},
				function(lines) {
					if (lines[0]=='1') {
						qa_set_inner_html(document.getElementById('voting_'+postid), 'voting', lines.slice(1).join("\n"));
						$('.voting a').tooltip({placement:'bottom'});
						

					} else if (lines[0]=='0') {						
						ra_alert(lines[1]);					
					} else
						qa_ajax_error();
				}
			);	
		}
		return false;
	});	
}
function ra_ajax_loading($elm){
	var position = $($elm).offset();
	var html = '<div id="ajax-loading"></div>';	
	$(html).appendTo('body').ajaxStart(function () {
		$('#ajax-loading').css(position);
		$(this).show();
	});

	$("#ajax-loading").ajaxStop(function () {
		$(this).remove();
	});
}
function ra_toggle_editor(){	
	$( '#q_doanswer' ).on('click', function(event) {
		event.preventDefault();
		$('html, body').animate({
			scrollTop: $('#anew').offset().top
		}, 500);
	});
}
function ra_favorite_click()
{
	$('body').delegate( '.fav-btn', 'click', function() {
		ra_ajax_loading(this);
		var ens 	=	$(this).data('id').split('_');
		var code	=	$(this).data('code');
		var elem	=	$(this);
		qa_ajax_post('favorite', {entitytype:ens[1], entityid:ens[2], favorite:parseInt(ens[3]), code:code},
			function (lines) {
				if (lines[0]=='1'){
					
					elem.parent().empty().html(lines.slice(1).join("\n"));
					$('.fav-btn').tooltip({placement:'bottom'});
				}else if (lines[0]=='0') {
					alert(lines[1]);
					//ra_remove_process(elem);
				} else
					qa_ajax_error();
			}
		);
		
		//ra_process(elem, false);
		
		return false;
	});
}
function ra_alert($mesasge){
	if($('#ra-alert').length > 0)
		$('#ra-alert').remove();
	var html = '<div id="ra-alert" class="alert fade in"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>'+$mesasge+'</div>';
	$(html).appendTo('body');
	$('#ra-alert').css({left:($(window).width()/2 - $('#ra-alert').width()/2)}).animate({top:'50px'},300);
}
function ra_sparkline(elm){
 	
  	var isRgbaSupport = function(){
		var value = 'rgba(1,1,1,0.5)',
		el = document.createElement('p'),
		result = false;
		try {
			el.style.color = value;
			result = /^rgba/.test(el.style.color);
		} catch(e) {}
		el = null;
		return result;
	};

	var toRgba = function(str, alpha){
		var patt = /^#([\da-fA-F]{2})([\da-fA-F]{2})([\da-fA-F]{2})$/;
		var matches = patt.exec(str);
		return "rgba("+parseInt(matches[1], 16)+","+parseInt(matches[2], 16)+","+parseInt(matches[3], 16)+","+alpha+")";
	};

	// chart js
	var generateSparkline = function($re){
		$(elm).each(function(){
			var $data = $(this).data();
			if($re && !$data.resize) return;
			if($data.type == 'bar'){
				!$data.barColor && ($data.barColor = "#3fcf7f");
				!$data.barSpacing && ($data.barSpacing = 2);
				$(this).next('.axis').find('li').css('width',$data.barWidth+'px').css('margin-right',$data.barSpacing+'px');
			};
			
			($data.type == 'pie') && $data.sliceColors && ($data.sliceColors = eval($data.sliceColors));
			
			// $data.fillColor && ($data.fillColor.indexOf("#") !== -1) && isRgbaSupport() && ($data.fillColor = toRgba($data.fillColor, 0.5));
			$data.spotColor = $data.minSpotColor = $data.maxSpotColor = $data.highlightSpotColor = $data.lineColor;
			$(this).sparkline( $data.data || "html", $data);

			if($(this).data("compositeData")){
				var $cdata = {};
				$cdata.composite = true;
				$cdata.spotRadius = $data.spotRadius;
				$cdata.lineColor = $data.compositeLineColor || '#a3e2fe';
				$cdata.fillColor = $data.compositeFillColor || '#e3f6ff';
				$cdata.highlightLineColor =  $data.highlightLineColor;
				$cdata.spotColor = $cdata.minSpotColor = $cdata.maxSpotColor = $cdata.highlightSpotColor = $cdata.lineColor;
				isRgbaSupport() && ($cdata.fillColor = toRgba($cdata.fillColor, 0.5));
				$(this).sparkline($(this).data("compositeData"),$cdata);
			};
			if($data.type == 'line'){
				$(this).next('.axis').addClass('axis-full');
			};
		});
	};

	var sparkResize;
	$(window).resize(function(e) {
		clearTimeout(sparkResize);
		sparkResize = setTimeout(function(){generateSparkline(true)}, 500);
	});
	generateSparkline(false);

  }
  
function ra_load_items(){
	var winwidth 	= $(window).width(),
		contwidth 	= $('#site-body').width(),
		ajaxblockwidth 	= winwidth - contwidth;

	if(winwidth > 1170 && ajaxblockwidth > 250){
		$.ajax({
            data: {
				ra_ajax: true,
				ra_ajax_html: true,
				height: $('#site-body').height(),
                action: 'get_ajax_block',
            },
            dataType: 'html',
            context: this,
            success: function (response) {
				$('#ajax-item #ajax-blocks').css('width', (winwidth - contwidth)- 30 );
				$(response).appendTo('#ajax-item #ajax-blocks');
				ra_sparkline('.pieact');
            },
        });
		
	}

}
function ra_ajax_item_resize(){
	var winwidth 	= $(window).width(),
		contwidth 	= $('#site-body').width(),
		ajaxblockwidth 	= winwidth - contwidth;

	if(winwidth > 1170 && ajaxblockwidth > 250){	
		$('#ajax-item #ajax-blocks').css('width', (winwidth - contwidth)- 30 );		
	}else{
		$('#ajax-item #ajax-blocks').hide();
	}

}

function ra_slide_menu(){
	$('#slide-mobile-menu').toggle(
		function() {
			$('.left-sidebar').animate({'left':0}, 200);
			$('.qa-main').animate({'width': $('.qa-main').width(), 'margin-left':150},200);
		}, function() {
			$('.left-sidebar').animate({'left':-150}, 200);
			$('.qa-main').animate({'width': 'auto', 'margin-left':''}, 200);
		}
	);
}
function ra_float_left(){
	var winwidth 	= $(window).width();
	if(winwidth < 980)
		$('.left-sidebar .float-nav').removeAttr('style');
	else
	$(window).scroll(function(){
		var st = $(this).scrollTop();
		
		
		if(winwidth > 980){
			$('.left-sidebar').each(function(){
				var $this = $(this), 
					offset = $this.offset(),
					h = $this.height(),
					$float = $this.find('.float-nav'),
					floatH = $float.height(),
					topFloat = 0;
				if(st >= offset.top-topFloat){
					$float.css({'position':'fixed', 'top':topFloat+'px'});
				}else if(st < offset.top + h-topFloat - floatH){
					$float.css({'position':'absolute', 'top':0});
				}else{
					$float.css({'position':'absolute', 'top':0});
				}
			})
		}else{
			$('.left-sidebar .float-nav').removeAttr('style');
		}
	});
}

function ra_widgets(){
	$('.position-toggler').click(function(){
		$('.position-canvas').not($(this).parent().next()).hide();
		$(this).parent().next().toggle(0);
		$(this).toggleClass('icon-angle-up icon-angle-down');
	});	
	$('#ra-widgets').delegate('.widget-delete', 'click', function(){
		var $parent = $(this).closest('.widget-canvas');
		$(this).closest('.draggable-widget').remove();
		$parent.find('.widget-save').addClass('active');	
	});		
	$('#ra-widgets').delegate('.draggable-widget select, .draggable-widget input, .draggable-widget textarea', 'click', function(){
		var $parent = $(this).closest('.widget-canvas');
		$parent.find('.widget-save').addClass('active');	
	});	
	$('#ra-widgets').delegate('.widget-template-to', 'click', function(){
		var $parent = $(this).closest('.position-canvas');
		$(this).closest('.draggable-widget').find('.select-template').slideToggle(200);
	});	
	$('#ra-widgets').delegate('.widget-options', 'click', function(){
		var $parent = $(this).closest('.position-canvas');
		$(this).closest('.draggable-widget').find('.widget-option').slideToggle(200);
	});	

	
	$('#ra-widgets').delegate('.widget-save.active', 'click', function(){
		var $parent = $(this).closest('.widget-canvas').find('.position-canvas');
		ra_save_widget($parent);
	});
	
	if ($('#ra-widgets').length>0) {
		$('#ra-widgets .draggable-widget').draggable({
			connectToSortable: '.position-canvas',
			helper: 'clone',
			handle: '.drag-handle',
			drag: function (e, t) {
				t.helper.width(299);
				t.helper.height(42);
			}
		});

		$('.position-canvas').sortable({
			connectWith: '.column',
			opacity: .35,
			placeholder: 'placeholder',
			handle: '.drag-handle',
			start: function (e, ui) {
				ui.placeholder.height(42);
			},
			stop: function () { 
				$(this).closest('.widget-canvas').find('.widget-save').addClass('active');				
			}
		});
	}
}
function ra_save_widget($elm){
	var widget ={};
	var	locations = {};
	var	options = {};
		
	$elm.find('.draggable-widget').each(function(){		
		var name = $(this).data('name');
		
		widget[name] = {'locations':'', 'options':''};
		
		$(this).find('.select-template input').each(function(){
			locations[$(this).attr('name')] = $(this).is(':checked') ? true : false;
		});
		$(this).find('.widget-option input, .widget-option select, .widget-option textarea').each(function(){
			options[$(this).attr('name')] = $(this).val();
		});
		
		widget[name]['locations'] = locations;
		widget[name]['options'] = options;
		
	});
	

	 $.ajax({
		data: {
			ra_ajax: true,
			ra_ajax_html: true,
			position: $elm.data('name'),
			widget_names: JSON.stringify(widget),
			action: 'save_widget_position',
		},
		dataType: 'html',
		context: $elm,
		success: function (response) {
			$elm.closest('.widget-canvas').find('.widget-save').removeClass('active');
		},
	});
}

function ra_ask_box_autocomplete(){
	$( "#ra-ask-search" ).autocomplete({
		source: function( request, response ) {
			$.ajax({
				data: {
					ra_ajax: true,
					ra_ajax_html: true,
					start_with: request.term,
					action: 'get_question_suggestion',
				},
				dataType: 'json',
				context: this,
				success: function (data) {
					response($.map(data, function(obj) {
						return {
							label: obj.title,
							url: obj.url,
							tags: obj.tags,			
							answers: obj.answers,			
							blob: obj.blob			
						};
					}));
				},
			});
		},
		minLength: 3,
		appendTo:".ra-ask-widget",
		messages: {
			noResults: '',
			results: function() {}
		}
	}).data( "uiAutocomplete" )._renderItem = function( ul, item ) {
		return $("<li></li>")
		.data("item.uiAutocomplete", item)
		.append('<a href="'+item.url+'" class=""><img src="'+item.blob+'" /><span class="title">' + item.label + '</span><span class="tags icon-tags">'+item.tags+'</span><span class="category icon-chat">'+item.answers+'</span></a>')
		.appendTo(ul);
	};

    $('#ra-ask-search').off('keyup keydown keypress');
}

function back_to_top(){
	$("#back-to-top").hide();
	$(function () {
		$(window).scroll(function () {
			if ($(this).scrollTop() > 50) {
				$('#back-to-top').fadeIn();
			} else {
				$('#back-to-top').fadeOut();
			}
		});
		$('#back-to-top').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 500);
			return false;
		});
	});
}

function ra_load_login_register(){
	$('#login-register').not('active').click(function(){
		$.ajax({
			data: {
				ra_ajax: true,
				ra_ajax_html: true,
				action: 'get_login_register',
			},
			dataType: 'html',
			success: function (response) {
				$('.qa-main > .list-c').html(response);
			},
		});
	});
}

$(document).ready(function(){

	var win_height = $(window).height();
	var main_height = $('#site-body').height() +60;
	
	if( main_height < win_height)
		$('#site-body').css('height', win_height -50);
	
	ra_float_left();	
	dropdown_override();
	ra_slide_menu();
	ra_vote_click();
	ra_toggle_editor();
	ra_favorite_click();
	ra_tab();
	ra_widgets();
	back_to_top();
	ra_question_meta();
	ra_load_login_register();
	if ($('.ra-ask-widget').length>0)
		ra_ask_box_autocomplete();
	
	if ((typeof qa_wysiwyg_editor_config == 'object') && $('body').hasClass('qa-template-question'))
		qa_ckeditor_a_content=CKEDITOR.replace('a_content', window.qa_wysiwyg_editor_config);
	
	$("#q_meta_remove_featured_image").click(function(e){
		$("#featured_image").val("");
		$("#image-preview").attr("src",theme_url + "/images/featured-preview.jpg");
	});
<<<<<<< HEAD
	if($("#fileuploader").length){
		$("#fileuploader").uploadFile({
			url:theme_url + "/inc/upload.php",
			allowedTypes:"png,gif,jpg,jpeg",
			fileName:"featured",

			maxFileCount:1,
			multiple:false,
			showDelete: true,
			onSuccess:function(files,data,xhr)
			{
				$("#featured_image").val(data);
				$("#image-preview").attr("src",theme_url + "/uploads/"+data);
			},
			deleteCallback:function(data, pd) {
				$.post(theme_url + "/inc/upload-delete.php", {op: "delete",name: data},
						function (resp,textStatus, jqXHR) {
								$("#image-preview").attr("src",theme_url + "/images/featured-preview.jpg");
								$("#featured_image").val("");
						});
				pd.statusbar.hide(500); //You choice.		
			},
		});
	}
=======
	/* $("#fileuploader").uploadFile({
		url:theme_url + "/inc/upload.php",
		allowedTypes:"png,gif,jpg,jpeg",
		fileName:"featured",

		maxFileCount:1,
		multiple:false,
		showDelete: true,
		onSuccess:function(files,data,xhr)
		{
			$("#featured_image").val(data);
			$("#image-preview").attr("src",theme_url + "/uploads/"+data);
		},
		deleteCallback:function(data, pd) {
			$.post(theme_url + "/inc/upload-delete.php", {op: "delete",name: data},
					function (resp,textStatus, jqXHR) {
							$("#image-preview").attr("src",theme_url + "/images/featured-preview.jpg");
							$("#featured_image").val("");
					});
			pd.statusbar.hide(500); //You choice.		
		},
	});	 */	
>>>>>>> origin/master
/* 	ra_ajax_sub_menu('.qa-nav-sub-recent a');
	ra_ajax_sub_menu('.qa-nav-sub-hot a');
	ra_ajax_sub_menu('.qa-nav-sub-votes a');
	ra_ajax_sub_menu('.qa-nav-sub-answers a');
	ra_ajax_sub_menu('.qa-nav-sub-views a');
	ra_ajax_sub_menu('.qa-nav-sub-by-answers a');
	ra_ajax_sub_menu('.qa-nav-sub-by-selected a');
	ra_ajax_sub_menu('.qa-nav-sub-by-upvotes a'); */
	
	ra_sparkline('.sparkline');
	
	//ra_load_items();
	
	/* $(window).resize(function(){
		ra_ajax_item_resize();
		ra_float_left()
	}); */

$('#featured-slider').carousel({
	interval: 10000
	})
});