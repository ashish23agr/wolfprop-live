jQuery(function($) {
	console.log('Properties page loaded - initiating AJAX call');
	ajaxSubmit();
	
	function ajaxSubmit(extra='', callback=false) {
		var sortby = $("#sortby").val();
		var filterform;
		
		if (propertyAjax.has_action === 'true') {
			filterform = $('#property_filter').serialize();
		} else {
			filterform = 'action=filter_properties';
		}

		filterform += '&sortby=' + sortby + extra;
		console.log('AJAX URL:', propertyAjax.ajaxurl);
		console.log('AJAX Data:', filterform);
		
		$.ajax({
			type: "get",
			url: propertyAjax.ajaxurl,
			data: filterform,
			success: function(data) {
				console.log('AJAX Success - Data received:', data.substring(0, 200) + '...');
				$('.property_listing').html(data);
				if (callback == true) {
					$('html, body').animate({
						scrollTop: $(".property_listing").offset().top
					}, 1000);
					$(".loading").css("display", "none");
				}
			},
			error: function(xhr, status, error) {
				console.error('AJAX Error:', status, error);
				console.error('Response:', xhr.responseText);
				$('.property_listing').html('<div class="error">Error loading properties. Check console for details.</div>');
			}
		});
	}

	$("#sortby").change(function() {
		var text = '<img style="display: block; margin: 0 auto;" alt="loading" src="' + propertyAjax.template_uri + '/images/ring.gif">';
		$(".property_listing").html(text);
		var sortby = $(this).val();
		ajaxSubmit();
	});

	$('body').on('click', 'a.inactive', function(event) {
		event.preventDefault();
		var paged = $(this).attr("data-paged");
		var tab = $(this).attr("data-tab");
		$(".loading").css("display", "block");
		var extra = "&paged=" + paged + "&tab=" + tab;
		ajaxSubmit(extra, true);
	});
});
