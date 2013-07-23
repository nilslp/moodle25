M.theme_ubertheme.cycling_banners = function(Y) {

// Banner timing in miliseconds (1000ms = 1sec)
var banner_shown = 15000;
var banner_trans = 1;
var banner_delay = .5;

var list, max_height=0, banners=[], current_banner=0;

var banner_list = Y.one('#page-header .banner ul');

if (banner_list) {

	list = banner_list.all('li');
	list && list.each(function (node) {
		banners.push(node);
		if (current_banner < (list.size() - 1)) {
			++current_banner;
			// node.setStyle('opacity', 0);
		}
		// node.one('img').setStyle('display','none');
	});
	// lastItem = banner_list.all('li:last-child');
	// lastItem && lastItem.setStyle('opacity', 1).removeClass('hide');

	banner_timer = Y.later(banner_shown, null, bannerCycle, true, true);

}

function bannerCycle() {
	var num_banners = banners.length;

	banners[current_banner].transition({
		//display: 'none',
		duration: (banner_trans),
		opacity: 0,
		delay: (banner_delay),
		position: 'absolute'
	});

	//.addClass('hide') after delay of 1
	// Y.later(1000, banners[current_banner], function() {
	// 	banners[current_banner].addClass('hide');
	// });

	// var bNode = banners[current_banner];
	// bNode.plug(Y.Plugin.NodeFX, {
	// 	to: {
	// 		backgroundColor: '#BADA55',
	// 		cssClass: 'hide',
	// 		delay: (banner_delay),
	// 		duration: (banner_trans),
	// 		opacity: 0
	// 	}
	// });
	// bNode.fx.run();

	// banners[current_banner].setStyle('display', 'block');

	// wrap banner index
	++current_banner;
	if (current_banner >= num_banners){
		current_banner = 0;
	} else if (current_banner < 0){
		current_banner = num_banners -1;
	}

	banners[current_banner].transition({
		//display: 'block',
		duration: (banner_trans),
		opacity: 1,
		position: 'relative'
	});

	//.removeClass('hide') after delay of 1
	// Y.later(1000, banners[current_banner], function() {
	// 	banners[current_banner].removeClass('hide');
	// });

	} // end bannerCycle()

}