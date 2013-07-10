M.theme_foundation.screen_reader = function(Y){

	var remove_collapsed;
	var screen_reader_enabled = Y.one('body').hasClass('screen-reader-enabled');

	if (screen_reader_enabled) {
		remove_collapsed = Y.all('.screen-reader-enabled .block li');

		remove_collapsed && remove_collapsed.each( function (node) {
			node.removeClass('collapsed').setAttribute('aria-expanded', 'true');
		});
	}
}