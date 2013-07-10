/** Theme Settings Behaviors **/
M.theme_ubertheme_theme_settings = {};

M.theme_ubertheme_theme_settings.init = function(Y) {

		var setting_form = Y.one('body.path-admin-setting form#adminsettings, body#page-theme-ubertheme-admin form#adminsettings');
		
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