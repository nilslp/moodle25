M.theme_ubertheme.tabs = function(Y) {
    
        var tabtree = Y.one('.tabtree');
        
        if (tabtree) {
        
            var w, r0, a_p=20;
            var pattern = /tabrow([0-9]+)/;            
            var list = tabtree.all('ul');
            
            w = parseInt(tabtree.getComputedStyle('width'),10);
            
            function fixTabs(node) {
                if (pattern.exec(node.get('className'))){
                    node.setStyle('width',tabtree.getComputedStyle('width'));
                    var items = node.all('> li');
                    var numtabs = items && items.size();
                    var width = numtabs && Math.floor(w/numtabs);
                    var links = node.all('> li > a');
                    links && links.setStyle('width',width-a_p+'px');                    
                }
            }
            
            list && list.each(fixTabs);
        
        }     
        
}