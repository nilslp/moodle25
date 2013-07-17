M.theme_ubertheme.toggle_course_list = function(Y) {

    var page_course_index,
        collapseable = LP.collapsecourselist,
        courselist = LP.courselist,
        cn_body,
        regioncontent,
        cat_list,
        toggle,
        az_list,
        az_list_collapseable_button,
        last_init_char = '',
        controls;

    // Should only run on the main course list page
    page_course_index = Y.one('body#page-course-index');
    if (page_course_index) {

        collapseable = parseInt(collapseable);

        // Apply collapseable class to the body tag for script reference
        cn_body = (collapseable) ? 'course-list-collapseable' : 'course-list-static';
        Y.one('body').addClass(cn_body);

        regioncontent = page_course_index.one('.region-content');
        cat_list = page_course_index.one('.region-content .course_category_tree');
        toggle = Y.Node.create('<div class="btn toggle-view">'+M.util.get_string('course-list-show-az','theme_ubertheme')+'</div>');
        az_list = Y.Node.create('<div id="btn az-course-list" class="hide"><ul></ul></div>');

        // Add az-list/cat-list toggle
        regioncontent.insert(toggle, cat_list);

        // Append AZ-list to container
        for (var i=0; i<courselist.length; i++) {
            var html, course, this_init_char, cn_set;
            course = courselist[i];
            cn = (course.visible === 0) ? ' dimmed' : '';
            s_cn = (collapseable === 1) ? ' collapsed' : '';
            this_init_char = course.sortname.charAt(0).toUpperCase();

            if (last_init_char.toUpperCase() != this_init_char) {
                last_init_char = this_init_char;
                cn_set = (last_init_char == '#') ? 'Misc' : last_init_char;
                section_exists = az_list.one('ul li.char-'+cn_set.toLowerCase());
                section_exists || az_list.one('ul').append('<li class="char-'+cn_set.toLowerCase()+s_cn+' section"><div class="label">'+cn_set+'</div><ul class="courses"></ul></li>')
            }

            html = '<li class="course"><a href="view.php?id=' + course.id + '" class="'+cn+'">' + course.fullname + '</a></li>';
            az_list.one('li.char-'+cn_set.toLowerCase()+' ul').append(html);
        }

        if (collapseable) {
            if (collapseable === 1) {
                 az_list_collapse_button = Y.Node.create('<div class="btn az-accordian expand"><span>'+M.util.get_string('course-list-expand-all','theme_ubertheme')+'</span></div>');
            }
            else if (collapseable === 2) {
                 az_list_collapse_button = Y.Node.create('<div class="btn az-accordian"><span>'+M.util.get_string('course-list-collapse-all','theme_ubertheme')+'</span></div>');
            }
            az_list.prepend(az_list_collapse_button);
        }

        // Move the course search below the Cat-List and AZ-list
        regioncontent.insert(az_list, Y.one('#coursesearch'));

        page_course_index.all('.toggle-view').on("click", function (e) {
            if(cat_list.hasClass('hide')) {
                cat_list.removeClass('hide');
                az_list.addClass('hide');
                toggle.setContent(M.util.get_string('course-list-show-az','theme_ubertheme'));
            }
            else {
                toggle.setContent(M.util.get_string('course-list-show-cat','theme_ubertheme'));
                az_list.removeClass('hide');
                cat_list.addClass('hide');
            }
        });

        // Move the collapse/expand controls to the top of the category box
        controls = page_course_index.one('.region-content .course_category_tree .controls');
        controls && page_course_index.one('.region-content .course_category_tree').removeChild(controls);
        all_cats = page_course_index.all('.course_category_tree .category');

        // Set collapse/expand display according to theme setting
        if (collapseable === 0) {
            all_cats.removeClass('collapsed');
        }
        else if (collapseable === 1) {
            page_course_index.one('.region-content .course_category_tree').prepend('<div class="btn cat-accordian expand"><span>'+M.util.get_string('course-list-expand-all','theme_ubertheme')+'</span></div>');
            all_cats.addClass('collapsed');
        }
        else if (collapseable === 2) {
            page_course_index.one('.region-content .course_category_tree').prepend('<div class="btn cat-accordian"><span>'+M.util.get_string('course-list-collapse-all','theme_ubertheme')+'</span></div>');
            all_cats.removeClass('collapsed');
        }

        // Category Behaviors
        cat_accordian = page_course_index.one('.course_category_tree .cat-accordian');
        cat_accordian && cat_accordian.on('click',
            function() {
                all_cats = page_course_index.all('.course_category_tree .category');
                if (this.hasClass('expand')) {
                    this.removeClass('expand');
                    this.one('span').set('text',M.util.get_string('course-list-collapse-all','theme_ubertheme'));
                    all_cats.removeClass('collapsed');
                }
                else {
                    this.addClass('expand');
                    this.one('span').set('text',M.util.get_string('course-list-expand-all','theme_ubertheme'));
                    all_cats.addClass('collapsed');
                }
             }
        );

        // A-Z List Behaviors
        az_accordian = page_course_index.one('#az-course-list .az-accordian');
        az_accordian && az_accordian.on('click',
            function() {
                all_sections = page_course_index.all('#az-course-list .section');
                if (this.hasClass('expand')) {
                    this.removeClass('expand');
                    this.one('span').set('text',M.util.get_string('course-list-collapse-all','theme_ubertheme'));
                    all_sections.removeClass('collapsed');
                }
                else {
                    this.addClass('expand');
                    this.one('span').set('text',M.util.get_string('course-list-expand-all','theme_ubertheme'));
                    all_sections.addClass('collapsed');
                }
             }
        );
        
        regioncontent.all('.course_info a').on('click',
            function(e) {
                e.preventDefault();
                name = 'info';
                url = e.currentTarget.getAttribute('href');
                specs = 'width=400,height=300,location=0,menubar=0,status=0,titlebar=0,scrollbars=1';
                window.open(url,name,specs);
            }
        );

    }
}
