M.theme_ubertheme.paging = function(Y) {
     
    // Paging
    Y.all('.paging a')
        .on(
            'hover',
            function(e) {
                ancestor_li = e.target.ancestor('li');
                if (!ancestor_li.hasClass('disabled')) ancestor_li.addClass('hover');
            },
            function(e) {
                e.target.ancestor('li').removeClass('hover');
            }
        );
    
    Y.all('.paging a')
        .on(
            'click',
            function(e) {
            }
        );
    
}