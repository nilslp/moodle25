M.theme_ubertheme.sideblock_stuff = function(Y) {
    
    var transparentBlock = Y.all('body:not(.editing) .block .header h2:contains(Transparent Block)');
    // var transparentBlock = Y.all('body:not(.editing) .block .header h2');

    transparentBlock && transparentBlock.each( function (node) {
		node.ancestor('.block').addClass('transparent-block');
    });

    var calendarBlock = Y.all('body#page-calendar-view .block .header h2:contains(Monthly view)');

    calendarBlock && calendarBlock.each( function (node) {
		node.ancestor('.block').addClass('block_calendar_month');
    });    

    console.log(transparentBlock);

}
