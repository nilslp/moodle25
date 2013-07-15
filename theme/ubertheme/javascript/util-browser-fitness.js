/** Browser Fitness
    -------------------------------------------------------------------------*/
    
var BrowserFitness = {

    init:function (args) {
    
        // test variables
        this.browser = false;
        this.popup = false;
        this.flash = false;
        this.viewport = false;
    
        // default minimum requirements
        this.minreqs = {};
        this.minreqs.verFlash = 7;
        this.minreqs.verFirefox = 3.5;
        this.minreqs.verExplorer = 6;
        this.minreqs.verChrome = 8;
        this.minreqs.verSafari = 5;
        
        // set which tests to run
        this.runtest = {};
        this.runtest.popup = true;
        this.runtest.viewport = true;
        this.runtest.browser = true;
        this.runtest.flash = true;
    
        if (args) {
            
            if (args.minreqs) this.minreqs = this.objMerge(this.minreqs, args.minreqs);
            if (args.runtest) this.runtest = this.objMerge(this.runtest, args.runtest);
            
            if (args.diagnostic) {	
                // Testing Messages
                this.minreqs.verFlash = 99;
                this.minreqs.verFirefox = 99;
                this.minreqs.verExplorer = 99;
                this.minreqs.verChrome = 99;
                this.minreqs.verSafari = 99;
                this.runtest.popup = false;
                this.runtest.flash = false;
            }
            
        }
        
        // Pop Ups
        if (this.runtest.popup) this.popup = PopupTest.init();
        
        // Viewport
        if (this.runtest.viewport) this.viewport = ViewportInfo.init();
	
        // Browser
        if (this.runtest.browser) {
            this.browser = BrowserDetect.init();
            this.browser.passed = false;
            if (this.browser.name == 'Firefox' && parseFloat(this.browser.version) >= this.minreqs.verFirefox) { this.browser.passed = true; }	
            else if (this.browser.name == 'Internet Explorer' && parseFloat(this.browser.version) >= this.minreqs.verExplorer) { this.browser.passed = true; }	
            else if (this.browser.name == 'Chrome' && parseFloat(this.browser.version) >= this.minreqs.verChrome) { this.browser.passed = true; }	
            else if (this.browser.name == 'Safari' && parseFloat(this.browser.version) >= this.minreqs.verSafari) { this.browser.passed = true; }
        }
        
        // Flash
        if (this.runtest.flash) {
            this.flash = AdobeFlash.init();
            this.flash.passed = false;
            if (parseFloat(this.flash.version) >= this.minreqs.verFlash) { this.flash.passed = true; }	
        }
        
    },
    
    objMerge: function(obj1, obj2) {
        var obj3 = {};
        for (var attr in obj1) { obj3[attr] = obj1[attr]; }
        for (var attr in obj2) { obj3[attr] = obj2[attr]; }
        return obj3;
    }
			
} // end browserTest()



var PopupTest = {

    init: function() {
        this.done = false;
        this.allow = false;
        this.pop_test = window.open('','pop_test','width=1,height=1,left=0,top=0,scrollbars=no');
        if (this.pop_test) { t = setTimeout(PopupTest.popupDelay, 100); } else { this.allow = false; this.done = true; }
        return this;
    },
    
    popupDelay: function() {
    
        if (PopupTest.pop_test.screenTop) {
            // IE 9,8,7,6
            //alert("screenTop");
            PopupTest.pop_test.close();
            PopupTest.allow = true;
        }
        else if (PopupTest.pop_test.screenY) {
            //alert("screenY");
            PopupTest.pop_test.close();
            PopupTest.allow = true;
        }
        else if (PopupTest.pop_test.self && BrowserDetect.name != 'Chrome') {
            // Firefox, Safari
            //alert("self");
            PopupTest.pop_test.close();
            PopupTest.allow = true;
        }
        else if (PopupTest.pop_test.self) {
            // Chrome - maybe
            //alert('Not Closed!');
            if (PopupTest.pop_test.outerHeight > 0) {
                PopupTest.pop_test.close();
                PopupTest.allow = true;
            }
            else {
                PopupTest.allow = false;
            }
        }
        else {
            //alert("Hmmm.");
            PopupTest.pop_test.close();
            PopupTest.allow = false;
        }
        
        PopupTest.done = true;
        return;
    }
    
}; // end PopupTest



var ViewportInfo = {

    init: function() {
    
        this.width = 0; 
        this.height = 0;
        
        if (typeof window.innerWidth != 'undefined') {
            this.width = window.innerWidth;
            this.height = window.innerHeight;
        }
        else if (typeof window.documentElement != 'undefined'
                    && typeof window.documentElement.clientWidth != 'undefined'
                    && window.documentElement.clientWidth != 0) {
            this.width = window.documentElement.clientWidth;
            this.height = window.documentElement.clientHeight;
        }
        else {
            this.width = document.getElementsByTagName('body')[0].clientWidth;
            this.height = document.getElementsByTagName('body')[0].clientHeight;
        }
        
        return this;
    
    }

};



