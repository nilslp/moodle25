M.theme_foundation.support_widget = function(Y) {
    
    if (Y.one('#support-widget')) {
        
        // Optional parameters can be sent into the BrowserFitness object
        var o = {};
        // o.runtest = {'popup': false};
        // o.minreqs = {'verFirefox':11};
        // o.diagnostic = true;
                
        var support_widget = Y.one('#support-widget');
        var autorun_widget = (support_widget.getAttribute('data-autorun') == 'true');
        
        var test_data = false;
        var cookie = Y.Cookie.get('dle_support');

        if (!cookie) {
            if (autorun_widget) { initTest(o); } else { setBehaviors(); }
        }
        else {
            if (o && o.diagnostic) {
                if (autorun_widget) { initTest(o); } else { setBehaviors(); }
            }
            else {
                test_data = Y.JSON.parse(cookie);
                postTest();
            }
        }

    }
    
    function initTest(o) {
        if (!o) var o = {};
        BrowserFitness.init(o);
        test_data = BrowserFitness;
        popDoneTest();
    } // end initTest()
    
    function popDoneTest() {
        
        if (test_data.popup && !test_data.popup.done) {
            t2 = setTimeout(popDoneTest, 200);
        }
        else {
            postTest();
            if (!cookie) supportCookie();
        }
    }
    
    function supportCookie() {
        var t = {};
        t.browser = {};
        t.browser.name = test_data.browser.name;
        t.browser.version = test_data.browser.version;
        t.browser.os = test_data.browser.os;
        t.browser.passed = test_data.browser.passed;
        t.browser.useragent = test_data.browser.useragent;
        t.flash = {};
        t.flash.passed = test_data.flash.passed;
        t.flash.version = test_data.flash.version;
        t.popup = {};
        t.popup.allow = test_data.popup.allow;
        t.popup.done = test_data.popup.done;
        t.viewport = {};
        t.viewport.height = test_data.viewport.height;
        t.viewport.width = test_data.viewport.width;
        try {
            var json = Y.JSON.stringify(t);
        }
        catch (e) {}
        var tld = document.domain.substr(document.domain.indexOf('.'));
        Y.Cookie.set('dle_support', json, {'path': "/", 'domain':tld});
    } // end supportCookie()
    
    function postTest() {
            
        // Javascript
        var obj_js = Y.one('#support-widget .js').ancestor('li');
        if (o.diagnostic) {
            obj_js.addClass('failed').removeClass('unknown').removeClass('passed');
            obj_js.setAttribute('title','Failed - Javascript is required for this site to work properly.');
        }
        else {
            obj_js.addClass('passed').removeClass('unknown').removeClass('failed');
            obj_js.setAttribute('title','Passed - Javascript is currently turned on.');
        }
        
        // Browser
        var result_browser = Y.one('#support-widget .browser');
        var browser_name = test_data.browser.name.toLowerCase().replace(/\ /g, '-');
        if (test_data.browser && test_data.browser.passed) {
            result_browser.addClass(browser_name).ancestor('li').addClass('passed').removeClass('unknown').removeClass('failed');
            result_browser.setAttribute('title','Passed - This site has been designed and tested to work in '+test_data.browser.name+' '+test_data.browser.version+'.');
        }
        else {
            result_browser.addClass(browser_name).ancestor('li').addClass('failed').removeClass('unknown').removeClass('passed');
            result_browser.setAttribute('title',"Not Recommended - This site hasn't been tested to work in "+test_data.browser.name+' '+test_data.browser.version+'.');
        }
        
        // Popup
        var result_popup = Y.one('#support-widget .popup').ancestor('li');
        if (test_data.popup && test_data.popup.allow) {
            result_popup.addClass('passed').removeClass('unknown').removeClass('failed');
            result_popup.setAttribute('title','Passed - This browser currently allows pop-up windows from this site.');
        }
        else {
            result_popup.addClass('failed').removeClass('unknown').removeClass('passed');
            result_popup.setAttribute('title','Failed - Pop-up windows are being blocked.');
        }
        
        // Flash
        var result_flash = Y.one('#support-widget .flash').ancestor('li');
        if (test_data.flash && test_data.flash.passed) {
            result_flash.addClass('passed').removeClass('unknown').removeClass('failed');
            result_flash.setAttribute('title','Passed - This browser currently is using Flash '+test_data.flash.version+' which meets the minimum required version.');
        }
        else {
            result_flash.addClass('failed').removeClass('unknown').removeClass('passed');
            result_flash.setAttribute('title',"Failed - The flash player available to this browser doesn't meet the minimum required version for this site.");
        }
        
        setBehaviors();
                
    } // end postTest()
    
    
    
    function setBehaviors() {
        
        // Set Behaviors
        Y.one('#support-widget .lp-logo')
            .setAttribute('title','LearningPool.com')
            .on('click', function() {
                window.location = 'http://www.learningpool.com';
            });
        Y.all('#support-widget .failed')
            .on('click', function () {
                window.location = 'http://www.learningpool.com/support';
            });
        Y.one('#support-widget ul.tests')
            .delegate(
                'hover',
                function (e) { e.target.ancestor('li').addClass('hover'); e.stopPropagation(); },
                function (e) { e.target.ancestor('li').removeClass('hover'); e.stopPropagation(); },
                'li span'
            );
        Y.one('#support-widget .retest')
            .on('click', function() {
                if (confirm("Retesting will reload the page. You will lose any unsaved data. Continue anyway?")) {
                    var tld = document.domain.substr(document.domain.indexOf('.'));
                    Y.Cookie.remove('dle_support', {'path': "/", 'domain':tld});
                    if (this.ancestor('#support-widget').getAttribute('data-autorun') == 'true') {
                        location.reload();
                    }
                    else {
                        initTest();
                    }
                }
            });
            
    } // end setBehaviors()
    
}