(function($){
	'use strict';

	$('.mkgType').change(changeType);

	function changeType(event) {
		var elm = event.target,
			$parent = $(elm).parent(),
			$mkgFranchiseId = $parent.find('.mkgFranchiseId'),
			$mkgExternalLink = $parent.find('.mkgExternalLink'),
			$mkgSrc = $parent.find('.mkgSrc'),
			$uploadBtn = $parent.find('.uploadBtn'),
			defaultImageSrcSize = $mkgSrc.attr('data-imageSrcSize'),
			defaultVideoSrcSize = $mkgSrc.attr('data-videoSrcSize'),
			defaultExtImageSrcSize = $mkgSrc.attr('data-extImageSrcSize'),
			defaultImagePlaceholder = $mkgSrc.attr('data-imagePlaceholder'),
			defaultVideoPlaceholder = $mkgSrc.attr('data-videoPlaceholder');
		if($(elm).val() === "image") {
			$mkgExternalLink.hide();
			$mkgFranchiseId.show();
			$uploadBtn.show();
			$mkgExternalLink.attr("value", "");
			$mkgSrc.attr({"size": defaultImageSrcSize, "placeholder": defaultImagePlaceholder, "value": ""});
		}
		else if($(elm).val() === "extImage") {
			$mkgFranchiseId.hide();
			$mkgExternalLink.show();
			$uploadBtn.show();
			$mkgFranchiseId.attr("value", "");
			$mkgSrc.attr({"size": defaultExtImageSrcSize, "placeholder": defaultImagePlaceholder, "value": ""});
		}
		else {
			$mkgFranchiseId.hide();
			$mkgExternalLink.hide();
			$uploadBtn.hide();
			$mkgFranchiseId.attr("value", "");
			$mkgExternalLink.attr("value", "");
			$mkgSrc.attr({"size": defaultVideoSrcSize, "placeholder": defaultVideoPlaceholder, "value": ""});
		}
	};

	$('.mkgType').parents('tbody').addClass('sortable news_section');
	$('.lnwsText').parents('.form-table > tbody').addClass('sortable');

	$('.sortable').sortable({
		placeholder: "sortable-placeholder",
		connectWith: ".sortable",
		start: function(e, ui){
			ui.placeholder.height(ui.item.height());
		}
	});
	$('.sortable').sortable({
		update: function( event, ui ) {
			$(event.target).find('> tr > td').each(function(key, elem) {
				$(elem).parents('tr.ui-sortable-handle').attr("id", key);
				$(elem).children().each(updateFields);
			});
		}
	});

	function updateFields(key,elm) {
		var id = $(elm).parents('tr[id]').attr('id'),
			name = $(elm).attr('name');
		if(typeof name !== 'undefined') {
			$(elm).attr('name', name.replace( /(.+)\[[\d]+\](.+)/, "$1["+id+"]$2"));
		}
		else{
			$(elm).find('td *').each(updateFields);
		}
	}

	$('.mkgType').each(function(key,elem){
		$(elem).parents('tr').addClass('ui-state-default').attr('id',key);
	});

	$('.lnwsText').each(function(key,elem){
		$(elem).parents('.ui-sortable-handle').addClass('ui-state-default').attr('id',key);
	});

	//Add break lines as separetor after to News section.
	$('.news_section').parent().after('<div class="section-separator"><br/><br/><br/></div>');

	//Upload image action button
	$('.uploadBtn').click(function(e) {
		e.preventDefault();
		var setImageUrl = function(value) {
				var imageUrl = value.replace('http', 'https');
				$(e.target).parent().find('input.uploadImage').val(imageUrl);
			},
			image = wp.media({
			title: 'Upload Image',
			multiple: false // True if you want to upload multiple files at once.
		})
		.open()
		.on('select', function(){
			// Return the selected image from the Media Uploader (the result is an object).
			var uploaded_image = image.state().get('selection').first();
			// Convert uploaded_image to a JSON object doing it more easy to handle.
			var image_url = uploaded_image.toJSON().url;
			// Set the url value to the proper input field.
			setImageUrl(image_url);
		});
	});
})(jQuery);
