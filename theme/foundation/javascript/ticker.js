M.theme_foundation.ticker = function(Y) {
    
    var ticker = Y.one('#ticker');
    if (!ticker) {   
        // no ticker!
        return;
    }
    
    var ndlist = [];
    var cfg = {
        msg_active : 0,
        msg_width : '',
        h2_width : 0,
        controls_width : 0, 
        avail_width : 0,
        ticker_width : 0,
        ticker_height : 0,
        ticker_border : 0,
        actual_height : 0,
        ticker_timer : false, 
        scroll_timer : false,
        scroll_delay_count : 0,
        scroll_done : true,
        cycle_speed : 5000, 
        scroll_speed : 25,
        scroll_delay : 0,   
        msg_num : 0,
        msgs : [],
        auto_animate : true
    }

    ndlist = Y.all('#ticker ul li');
    
    cfg.ticker_height = ticker.get('offsetHeight');
    cfg.ticker_border = parseInt(ticker.getStyle('borderBottomWidth'), 10);
    cfg.actual_height = cfg.ticker_height - cfg.ticker_border;
    if (cfg.actual_height < 0) {
        // Prevent IE8 error
        cfg.actual_height = 0;
    };

    ndlist && ndlist.setStyle('lineHeight',cfg.actual_height+'px');
    
    ndlist && ndlist.each(function (node) {
        node.hide(true);
        cfg.msgs.push(node);
    });

    if (!cfg.msgs.length){
        // no messages
        return;
    }

    // show the first message
    cfg.msgs[cfg.msg_num].show();
    cfg.msgs[cfg.msg_num].addClass('active');

    // add dragging feature

    var del = new Y.DD.Delegate({
        container: '#ticker',
        nodes: 'li'
    });

    // add controls
    ticker
        .one('.wrapper')
        // .append('<div class="controls"><button id="ticker-timer" class="stop"></button><button class="back"></button><button class="forward"></button></div><div class="more"></div>')
        .append('<div class="controls"><button class="forward"></button></div><div class="more"></div>')
        .removeClass('hide');

    ticker.delegate(
        'click',
        tickerCtrl,
        '.controls button'
    );
        
    ticker.delegate(
        'hover',
        function () {
            tickerScrollPause();
            tickerCycleStop();
        }, 
        function () {
            if (cfg.scroll_done) {
                tickerCycleStart();
            } else {
                tickerSetScrollTimer(); 
            }
        },
        'ul'
    );

    del.on(
        'drag:drag',
        function(e) {
            // tickerDragPinX(e,this);
            tickerScrollPause();
            tickerCycleStop();
        }
    );
    del.after(
        'drag:align',
        function(e) {
            tickerAlignPinX(e,this);
        }
    );
    del.on(
        'drag:end', 
        function (e) {
            if (cfg.scroll_done) {
                tickerCycleStart();
            } else {
                tickerSetScrollTimer(); 
            }
        }
    );
    
    // start the ticker
    tickerInit();

    
    if (ndlist.size() < 2) {
        tickerCycleStop();
    }
    
    function tickerInit() {    
        // calc sizes
        var h2 = ticker.one('h2');
        h2 && (cfg.h2_width = h2.get('offsetWidth'));
        cfg.controls_width = ticker.one('.controls').get('offsetWidth');
        cfg.ticker_width = ticker.get('offsetWidth');
        ndlist.setStyle('left',cfg.h2_width+'px');
        
        // calc scroll delay
        cfg.scroll_delay = parseInt( cfg.cycle_speed / cfg.scroll_speed ); 
        
        tickerCycle();
        tickerSetCycleTimer();
    }
    
    // Cycling
    
    function tickerCycleStart() { 
        // Y.one('#ticker .controls #ticker-timer').addClass('stop');
        tickerSetCycleTimer(); 
        cfg.auto_animate = true;
    } 
    
    function tickerCycleStop() { 
        // Y.one('#ticker .controls #ticker-timer').removeClass('stop');
        cfg.auto_animate = false;
    } 
    
    function tickerSetCycleTimer() {
        if (!cfg.ticker_timer) {
            cfg.ticker_timer = Y.later(cfg.cycle_speed, null, tickerCycle, true, true);
        }
    } 
    
    // Scrolling
    
    function tickerScrollStop() {
        cfg.scroll_done = true;
    } 
    
    function tickerScrollPause() {
        if (cfg.scroll_timer) {
            cfg.scroll_timer.cancel();
            delete cfg.scroll_timer;
        }
    } 
    
    function tickerSetScrollTimer() {
        if (!cfg.scroll_timer) {
            cfg.scroll_timer = Y.later(cfg.scroll_speed, null, tickerSlide, cfg, true);
        }
    } 
            
    function tickerCtrl(e) {    
        e.stopPropagation();        
        tickerScrollStop();
        tickerCycleStop();        
        if (this.hasClass('forward')) { 
            tickerCycle(false); 
            return; 
        } else if (this.hasClass('back')) { 
            tickerCycle(false,'back'); 
            return; 
        } else if (this.hasClass('stop')) { 
            this.removeClass('stop'); 
            return; 
        } else {
            if (cfg.scroll_done) { 
                tickerCycle(false); 
                tickerCycleStart(); 
            } else { 
                tickerSetScrollTimer(); 
            }
            this.addClass('stop');
            return;
        }
        
    } 
    
    function tickerCycle( d, dir ){    
        var numMsgs = cfg.msgs.length;
        var nd = {};
        dir = dir || 'forward';
        
        // can be paused
        if (d && !cfg.auto_animate) {
            return;
        }
        
        // hide old message
        nd = cfg.msgs[cfg.msg_num];
        nd.hide(true);
        nd.removeClass('active');
        
        // Cycle messages
        d && 'forward' == dir ? ++cfg.msg_num : --cfg.msg_num;
        
        // wrap message counter
        if (cfg.msg_num >= numMsgs) {
            cfg.msg_num = 0;
        } else if (cfg.msg_num < 0) {
            cfg.msg_num = numMsgs - 1;
        }            
        
        // show new message
        nd = cfg.msgs[cfg.msg_num];
        nd.show(true);
        nd.addClass('active');
        nd.setStyle('left',cfg.h2_width+'px');
                
        // Scroll message if it's too wide          
//        cfg.msg_width = nd.one('span').get('offsetWidth');
        cfg.msg_width = nd.get('offsetWidth');
        
        cfg.avail_width = cfg.ticker_width - (cfg.h2_width + cfg.controls_width);
        
        if (cfg.msg_width >= cfg.avail_width) {
            tickerScroll();
        }        
    } 
    
    function tickerScroll() {    
        cfg.scroll_delay_count = 0;
        cfg.scroll_done = false;
        
        // pause the current cycle
        tickerCycleStop();
        
        // start scroll sequence
        tickerSetScrollTimer();    
    } 
    
    function tickerSlide() {   
        // if finished, do nothing
        if (cfg.scroll_done) {
            return;
        }
        
        var nd = {};
        var targetX = 0;
        if (cfg.scroll_delay_count > cfg.scroll_delay) {
            nd = cfg.msgs[cfg.msg_num];
            targetX = parseInt(nd.getStyle('left').replace(/px/g,'')) - 2;
            nd.setStyle('left',targetX+'px'); 
            if (targetX <= parseInt(cfg.msg_width*-1) + (cfg.avail_width*0.1)) { 
                tickerScrollStop(); 
                tickerCycle(true); 
                tickerCycleStart(); 
            }
        } else {
            cfg.scroll_delay_count++;
        }
        
    }

    function tickerAlignPinX(e,n) {
        e.target.actXY[1] = (e.target.startXY[1] - e.target.deltaXY[1]);
    }
    
}