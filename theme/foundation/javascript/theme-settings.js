M.theme_foundation.theme_settings = function(Y) {

    var setting_form = Y.one('body.path-theme form.theme-settings, body.path-admin-setting form#adminsettings');
    
    if (setting_form) {
        
        setting_form.delegate(
            'hover',
            function() { this.addClass('hover'); },
            function() { this.removeClass('hover'); },
            'legend, h3'
        );
        
        setting_form.delegate(
            'click',
            function() { this.ancestor('fieldset').toggleClass('show'); },
            'legend'
        );
        
        setting_form.delegate(
            'click',
            function() { this.ancestor('.admin-setting-group').toggleClass('show'); },
            'h3'
        );
        
        setting_form.delegate(
            'change',
            function() { this.ancestor('fieldset').addClass('group-changed'); },
            '.fitem input, .fitem select, .fitem textarea'
        );
        
        setting_form.delegate(
            'change',
            function() { this.ancestor('.admin-setting-group').addClass('group-changed'); },
            '.form-setting input, .form-setting select, .form-setting textarea'
        );
            
        setting_form.all('.fitem_fcolorpicker').each(
            function() {
                M.util.init_colour_picker(Y, this.getAttribute('id'));
            });

    }

}