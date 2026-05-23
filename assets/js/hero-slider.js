/**
 * Homepage hero carousel.
 */
(function () {
	'use strict';

	var slider = document.querySelector('.home-hero-slider');
	if (!slider) {
		return;
	}

	var slides = slider.querySelectorAll('.home-hero-slider__slide');
	var dots = slider.querySelectorAll('.home-hero-slider__dot');
	var current = 0;
	var timer;

	function goTo(index) {
		if (!slides.length) {
			return;
		}
		current = (index + slides.length) % slides.length;

		slides.forEach(function (slide, i) {
			slide.classList.toggle('is-active', i === current);
		});

		dots.forEach(function (dot, i) {
			dot.classList.toggle('is-active', i === current);
			dot.setAttribute('aria-selected', i === current ? 'true' : 'false');
		});
	}

	function next() {
		goTo(current + 1);
	}

	function startAutoplay() {
		stopAutoplay();
		if (slides.length > 1) {
			timer = setInterval(next, 5000);
		}
	}

	function stopAutoplay() {
		if (timer) {
			clearInterval(timer);
		}
	}

	dots.forEach(function (dot) {
		dot.addEventListener('click', function () {
			var index = parseInt(dot.getAttribute('data-slide-to'), 10);
			goTo(index);
			startAutoplay();
		});
	});

	slider.addEventListener('mouseenter', stopAutoplay);
	slider.addEventListener('mouseleave', startAutoplay);

	goTo(0);
	startAutoplay();
})();
