$(document).ready(function(){
  $('#activitylist').on('click', function(e){
    e.preventDefault();
    
		$.ajax({
            data: {
				cs_ajax: true,
				cs_ajax_html: true,
                action: 'activitylist',
            },
            dataType: 'html',
            context: this,
            success: function (response) {
				$('#activity-dropdown-list').html(response);
            },
        });
  });
  $('#messagelist').on('click', function(e){
    e.preventDefault();
    
		$.ajax({
            data: {
				cs_ajax: true,
				cs_ajax_html: true,
                action: 'messagelist',
            },
            dataType: 'html',
            context: this,
            success: function (response) {
				$('#message-dropdown-list').html(response);
            },
        });
  });
});
