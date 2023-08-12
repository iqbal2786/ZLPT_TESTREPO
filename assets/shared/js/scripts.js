jQuery( document ).ready(function ( $ ) {
	init_parallax();
} );

function init_properties_swiper() {
	if ( $('.zl-properties-carousel-wrapper').length > 0 ) {
		new Swiper( '.zl-properties-carousel-wrapper', {
			effect: 'coverflow',
			grabCursor: false,
			centeredSlides: true,
			slidesPerView: 'auto',
			loop: true,
			slidesPerView: 1,
			navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			},
			coverflowEffect: {
				rotate: 30,
				stretch: 0,
				depth: 100,
				modifier: 1,
				slideShadows: false
			},
			breakpoints: {
				768: {
					slidesPerView: 2,
				},
				1024: {
					slidesPerView: 4,
				},
			}
		} );
	}
}

function init_posts_swiper() {
	if ( $('.zl-posts-carousel-wrapper').length > 0 ) {
		new Swiper(".zl-posts-carousel-wrapper", {
			spaceBetween: 30,
			effect: "fade",
			loop: true,
			navigation: {
				nextEl: ".swiper-button-next",
				prevEl: ".swiper-button-prev",
			},
			pagination: {
				el: ".swiper-pagination",
				clickable: true,
			},
		});
	}
}

function init_parallax() {
	if ( $('.jarallax').length >= 1 ) {
		jarallax( document.querySelectorAll(".jarallax") );
		jarallax( document.querySelectorAll(".jarallax-keep-img"), {
			keepImg: true,
		} );
	}
}
