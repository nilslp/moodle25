M.theme_ubertheme.yui_carousel = function(Y){
    
    var i = 0, img = {}, transform = {}, anchor;
    var carousel = {};
    var controls = {};
    var transforms = [];
    var images = {};
    var cfg = {
        "maxdepth" : 5,   
        "carouselWidth" : 0,
        "carouselHeight" : 0,
        "switchInterval" : 3000,
		"transitionDuration" : 0.5,
        "centerX" : 50,
        "centerY" : 20,
        "numImages" : 0,
        "startZ" : 0,
        "imageHeight" : 0,
        "imageWidth" : 0,
        "flankingItems" : 4,
        "startingItemSeparation" : 80,
        "separationFactor" : 0.6,
        "decreaseFactor" : 0.8,
        "opacityDecreaseFactor" : .75,
        "timer" : -1,
		"restartTimer" : -1,
		"restartInterval" : 3000,
        "clockwise" : 0,
		"canClick" : true,
		"textMargin" : 10,
		"imageTitle" : 1
    };
           
    // 1) find if a list of images with the required class exists
    carousel = Y.one('.yui-carousel-container');
    if (!carousel) {
        // no carousel on page
        return;
    }
    
    cfg.carouselWidth = parseInt(carousel.getStyle('width'),10);
    cfg.carouselHeight = parseInt(carousel.getStyle('height'),10);
    cfg.centerX = cfg.carouselWidth * .5;
    cfg.centerY = cfg.carouselHeight * .5;
    
    // load stored settings
    var settings = carousel.all('input.carousel-settings');
    settings && settings.each(function(node) {
        var value = node.get('value');
        var name = node.get('name');
        cfg[name] = parseFloat(value);
    });
    
    // 2) put the images into a yui NodeList
    images = carousel.all('.carousel-images a');
    if (!images || (0 == images.size())) {
        // no images in carousel
        return;
    }
    
    cfg.numImages = images.size();
    
    // 3) stack images depthwise
    carousel.setStyle('position','relative');
    
    // calc max image size
    for (i = 0; i < cfg.numImages; ++i) {
		var anchor = images.item(i);
        img = anchor && anchor.one('img');
        if (!img) {
            continue;
        }
        var imgHeight = parseInt(img.getComputedStyle('height'),10);
        var imgWidth = parseInt(img.getComputedStyle('width'),10);
		
		// try animating a div
		//var div = Y.Node.create('<div style="position:relative;width:'+imgWidth+';height:'+imgHeight+';"></div>');
		//div.append(img);
		//img.remove();
		if (cfg.imageTitle) {
			//div.append(Y.Node.create('<span class="yui-image-text" style="bottom:'+parseInt(cfg.textMargin,10)+'%;">'+img.get('title')+'</span>'));
			anchor.append(Y.Node.create('<span class="yui-image-text" style="bottom:'+parseInt(cfg.textMargin,10)+'%;">'+img.get('title')+'</span>'));
		}
		//anchor.append(div);
		
        cfg.imageHeight = (imgHeight > cfg.imageHeight) ? imgHeight : cfg.imageHeight;
        cfg.imageWidth = (imgWidth > cfg.imageWidth) ? imgWidth : cfg.imageWidth;
    }
    
    // starting transform for topmost item
    transform = {
        x : (cfg.centerX - cfg.imageWidth * .5),
        y : (cfg.centerY - cfg.imageHeight * .5),
        width : cfg.imageWidth,
        height : cfg.imageHeight,
        opacity : 1,
        zIndex : cfg.numImages
    };
    
    var itemSeparation = cfg.imageWidth - cfg.startingItemSeparation;
    var itemStartX = transform.x;
    var itemStartLeft = itemStartX+cfg.imageWidth;
    for (i = 0; i <= cfg.flankingItems; ++i) {       
        // store transform
        transforms.push({
            height : transform.height,
            width : transform.width,
            x : transform.x,
            y : transform.y,
            opacity : transform.opacity,
            zIndex : transform.zIndex
        });
        
        if (i > 0) {            
            // unshift a mirror of the above transform onto the start of the array
            transforms.unshift({
                height : transform.height,
                width : transform.width,
                x : itemStartX - (transform.width + (transform.x - itemStartLeft)),
                y : transform.y,
                opacity : transform.opacity,
                zIndex : transform.zIndex
            });
        }
        
        // premodify transform for next item
        transform.width *= cfg.decreaseFactor;
        transform.height *= cfg.decreaseFactor;
        transform.x += itemSeparation;
        transform.y = cfg.centerY - transform.height * .5;
        transform.zIndex--;
        transform.opacity *= cfg.opacityDecreaseFactor;
        itemSeparation *= cfg.separationFactor;        
    }
        
    images.setStyle('position','absolute');
    images.setStyle('left',cfg.centerX-cfg.imageWidth*.5);
    images.setStyle('top',cfg.centerY-cfg.imageHeight*.5);
    
    // show image container and carousel
    // carousel.setStyle('visibility','visible');
    carousel.setStyle('background','transparent none');
    var carousel_elments = carousel.all('.carousel-controls, .carousel-images');
    // imgcontainer && imgcontainer.transition({
    //     duration : 1,
    //     opacity : 1
    // });
    carousel_elments.setStyle('visibility','visible');

    animateImages();
    
    // 4) add listeners to controls
    // see this link: http://yuilibrary.com/yui/docs/event/ for information on adding a listener to a button
	var backBtn = Y.one('.carousel-controls .carousel-prev');
	var forwardBtn = Y.one('.carousel-controls .carousel-next');
	
	// on backBtn click - reset	interval and go to prevImage
	backBtn && backBtn.on('click', function(e) {
		if (!cfg.canClick) {
			return;
		}
		prevImage();
		e.stopPropagation();
		pauseAnimation();
	});
	
	// on nextBtn click - reset interval and go to nextImage
	forwardBtn && forwardBtn.on('click', function(e) {
		if (!cfg.canClick) {
			return;
		}
		nextImage();
		e.stopPropagation();
		pauseAnimation();
	});
    
	startAnimation();
	
	function pauseAnimation() {
		clearInterval(cfg.timer);
		if (cfg.restartTimer != -1) {
			clearInterval(cfg.restartTimer);
		}
		cfg.restartTimer = setInterval(restartAnimation,cfg.restartInterval);
	};
	
	function restartAnimation() {
		clearInterval(cfg.restartTimer);
		cfg.restartTimer = -1;
		startAnimation();
	}
    
	function startAnimation() {	
		// 5) animate images
		// see this link for creating timers http://www.w3schools.com/js/tryit.asp?filename=tryjs_setinterval   
		cfg.timer = setInterval(nextImage,cfg.switchInterval);
	}
	
    // shifts images to next natural sequence
    function nextImage() {
        if (cfg.clockwise) {
            images.push(images.shift());
        } else {
            images.unshift(images.pop());            
        }
        animateImages();
    }
	
	// shifts images to prev natural sequence
    function prevImage() {
        if (cfg.clockwise) {
			images.unshift(images.pop());
        } else {
            images.push(images.shift());            
        }
        animateImages();
    }
    
    function animateImages() {
		cfg.canClick = false;
        for (i = 0; i < cfg.numImages; ++i) {
            anchor = images.item(i);
            if (!anchor) {
                continue;
            }
            //var img = anchor.one('div');
            var img = anchor;
			var title = img && img.one('span');
            var tindex = Math.min(i, (transforms.length - 1));
            transform = transforms[tindex];
            
			var percent = Math.floor((transform.width/cfg.imageWidth)*100);
            // the following code sets the properties directly, but you will want to create transistions instead
            // see this link: http://yuilibrary.com/yui/docs/panel/panel-animate.html for examples
            // anchor.setStyle('left',transform.x+"px");
            // anchor.setStyle('top',transform.y+"px");
			anchor.transition({
				duration : cfg.transitionDuration,
				left : transform.x+"px",
				top : transform.y+"px"
			}, function () {
				cfg.canClick = true;
			});
			
			title && title.transition({
				duration : cfg.transitionDuration,
				//'font-size' : percent+'px'
				'font-size' : '12px'
			});
			
			anchor.setStyle('zIndex',transform.zIndex);
			
            var opacity = (i>tindex) ? 0 : transform.opacity;
            img.transition({
				duration : cfg.transitionDuration,
				height : transform.height+"px",
				width : transform.width+"px",
				opacity: opacity
			});
            // img.setStyle('opacity',opacity);
        }
    }

};
