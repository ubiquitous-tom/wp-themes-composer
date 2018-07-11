jQuery(document).on('carousel_pagination.add_new_item_image_url', function(event, image_url, value) {
	var new_image_url = image_url;
	if (value.image_h) {
		new_image_url = image_url.replace(value.franchiseID + '_avatar', value.image_h);
	}
	// console.log('add_new_item_image_url', image_url, value, new_image_url)
	return new_image_url;
});

jQuery(document).on('carousel_pagination.second_page_image_url', function(event, image_url, value) {
	var new_image_url = image_url;
	if (value.image_h) {
		new_image_url = image_url.replace('{{ID}}_avatar', value.image_h);
	}
	// console.log('second_page_image_url', image_url, value, new_image_url);
	return new_image_url;
});
