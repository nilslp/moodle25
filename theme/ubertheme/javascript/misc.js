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
		// console.log('Pure Javascript - Width: ' + x);

		if (x <= 340) {
			b.removeClass('narrow').removeClass('normal').removeClass('wide').addClass('mobile');
		} else if (x <= 740) {
			b.removeClass('mobile').removeClass('normal').removeClass('wide').addClass('narrow');
		} else if (x < 1220) {
			b.removeClass('mobile').removeClass('narrow').removeClass('wide').addClass('normal');
		} else if (x >= 1220) {
			b.removeClass('mobile').removeClass('narrow').removeClass('normal').addClass('wide');
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