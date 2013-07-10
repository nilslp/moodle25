M.theme_foundation.sideblock_stuff = function(Y) {
    
    Y.all('body:not(.editing) .block .header h2:contains(Transparent Block)').each( function(node) {
		node.ancestor('.block').addClass('transparent-block');
    });

    Y.all('body#page-calendar-view .block .header h2:contains(Monthly view)').each( function(node) {
		node.ancestor('.block').addClass('block_calendar_month');
    });    
    
}
