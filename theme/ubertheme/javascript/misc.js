M.theme_ubertheme.misc = function(Y) {
	
	function getWindowSize() {

		var x = 0;

		if (self.innerHeight) {
			x = self.innerWidth;
		} else if (document.documentElement && document.documentElement.clientHeight) {
			x = document.documentElement.clientWidth;
		} else if (document.body) {
			x = document.body.clientWidth;
		}
		return x;

	}

	function addWidthClass() {

		var x = getWindowSize();
		var b = Y.one('body');

		var mobile_menu = Y.all('#custommenu li, #custommenu li a');
		// console.log('Pure Javascript - Width: ' + x);
		
		if (x <= 780) {
			b.removeClass('narrow').removeClass('normal').removeClass('wide').addClass('mobile');
			mobile_menu && mobile_menu.removeClass('yui3-menu-label').addClass('mobile');
		} else if (x <= 980) {
			b.removeClass('mobile').removeClass('normal').removeClass('wide').addClass('narrow');
			mobile_menu && mobile_menu.addClass('yui3-menu-label').removeClass('mobile');
		} else if (x < 1220) {
			b.removeClass('mobile').removeClass('narrow').removeClass('wide').addClass('normal');
			mobile_menu && mobile_menu.addClass('yui3-menu-label').removeClass('mobile');
		} else if (x >= 1220) {
			b.removeClass('mobile').removeClass('narrow').removeClass('normal').addClass('wide');
			mobile_menu && mobile_menu.removeClass('yui3-menu-label').addClass('mobile');
		}

		var mobile = b.hasClass('mobile');
		var narrow = b.hasClass('narrow');
		var normal = b.hasClass('normal');
		var wide = b.hasClass('wide');

		var debugValue;

		if (mobile) {
			debugValue = 'Mobile: ';
		} else if (narrow) {
			debugValue = 'Narrow: ';
		} else if (normal) {
			debugValue = 'Normal: ';
		} else {
			debugValue = 'Wide: ';
		}

		// console.log(debugValue + x);

	}

	addWidthClass();

	Y.one('win').on('resize', function(e) {

		addWidthClass();

	});


}