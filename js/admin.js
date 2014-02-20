$(document).ready(function(){
	$( "#option_enable_adv_list" ).change(function() {
		$( "#ads_container" ).toggle(500);
	});
	$('#add_adv').on('click', function(e){
		e.preventDefault();
		var ads_list_count =  Number($("#adv_number").val()) + 1;
		var list_options = '';
		for(var i=1; i<=ads_list_count; i++) {
			list_options += '<option value="' + i + '">' + i + '</option>';
		}
		adv_count =  Number($("#adv_number").val()) + 1;
		$("input[name=adv_number]").val(adv_count);
		$("#ads_container").append('<tr id="adv_box_' + adv_count + '"><th class="qa-form-tall-label th_' + adv_count + '">Advertisment #' + adv_count + '<span class="description">static advertisement</span></th><td class="qa-form-tall-data"><span class="description">upload advertisment image</span><div class="clearfix"></div><input type="file" class="btn btn-success" id="ra_adv_image_' + adv_count + '" name="ra_adv_image_' + adv_count + '"><span class="description">Image Title</span><input class="qa-form-tall-text" type="text" id="adv_image_title_' + adv_count + '" name="adv_image_title_' + adv_count + '" value=""><span class="description">Image link</span><input class="qa-form-tall-text" id="adv_image_link_' + adv_count + '" name="adv_image_link_' + adv_count + '" type="text" value=""><span class="description">Display After this number of questions</span><select id="adv_location_' + adv_count + '" name="adv_location_' + adv_count + '" class="qa-form-wide-select">' + list_options + '</select><button advid="' + adv_count + '" id="advremove" name="advremove" class="qa-form-tall-button pull-right btn" type="submit" onclick="return advremove(this);">Remove This Advertisement</button></td></tr>');
		$('html, body').animate({
			scrollTop: $(".th_" + adv_count).offset().top
		}, 800);
	});
	$('#add_adsense').on('click', function(e){
		e.preventDefault();
		var ads_list_count =  Number($("#adv_number").val()) + 1;
		var list_options = '';
		for(var i=1; i<=ads_list_count; i++) {
			list_options += '<option value="' + i + '">' + i + '</option>';
		}
		adv_count =  Number($("#adv_number").val()) + 1;
		$("input[name=adv_number]").val(adv_count);
		$("#ads_container").append('<tr id="adv_box_' + adv_count + '"><th class="qa-form-tall-label th_' + adv_count + '">Advertisment #' + adv_count + '<span class="description">Google Adsense Code</span></th><td class="qa-form-tall-data"><input class="qa-form-tall-text" id="adv_adsense_' + adv_count + '" name="adv_adsense_' + adv_count + '" type="text" value=""><span class="description">Display After this number of questions</span><select id="adv_location_' + adv_count + '" name="adv_location_' + adv_count + '" class="qa-form-wide-select">' + list_options + '</select><button advid="' + adv_count + '" id="advremove" name="advremove" class="qa-form-tall-button pull-right btn" type="submit" onclick="return advremove(this);">Remove This Advertisement</button></td></tr>');    
		$('html, body').animate({
			scrollTop: $(".th_" + adv_count).offset().top
		}, 800);
	});
	function advremove(e){
		alert(e.attr("advid"));
		e.preventDefault();
			adv_count =  Number($("#adv_remove").val()) - 1;
			$("input[name=adv_number]").val(adv_count);
			$("#ads_container").append('<tr><th class="qa-form-tall-label">Advertisment #' + adv_count + '<span class="description">Google Adsense Code</span></th><td class="qa-form-tall-data"><input class="qa-form-tall-text" id="adv_adsense_' + adv_count + '" name="adv_adsense_' + adv_count + '" type="text" value=""><button id="remove_adv_' + adv_count + '" name="remove_adv_' + adv_count + '" class="qa-form-tall-button pull-right btn" type="submit">Remove This Advertisement</button></td></tr>');    
		$("#ads_container").append('');
	};
	
	$('#add_social').on('click', function(e){
		e.preventDefault();
		var social_list_count =  Number($("#social_count").val()) + 1;
		var list_options = '<option value="upload_file">Upload Social Icon</option>';
		for(var i=1; i <= 10; i++) {
			list_options += '<option value="' + i + '" class="icon-wrench">Icon ' + i + '</option>';
		}
		$("input[name=social_count]").val(social_list_count);
		$("#social_container").append('<tr id="social_box_' + social_list_count + '"><th class="qa-form-tall-label social_th_' + social_list_count + '">Social Link #' + social_list_count + '<span class="description">choose Icon and link to your social profile</span></th><td class="qa-form-tall-data"><span class="description">Social Profile Link</span><input class="qa-form-tall-text" id="social_link_' + social_list_count + '" name="social_link_' + social_list_count + '" type="text" value=""><span class="description">Link Title</span><input class="qa-form-tall-text" type="text" id="social_title_' + social_list_count + '" name="social_title_' + social_list_count + '" value=""><span class="description">Choose Social Icon</span><select id="social_icon_' + social_list_count + '" name="social_icon_' + social_list_count + '" fieldid="' + social_list_count + '" class="qa-form-wide-select social-select">' + list_options + '</select><div class="social_icon_file_' + social_list_count + '"><span class="description">upload Social Icon</span><div class="clearfix"></div><input type="file" class="btn btn-success" id="ra_social_image_' + social_list_count + '" name="ra_social_image_' + social_list_count + '"><button advid="' + social_list_count + '" id="social_remove" name="social_remove" class="qa-form-tall-button pull-right btn" type="submit" onclick="return advremove(this);">Remove This Link</button></td></tr>');
		$('html, body').animate({
			scrollTop: $(".social_th_" + social_list_count).offset().top
		}, 800);
	});
	$( ".social-select" ).change(function() {
		if ($(this).val()==1)
			$('.social_icon_file_' + $(this).attr('sociallistid')).show(500);
		else 
			$('.social_icon_file_' + $(this).attr('sociallistid')).hide(500);
	});
});

