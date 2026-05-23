/**
 * Theme Settings admin: media library and color pickers.
 */
(function ($) {
	'use strict';

	$('.buity-color-picker').wpColorPicker();

	$(document).on('click', '.buity-media-upload', function (e) {
		e.preventDefault();

		var $field = $(this).closest('.buity-media-field');
		var $input = $field.find('input[type="hidden"]');
		var $preview = $field.find('.buity-media-field__preview');
		var $remove = $field.find('.buity-media-remove');
		var frame = wp.media({
			title: 'Select image',
			button: { text: 'Use image' },
			multiple: false,
			library: { type: 'image' },
		});

		frame.on('select', function () {
			var attachment = frame.state().get('selection').first().toJSON();
			$input.val(attachment.id);
			var url = attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url;
			$preview.html('<img src="' + url + '" alt="" />');
			$remove.removeClass('hidden');
		});

		frame.open();
	});

	$(document).on('click', '.buity-media-remove', function (e) {
		e.preventDefault();
		var $field = $(this).closest('.buity-media-field');
		$field.find('input[type="hidden"]').val('');
		$field.find('.buity-media-field__preview').empty();
		$(this).addClass('hidden');
	});
})(jQuery);
