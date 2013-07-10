/** Cycling Banners **/
M.theme_ubertheme_cycling_banners = {};

M.theme_ubertheme_cycling_banners.init = function(Y) {

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
								if (current_banner < list.size() - 1){
										++current_banner;
										node.setStyle('opacity',0);
								}
								node.one('img').setStyle('display','none');
						});
						
				banner_timer = Y.later(banner_shown, null, bannerCycle, true, true);
		
		}
		
		function bannerCycle() {
				var num_banners = banners.length;
				
				banners[current_banner].transition({
						duration: (banner_trans),
						opacity: 0,
						delay: (banner_delay)
				});
				
				// wrap banner index
				++current_banner;
				if (current_banner >= num_banners){
						current_banner = 0;
				} else if (current_banner < 0){
						current_banner = num_banners -1;
				}
				
				banners[current_banner].transition({
						duration: (banner_trans),
						opacity: 1
				});
				
		} // end bannerCycle()

}

/** Feature Slider **/

/*var pattern = /\[\[(.*)\]\]/;
var source = document.getElementById('feature-slider');
var adminsettings = document.getElementById('adminsettings');
var target = document.getElementById('region-main');
var forms = document.getElementById('mform1');
if (source && !adminsettings && !forms) {
		target.innerHTML = target.innerHTML.replace(pattern,'<div id="feature-slider-target"><div class="loading">Building Feature Panel...</div></div>');
}*/

M.theme_ubertheme_feature_slider = {};

M.theme_ubertheme_feature_slider.init = function(Y) {

		feature_slider = Y.one('#feature-slider');
		feature_slider_target = Y.one('#feature-slider-target');
		
		if (!feature_slider || !feature_slider_target) return;
		
		feature_slider_target.append(feature_slider);
		feature_slider_target.one('.loading').addClass('hide');
		feature_slider.removeClass('hide');		

		// Banner timing in miliseconds (1000ms = 1sec)
		var feature_shown = 10000;
		var feature_trans = 2;
		var feature_delay = .25;

		var list, max_height=0, features=[], current_feature=0;
		
		var feature_list = Y.one('#feature-slider ul');
		
		if (feature_list) {
		
				list = feature_list.all('li');
				list && list.each(function (node) {
								features.push(node);
								if (current_feature < list.size() - 1){
										++current_feature;
										node.setStyle('opacity',0);
								}
						});
				
				feature_timer = Y.later(feature_shown, null, featureCycle, true, true);
		
		}
		
		function featureCycle() {
				var num_features = features.length;
				
				features[current_feature].transition({
						duration: (feature_trans),
						opacity: 0,
						delay: (feature_delay)
						},
						function(){
								this.setStyle('display','none');
						}
				);
				
				// wrap banner index
				++current_feature;
				if (current_feature >= num_features){
						current_feature= 0;
				} else if (current_feature < 0){
						current_features = num_features-1;
				}
				
				features[current_feature].setStyle('display','block');
				features[current_feature].transition({
						duration: (feature_trans),
						opacity: 1
				});
				
		} // end featureCycle()
		
}/** Theme Settings Behaviors **/


M.theme_ubertheme_theme_settings = {};

M.theme_ubertheme_theme_settings.init = function(Y) {

		var setting_form = Y.one('body.path-admin-setting form#adminsettings');
		
		if (setting_form) {
				
				setting_form.delegate(
						'hover',
						function() { this.addClass('hover'); },
						function() { this.removeClass('hover'); },
						'h3'
				);
				
				setting_form.delegate(
						'click',
						function() { this.ancestor('.admin-setting-group').toggleClass('show'); },
						'h3'
				);
				
				setting_form.delegate(
						'change',
						function() { this.ancestor('.admin-setting-group').addClass('group-changed'); },
						'.form-item input, .form-item select, .form-item textarea'
				);

		}
				
}