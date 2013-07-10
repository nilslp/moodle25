M.theme_foundation.course_list = function(Y) {
    
    var collapseable = Y.one('body').hasClass('course-list-collapseable');
    var tree = Y.one('#page-course-index .course_category_tree');
    if (!tree) { 
        return; 
    }
    
    collapseable && tree.all('.category_label').each(function(n) {
        var href = n.one('.category_link').getAttribute('href');
        n.append('<a class="jumpto hide" href="'+href+'">'+M.util.get_string('course-list-goto-cat', 'theme_foundation')+'</a>');
        n.one('.category_link').removeAttribute('href');
    });
    
    collapseable && tree.delegate(
        'mousedown', 
        function(e) {  
            e.preventDefault(); 
            this.ancestor('.category').toggleClass('collapsed'); 
        },
        'a.category_link'
    );
   
    collapseable && tree.delegate(
        'mouseenter',
        function(e) { this.siblings('.jumpto').removeClass('hide'); },
        'a.category_link'
    );
    
    collapseable && tree.delegate(
        'mouseleave',
        function(e) { this.one('.jumpto').addClass('hide'); },
        '.category_label'
    );

    tree.delegate(
        'hover',
        function(e) { this.addClass('hover'); },
        function(e) { this.removeClass('hover'); },
        '.category_label, .course'
    );
    
    if (!collapseable) {
        Y.all('.category.with_children .category_label').detach('click');
        tree.delegate(
            'click',
            function(e) { 
                e.stopPropagation(); 
            },
            '.category.with-children .category_label'
        );
    }
        

    var az_list = Y.one('#az-course-list');
    if (az_list) {
        
        az_list.delegate(
            'hover',
            function(e) { this.addClass('hover'); },
            function(e) { this.removeClass('hover'); },
            '.course, .section .label'
        );
            
        
        collapseable && az_list.delegate(
            'click',
            function(e) { this.ancestor('.section').toggleClass('collapsed'); },
            '.section .label'
        );
    }
       
}
