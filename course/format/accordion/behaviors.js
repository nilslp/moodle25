M.course_accordion_format = function (Y) {

    var accordion = Y.one('#page-course-view-accordion .accordion-topics');
    
    if (accordion) {

        button_txt_expand = 'Expand All';
        button_txt_collapse = 'Collapse All';

        var sections = accordion.all('dl:not(.course-summary):not(.collapsed)').size(),
            section_1 = accordion.one('dl#section-1');

        if (sections == 0) {
            section_1 && section_1.removeClass('collapsed');
            // accordion.one('#section-1').removeClass('collapsed');
        }

        var dts = accordion.all('dt');
        dts && dts.on('click',
            function (e) {
                if (e.currentTarget.ancestor('dl').getAttribute('id') == 'section-0') { return; }
                e.currentTarget.ancestor('dl').toggleClass('collapsed');
            }
        );
        var actions = accordion.all('.actions a');
        actions && actions.on('click', function (e) { e.stopPropagation(); });

        var raas = accordion.all('ul.section li');
        raas && raas.on(
            'hover',
            function(e) { this.addClass('hover'); },
            function(e) { this.removeClass('hover'); }
            );

        var sect0 = accordion.one('#section-0');
        if (sect0) {
            sect0.append(Y.Node.create('<button class="toggle">'+button_txt_expand+'</button>'));
        } else {
            accordion.prepend(Y.Node.create('<button class="toggle">'+button_txt_expand+'</button>'));
        }
        accordion && accordion.delegate(
            'click',
            function(e) {
                this.toggleClass('open');
                sections = Y.all('#page-course-view-accordion .accordion-topics .section:not(#section-0)');
                if (this.hasClass('open')) {
                    e.target.set('text', button_txt_collapse);
                    sections.each( function(node) { node.removeClass('collapsed'); });
                }
                else {
                    e.target.set('text', button_txt_expand);
                    sections.each( function(node) { node.addClass('collapsed'); });
                }
            },
            'button.toggle'
            );

    }

}