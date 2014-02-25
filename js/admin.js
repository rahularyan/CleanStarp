$(document).ready(function(){
	// General
	$("#logo_uploader").uploadFile({
		url:theme_url + "/inc/upload.php",
		allowedTypes:"png,gif,jpg,jpeg",
		fileName:"myfile",
		maxFileCount:1,
		multiple:false,
		showDelete: true,
		onSuccess:function(files,data,xhr)
		{
			$("#ra_logo_field").val(theme_url + "/uploads/" + data);
			$("#logo-preview").attr("src",theme_url + "/uploads/"+data);
			$("#logo-preview").show();
		},
		deleteCallback:function(data, pd) {
			$.post(theme_url + "/inc/upload-delete.php", {op: "delete",name: data},
					function (resp,textStatus, jqXHR) {
							$("#logo-preview").hide(500);
							$("#ra_logo_field").val("");
					});
			pd.statusbar.hide(500); //You choice.		
		},
	});
	$("#favicon_uploader").uploadFile({
		url:theme_url + "/inc/upload.php",
		allowedTypes:"png,gif,jpg,jpeg",
		fileName:"myfile",
		maxFileCount:1,
		multiple:false,
		showDelete: true,
		onSuccess:function(files,data,xhr)
		{
			$("#ra_favicon_field").val(theme_url + "/uploads/" + data);
			$("#favicon-preview").attr("src",theme_url + "/uploads/" + data);
			$("#favicon-preview").show();
		},
	});
	
	// Typography
	$(".font-family").chosen({width: "370px",allow_single_deselect: true ,no_results_text: "There is no font with this name!"});
	$(".font-style").chosen({width: "200px",allow_single_deselect: true});
	$(".font-family-backup").chosen({width: "260px",allow_single_deselect: true});
	$( ".font-family, .font-style, .font-family-backup" ).on('change keyup paste',function(){
		var font=$(this).parent().children('#typo_option_family').val();
		var font_backup=$(this).parent().children('#typo_option_backup').val();
		if((font == '') || (font_backup=='')){var connector = '';}else{var connector = ', ';}
		$(this).parent().children('span').css('font-family', font + connector + font_backup);
		$(this).parent().children('span').children().css('font-family', font + connector + font_backup);
		
		$(this).parent().children('span').css('font-style', 'normal');
		$(this).parent().children('span').children().css('font-style', 'normal');
		var font_style = $(this).parent().children('#typo_option_style').val();
		if (font_style.indexOf("italic") !== -1) {
			$(this).parent().children('span').css('font-style', 'italic');
			$(this).parent().children('span').children().css('font-style', 'italic');
			font_style = font_style.replace('italic', '');
		}
		
		$(this).parent().children('span').css('font-weight', font_style);
		$(this).parent().children('span').children().css('font-weight', font_style);
		
		font_option = $(this).find('option:selected')
		if(font_option.attr("font-data-type")=='googlefont'){
			// update styling variants
			var details = jQuery.parseJSON(font_option.attr('font-data-detail'));
			var options = '<option value=""></option>';
			var selected = "";
			$.each(details, function(index, value) {
				if (value.id === font_style){selected = ' selected="selected"';}else{selected = '';}
				options += '<option value="' + value.id + '"' + selected + '>' + value.name.replace(/\+/g, " ") + '</option>';	
			});

			 $(this).parent().children('#typo_option_style').html(options).trigger('chosen:updated');
			// show backup fonts
			$(this).parent().children('#typo_option_backup_chosen').fadeIn('fast');
		} else {
			$(this).parent().children('#typo_option_backup_chosen').fadeOut('fast');
		}

	});
	$( ".font-size" ).on('change keyup paste',function(){
		var font_size = $(this).parent().children('#typo_option_size').val();
		if (font_size.match('^(0|[1-9][0-9]*)$')){
			$(this).parent().parent().children('span').css('font-size', font_size + 'px');
			$(this).parent().parent().children('span').children().css('font-size', font_size + 'px');
		}
	});
	$( ".font-size, .font-linehight" ).on('change keyup paste',function(){
		var font_height = $(this).parent().children('#typo_option_lineheight').val();
		if (font_height.match('^(0|[1-9][0-9]*)$')){
			$(this).parent().parent().children('span').css('line-height', font_height + 'px');
			$(this).parent().parent().children('span').children().css('line-height', font_height + 'px');
		}else{
			$(this).parent().parent().children('span').css('line-height', 'inherit');
			$(this).parent().parent().children('span').children().css('line-height', 'inherit');
		}
	});
	
	// Scroll
	$(function () {
		$(window).scroll(function () {
			if ($(document).height() <= $(window).scrollTop() + $(window).height() + 120) {
				$('.form-button-holder').css({"position":"inherit"});
				$('.form-button-holder').css({"width" : "auto"});
			} else {
				$('.form-button-holder').css({"position":"fixed"});
				var width= $('.form-button-sticky-footer').css("width");
				$('.form-button-holder').css({"width" : width});
				$('.form-button-holder').css({"bottom":"0"});
			}
		});
	});
	
	// Styling
	$( "#option_bg_select" ).change(function() {
		if ($( "#option_bg_select" ).val()=='bg_color')
			$( "#bg-color-container" ).show(500);
		else
			$( "#bg-color-container" ).hide(500);
	});
	$( "#option_enble_back_to_top" ).change(function() {
		$( "#back_to_top_location_container" ).toggle(500);
	});
	$( "#option_enable_adv_list" ).change(function() {
		$( "#ads_container" ).toggle(500);
	});
	
	// Advertisement
	var advertisement_list_count =  Number($("#adv_number").val());
	for(var i=0; i<advertisement_list_count; i++) {
		build_advertisement_uploader(i);
	}
	function build_advertisement_uploader(id){
		$("#adv_image_uploader_" + id).uploadFile({
			url:theme_url + "/inc/upload.php",
			allowedTypes:"png,gif,jpg,jpeg",
			fileName:"myfile",
			maxFileCount:1,
			multiple:false,
			showDelete: true,
			onSuccess:function(files,data,xhr)
			{
				$("#adv_image_url_"+id).val(theme_url + "/uploads/" + data);
				$("#adv_preview_"+id).attr("src",theme_url + "/uploads/"+data);
				$("#adv_preview_"+id).show();
			},
			deleteCallback:function(data, pd) {
				$.post(theme_url + "/inc/upload-delete.php", {op: "delete",name: data},
						function (resp,textStatus, jqXHR) {
								$("#adv_preview_"+id).hide(500);
								$("#adv_image_url_"+id).val("");
						});
				pd.statusbar.hide(500); //You choice.		
			},
		});
	}
	$('#add_adv').on('click', function(e){
		e.preventDefault();
		var ads_list_count =  Number($("#adv_number").val()) + 1;
		questions_list_count =  Number($("#question_list_count").val());
		var list_options = '';
		for(var i=1; i<= questions_list_count; i++) {
			list_options += '<option value="' + i + '">' + i + '</option>';
		}
		adv_count =  Number($("#adv_number").val()) + 1;
		$("input[name=adv_number]").val(adv_count);
		$("#ads_container").append('<tr id="adv_box_' + adv_count + '"><th class="qa-form-tall-label th_' + adv_count + '">Advertisment #' + adv_count + '<span class="description">static advertisement</span></th><td class="qa-form-tall-data"><div class="clearfix"></div><img id="adv_preview_' + adv_count + '" src="" class="adv-preview img-thumbnail" style="display:none;"><div class="clearfix"></div><div id="adv_image_uploader_' + adv_count + '">Upload Icon</div><input type="hidden" value="" id="social_image_url_' + adv_count + '" name="social_image_url_' + adv_count + '"><span class="description">Image Title</span><input class="qa-form-tall-text" type="text" id="adv_image_title_' + adv_count + '" name="adv_image_title_' + adv_count + '" value=""><span class="description">Image link</span><input class="qa-form-tall-text" id="adv_image_link_' + adv_count + '" name="adv_image_link_' + adv_count + '" type="text" value=""><span class="description">Display After this number of questions</span><select id="adv_location_' + adv_count + '" name="adv_location_' + adv_count + '" class="qa-form-wide-select">' + list_options + '</select><button advid="' + adv_count + '" id="advremove" name="advremove" class="qa-form-tall-button advremove pull-right btn" type="submit">Remove This Advertisement</button></td></tr>');
		$('html, body').animate({
			scrollTop: $(".th_" + adv_count).offset().top
		}, 800);
		build_advertisement_uploader(adv_count);
	});
	$('#add_adsense').on('click', function(e){
		e.preventDefault();
		var ads_list_count =  Number($("#adv_number").val()) + 1;
		questions_list_count =  Number($("#question_list_count").val());
		var list_options = '';
		for(var i=1; i<= questions_list_count; i++) {
			list_options += '<option value="' + i + '">' + i + '</option>';
		}
		adv_count =  Number($("#adv_number").val()) + 1;
		$("input[name=adv_number]").val(adv_count);
		$("#ads_container").append('<tr id="adv_box_' + adv_count + '"><th class="qa-form-tall-label th_' + adv_count + '">Advertisment #' + adv_count + '<span class="description">Google Adsense Code</span></th><td class="qa-form-tall-data"><input class="qa-form-tall-text" id="adv_adsense_' + adv_count + '" name="adv_adsense_' + adv_count + '" type="text" value=""><span class="description">Display After this number of questions</span><select id="adv_location_' + adv_count + '" name="adv_location_' + adv_count + '" class="qa-form-wide-select">' + list_options + '</select><button advid="' + adv_count + '" id="advremove" name="advremove" class="qa-form-tall-button advremove pull-right btn" type="submit">Remove This Advertisement</button></td></tr>');    
		$('html, body').animate({
			scrollTop: $(".th_" + adv_count).offset().top
		}, 800);
	});
	$( ".advremove" ).on('click', function(e){
		e.preventDefault();
		var adv_frame = $(this).parent().parent();
		adv_list_count =  Number($("#adv_number").val()) - 1;
		$("input[name=adv_number]").val(adv_list_count);
		adv_frame.fadeOut(500);
		setTimeout(function() {
			adv_frame.unbind();
			adv_frame.remove();
		}, 600);
	});
	
	// Social
	var social_list_count =  Number($("#social_count").val());
	for(var i=0; i<social_list_count; i++) {
		build_social_uploader(i);
	}
	function build_social_uploader(id){
		$("#social_image_uploader_" + id).uploadFile({
			url:theme_url + "/inc/upload.php",
			allowedTypes:"png,gif,jpg,jpeg",
			fileName:"myfile",
			maxFileCount:1,
			multiple:false,
			showDelete: true,
			onSuccess:function(files,data,xhr)
			{
				$("#social_image_url_"+id).val(theme_url + "/uploads/" + data);
				$("#social_image_preview_"+id).attr("src",theme_url + "/uploads/"+data);
				$("#social_image_preview_"+id).show();
			},
			deleteCallback:function(data, pd) {
				$.post(theme_url + "/inc/upload-delete.php", {op: "delete",name: data},
						function (resp,textStatus, jqXHR) {
								$("#social_image_preview_"+id).hide(500);
								$("#social_image_url_"+id).val("");
						});
				pd.statusbar.hide(500); //You choice.		
			},
		});
	}
	$('#add_social').on('click', function(e){
		e.preventDefault();
		var social_list_count =  Number($("#social_count").val()) + 1;
		var list_options = '<option value="1">Upload Social Icon</option>';
		list_options += '<option value="icon-facebook" class="icon-facebook">Faebook</option>';
		list_options += '<option value="icon-twitter" class="icon-twitter">Twitter</option>';
		list_options += '<option value="icon-google" class="icon-google">Google</option>';
		
		$("input[name=social_count]").val(social_list_count);
		$("#social_container").append('<tr id="social_box_' + social_list_count + '"><th class="qa-form-tall-label social_th_' + social_list_count + '">Social Link #' + social_list_count + '<span class="description">choose Icon and link to your social profile</span></th><td class="qa-form-tall-data"><span class="description">Social Profile Link</span><input class="qa-form-tall-text" id="social_link_' + social_list_count + '" name="social_link_' + social_list_count + '" type="text" value=""><span class="description">Link Title</span><input class="qa-form-tall-text" type="text" id="social_title_' + social_list_count + '" name="social_title_' + social_list_count + '" value=""><span class="description">Choose Social Icon</span><select id="social_icon_' + social_list_count + '" name="social_icon_' + social_list_count + '" fieldid="' + social_list_count + '" class="qa-form-wide-select social-select">' + list_options + '</select><div class="social_icon_file_' + social_list_count + '"><span class="description">upload Social Icon</span><img id="social_image_preview_' + social_list_count + '" src="" class="social-preview img-thumbnail" style="display:none;"><div id="social_image_uploader_' + social_list_count + '">Upload Icon</div><input type="hidden" value="" id="social_image_url_' + social_list_count + '" name="social_image_url_' + social_list_count + '"><button advid="' + social_list_count + '" id="social_remove" name="social_remove" class="qa-form-tall-button social_remove pull-right btn" type="submit">Remove This Link</button></td></tr>');
		$('html, body').animate({
			scrollTop: $(".social_th_" + social_list_count).offset().top
		}, 800);
		build_social_uploader(social_list_count);
	});
	$( ".social-select" ).change(function() {
		if ($(this).val()==1)
			$('.social_icon_file_' + $(this).attr('sociallistid')).show(500);
		else 
			$('.social_icon_file_' + $(this).attr('sociallistid')).hide(500);
	});
	
	$( ".social_remove" ).on('click', function(e){
		e.preventDefault();
		var social_frame = $(this).parent().parent();
		social_list_count =  Number($("#social_count").val()) - 1;
		$("input[name=social_count]").val(social_list_count);
		social_frame.fadeOut(500);
		setTimeout(function() {
			social_frame.unbind();
			social_frame.remove();
		}, 600);
	});
});

