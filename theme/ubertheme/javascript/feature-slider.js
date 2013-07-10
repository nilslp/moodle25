M.theme_ubertheme.feature_slider = function(Y) {

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
		
}