// -> From Quirksmode.org
var BrowserDetect = {
	
    init: function () {
        this.useragent = navigator.userAgent || "?";
		this.name = this.searchString(this.dataBrowser) || "?";
		this.version = this.searchVersion(navigator.userAgent)
			|| this.searchVersion(navigator.appVersion)
			|| "?";
		this.os = this.searchString(this.dataOS) || "?";
        
        return this;
	},
	
    searchString: function (data) {
		for (var i=0;i<data.length;i++)	{
			var dataString = data[i].string;
			var dataProp = data[i].prop;
			this.versionSearchString = data[i].versionSearch || data[i].identity;
			if (dataString) {
				if (dataString.indexOf(data[i].subString) != -1)
					return data[i].identity;
			}
			else if (dataProp)
				return data[i].identity;
		}
	},
	
    searchVersion: function (dataString) {
		var index = dataString.indexOf(this.versionSearchString);
		if (index == -1) return;
		return parseFloat(dataString.substring(index+this.versionSearchString.length+1));
	},
	
    dataBrowser: [
		{   string: navigator.userAgent,    subString: "Maxthon",           identity: "Maxthon"                                          },
		{   string: navigator.userAgent,    subString: "Chrome",            identity: "Chrome"                                          },
		{   string: navigator.userAgent,    subString: "CrMo",              identity: "Chromium Mobile"                                 },
		{ 	string: navigator.userAgent,    subString: "OmniWeb",           identity: "OmniWeb",            versionSearch: "OmniWeb/"   },
		{   string: navigator.vendor,       subString: "Apple",             identity: "Safari",             versionSearch: "Version"    },
		{   prop: window.opera,                                             identity: "Opera"                                                                   },
		{   string: navigator.vendor,       subString: "iCab",              identity: "iCab"                                            },
		{   string: navigator.vendor,       subString: "KDE",               identity: "Konqueror"                                       },
		{   string: navigator.userAgent,    subString: "Firefox",           identity: "Firefox"                                         },
		{   string: navigator.vendor,       subString: "Camino",            identity: "Camino"                                          },
		{	string: navigator.userAgent,    subString: "Netscape",          identity: "Netscape"                                        }, // for newer Netscapes (6+)
		{   string: navigator.userAgent,    subString: "MSIE",              identity: "Internet Explorer",  versionSearch: "MSIE"       },
		{   string: navigator.userAgent,    subString: "Gecko",             identity: "Mozilla",            versionSearch: "rv"         },
		{ 	string: navigator.userAgent,    subString: "Mozilla",           identity: "Netscape",           versionSearch: "Mozilla"    }  // for older Netscapes (4-)
	],
	
    dataOS : [
        // -> Windows
		{   string: navigator.platform,     substring: "Win",               identity: "Windows"                 },
		{   string: navigator.userAgent,    subString: "Windows NT 5.0",    identity: "Windows 2000"            },
		{   string: navigator.userAgent,    subString: "Windows NT 5.1",    identity: "Windows XP"              },
		{   string: navigator.userAgent,    subString: "Windows NT 5.2",    identity: "Windows XP x64 Edition"  },
		{   string: navigator.userAgent,    subString: "Windows NT 6.0",    identity: "Windows Vista"           },
		{   string: navigator.userAgent,    subString: "Windows NT 6.1",    identity: "Windows 7"               },
        // -> Mac
		{   string: navigator.platform,     subString: "Mac",               identity: "Mac OS X"                },
		{   string: navigator.userAgent,    subString: "iPod",              identity: "iPod"                    },
		{   string: navigator.userAgent,    subString: "iPhone",            identity: "iPhone"                  },
        // -> Android
		{   string: navigator.userAgent,    subString: "Android 4",         identity: "Android 4.x"             },
		{   string: navigator.userAgent,    subString: "Android 2.3",       identity: "Android 2.3.x"           },
		{   string: navigator.userAgent,    subString: "Android 2.2",       identity: "Android 2.2.x"           },
		{   string: navigator.userAgent,    subString: "Android",           identity: "Android"                 },
        // -> Linux
		{   string: navigator.userAgent,    subString: "Ubuntu",            identity: "Ubuntu"                  },
		{   string: navigator.userAgent,    subString: "Kindle",            identity: "Kindle"                  },
		{   string: navigator.platform,     subString: "Linux",             identity: "Linux"                   }
	]

};


var AdobeFlash = {
    
    init: function () {
        this.version = -1;
        this.GetSwfVer();
        return this;
    },
    
    GetSwfVer: function () {
        
        var flashVersion = -1;
        
        if (navigator.plugins && navigator.plugins.length) {
            
            var plugin = navigator.plugins['Shockwave Flash'];
            
            if (plugin) {
                
                hasFlash = true;
                
                if (plugin.description) {
                    flashVersion = this.getFlashVersion(plugin.description);
                }
            }

            if (navigator.plugins['Shockwave Flash 2.0']) {
                hasFlash = true;
                flashVersion = '2.0.0.11';
            }

        }
        else if (navigator.mimeTypes && navigator.mimeTypes.length) {
            
            var mimeType = navigator.mimeTypes['application/x-shockwave-flash'];
            
            if (mimeType && mimeType.enabledPlugin) {
                flashVersion = this.getFlashVersion(mimeType.enabledPlugin.description);
            }

        }
        else if (typeof window.ActiveXObject != 'undefined') {
            
            try {
                var ax = new ActiveXObject('ShockwaveFlash.ShockwaveFlash');
                flashVersion = this.getFlashVersion(ax.GetVariable('$version'));
            }
            catch (e) {}
            
            if (flashVersion == -1) {
            
                try {
                    var ax = new ActiveXObject('ShockwaveFlash.ShockwaveFlash');
                    flashVersion = this.getFlashVersion(ax.GetVariable('$version'));
                }
                catch (e) {}
            
            }
            
        }
          
        this.version = flashVersion;
    },
    
    getFlashVersion: function (desc) {
        var matches = desc.match(/[\d]+/g);
        matches.length = 3;  // To standardize IE vs FF
        return matches.join('.');
    }
    
};