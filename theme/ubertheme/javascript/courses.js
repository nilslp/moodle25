M.theme_ubertheme_courses = {};

M.theme_ubertheme_courses.init = function(Y) {
    
    var collapse = (typeof collapsecourselist !== 'undefined') ? collapsecourselist : false;
     
    /** Course Category List **/
    if (collapse) {
    
        // Hide all courses
        Y.all('#page-course-index .categorylist')
            .each( function(node) {
                if (node.all('.course').size()) node.addClass('closed');
            });
        
        // Set category title behavior
        var cat_titles = Y.all('#page-course-index .categorylist .category');
        cat_titles.append('<div class="vc"></div>');
        
        // Course Titles
        var course_titles = Y.all('#page-course-index .categorylist .course');
        course_titles.append('<div class="vc"></div>');
        
        // Listeners
        var box = Y.one('#page-course-index .categorybox');
        if (box) {
            box.delegate(
                'hover',
                function(e) { this.addClass('hover'); },
                function(e) { this.removeClass('hover'); },
                '.categorylist .category, .categorylist .course'
            );
            box.delegate(
                'click',
                function(e){
                    if(e.target.ancestor('.categorylist').hasClass('closed')) {
                        e.target.ancestor('.categorylist').removeClass('closed');
                        e.target.ancestor('.categorylist').addClass('open');
                    }
                    else if (e.target.ancestor('.categorylist').hasClass('open')) {
                        e.target.ancestor('.categorylist').addClass('closed');
                        e.target.ancestor('.categorylist').removeClass('open');
                    }
                },
                '.categorylist .category'
            );
        }
    
    }
    else {
    
        // Show all courses
        Y.all('#page-course-index .categorylist').each( function(node) { node.addClass('open-static'); });
            
    }
    /** End Course Category List **/
    
}   