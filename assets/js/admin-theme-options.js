/**
 * Theme Settings admin: media, colors, repeaters.
 */
(function ($) {
	'use strict';

	var cfg = window.buityThemeSettingsAdmin || {};
	var maxHero = cfg.maxHeroSlides || 10;
	var maxHome = cfg.maxHomeSections || 12;

	$('.buity-color-picker').wpColorPicker();

	function reindexRepeater($container, labelPrefix) {
		$container.children('.buity-repeater__item').each(function (index) {
			var $item = $(this);
			$item.attr('data-index', index);
			$item.find('[name]').each(function () {
				var name = $(this).attr('name');
				if (!name) {
					return;
				}
				$(this).attr(
					'name',
					name.replace(/\[hero_slides\]\[\d+\]/, '[hero_slides][' + index + ']')
						.replace(/\[home_sections\]\[\d+\]/, '[home_sections][' + index + ']')
				);
			});
			$item.find('.buity-repeater__item-head strong').first().text(labelPrefix + ' ' + (index + 1));
		});
	}

	function clearMediaField($field) {
		$field.find('input[type="hidden"]').val('');
		$field.find('.buity-media-field__preview').empty();
		$field.find('.buity-media-remove').addClass('hidden');
	}

	$(document).on('click', '.buity-media-upload', function (e) {
		e.preventDefault();

		var $field = $(this).closest('.buity-media-field');
		var $input = $field.find('input[type="hidden"]');
		var $preview = $field.find('.buity-media-field__preview');
		var $remove = $field.find('.buity-media-remove');
		var frame = wp.media({
			title: cfg.selectImage || 'Select image',
			button: { text: cfg.useImage || 'Use image' },
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
		clearMediaField($(this).closest('.buity-media-field'));
	});

	$('#buity-add-hero-slide').on('click', function () {
		var $wrap = $('#buity-hero-slides');
		if ($wrap.children('.buity-hero-slide').length >= maxHero) {
			return;
		}
		var $clone = $wrap.children('.buity-hero-slide').first().clone();
		$clone.find('input[type="text"], input[type="url"]').val('');
		clearMediaField($clone.find('.buity-media-field'));
		$wrap.append($clone);
		reindexRepeater($wrap, cfg.heroLabel || 'Slide');
	});

	$('#buity-add-home-section').on('click', function () {
		var $wrap = $('#buity-home-sections');
		if ($wrap.children('.buity-home-section').length >= maxHome) {
			return;
		}
		var $clone = $wrap.children('.buity-home-section').first().clone();
		$clone.find('input[type="text"], input[type="url"]').val('');
		$clone.find('input[type="number"]').val('10');
		$clone.find('select.buity-home-section-source').val('popular');
		$clone.find('.buity-home-section-category-row').addClass('hidden');
		$wrap.append($clone);
		reindexRepeater($wrap, cfg.sectionLabel || 'Section');
	});

	$(document).on('click', '.buity-repeater__remove', function (e) {
		e.preventDefault();
		var $item = $(this).closest('.buity-repeater__item');
		var $wrap = $item.parent();
		if ($wrap.children('.buity-repeater__item').length <= 1) {
			$item.find('input[type="text"], input[type="url"]').val('');
			$item.find('input[type="number"]').val('10');
			clearMediaField($item.find('.buity-media-field'));
			return;
		}
		$item.remove();
		if ($wrap.is('#buity-hero-slides')) {
			reindexRepeater($wrap, cfg.heroLabel || 'Slide');
		} else {
			reindexRepeater($wrap, cfg.sectionLabel || 'Section');
		}
	});

	$(document).on('change', '.buity-home-section-source', function () {
		var $row = $(this).closest('.buity-home-section').find('.buity-home-section-category-row');
		if ($(this).val() === 'category') {
			$row.removeClass('hidden');
		} else {
			$row.addClass('hidden');
		}
	});
})(jQuery);
