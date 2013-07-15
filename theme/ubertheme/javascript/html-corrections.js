M.theme_ubertheme.html_corrections = function(Y) {
    
    Y.all('.paging')
        .each(
            function (node) {
                node.setContent(node.get('innerHTML').replace(/\&nbsp\;/gi, '').replace(/[\(\)]/g, ''));
            }
        );
    
}