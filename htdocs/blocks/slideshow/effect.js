(function() {
	var FL = getInstance(),sc = document.getElementsByTagName('script'),
		len = sc.length,
		i,
		thisScript,
		base,
		imgs,
		last,
		delay = window.setTimeout,
		unDelay = window.clearTimeout,
		e,
		types,
		handle,
		captions;

	for (i = len - 1; i >= 0; i--) {
		if (sc[i].src.indexOf('blocks/slideshow/effect.js') > 0) {
			thisScript = sc[i];
			break;
		}
	}

	if (!thisScript) { return;}
	// generate_param
	var srcQuery = thisScript.src.slice(thisScript.src.indexOf('?') + 1).split('&');
	var param = {};
	for (var j = 0; j < srcQuery.length; j++) {
		var spt = srcQuery[j].split('=');
		param[spt[0]] = spt[1];
	}

	// types mapping functions
	types = {
			1 : fadeOut,
			2 : slideLeft,
			3 : slideUpper
	};
	
	/* =========== caption class ============== */
	function Caption(e) {
		this.base = e.next();
		this.captions = this.base.detect('a');
		this.pointer  = 0;
		this.current = this.captions.get(this.pointer);
		this.activeClass = 'sz_slideshow_current';
		
		this.__construct();
	}
	
	Caption.exists = function(e) {
		if ( ! e ) {
			return false;
		}
		var next = e.next();
		
		return !!(next && next.tag === 'ul' && next.hasClass('sz_slideshow_caption'));
	},
	
	Caption.prototype = {
		update : function(num) {
			this.current.removeClass(this.activeClass);
			this.current = this.captions.get(num).addClass(this.activeClass);
		},
		__construct : function() {
			this.base.event('click', this.__handleClick, this);
		},
		__handleClick : function(ev) {
			if ( ev.target.tagName !== 'A' ) {
				return;
			}
			var num = ev.target.rel.replace('target_', '');
			ev.preventDefault();
			handle.moveSlide(num);
			this.update(num);
		}
	};

	/* ========= fadeout slideshow ============*/
	function fadeOut() {
		var len;
		
		this.base  = base;
		this.imgs  = base.children();
		len = this.imgs.length;
		
		this.imgs.foreach(function(num) {
			e = DOM(this).prop('__index', len - num - 1);
		});
		
		this.defferd1;
		this.defferd2;
		this.timerID;
		this.fd = base.last();
		this.ap = this.fd.prev();
		this.index = 0;
	};

	fadeOut.prototype = {
		doEffect : function() {
			var that = this,
				base = this.base,
				tmp;
			
			if ( ! this.ap ) {
				//slide image is single.
				return;
			}
	
			if (window.IS_EDIT && IS_EDIT === true) {
				this.timerID = delay(function() {loop();}, param.delay || 3000);
				return;
			}
			
			function loop() {
				var flg;
				
				that.fd.addStyle('zIndex', 1);//.addStyle('width', that.fd.prop('offsetWidth') + 'px');
				that.ap.addStyle('zIndex', 2);
				that.defferd1 = Animation.appear(that.ap, {speed : 80});
				that.defferd2 = Animation.fade(that.fd, { speed : 80, afterHide : false, callback : function() {
					//that.fd.addStyle('zIndex', 1);
					that.fd = that.ap;//.addStyle('zIndex', 2);
					that.ap = that.ap.prev();
					if ( ! that.ap ) {
						that.ap = base.last();

					}
					captions && captions.update(that.fd.prop('__index'));

					that.timerID = delay(function() {loop();}, param.delay || 3000);
				}});
			}
			
			loop();
		},
		moveSlide : function(num) {
			this.defferd1 && this.defferd1.abort();
			this.defferd2 && this.defferd2.abort();
			this.timerID && unDelay(this.timerID);

			// reset
			this.imgs.foreach(function() {
				DOM(this).addStyle({zIndex : 1, opacity: 0});
			});
			this.ap = this.imgs.get(this.imgs.length - num - 1).addStyle({zIndex : 2, opacity : 1});
			this.fd = this.ap.next();
			if ( ! this.fd ) {
				this.fd = this.imgs.get(0);
			}
			this.fd.addStyle({zIndex : 1});
			
			this.doEffect();
		}
	};

	/* ========= slide left move slideshow ==========*/
	function slideLeft() {
		this.ul = base.first();
		this.li = this.ul.children(0);
		this.targetCount = this.ul.children().length - 1;
		this.pointer = 0;
		this.defferd;
		this.timerID;

		// calculate total width
		var maxWidth = 0;
		
		this.ul.children().foreach(function() {
			maxWidth = ( maxWidth < this.offsetWidth ) ? this.offsetWidth : maxWidth;
		})

		this.totalWidth = maxWidth * this.ul.children().length;
		this.perWidth   = maxWidth;
		this.moveToDefault = maxWidth * this.targetCount;
		this.ul.addStyle('width', this.totalWidth + 'px');
	};

	slideLeft.prototype = {
		doEffect : function() {
			var that = this, li;
	
			if (window.IS_EDIT && IS_EDIT === true) {
				delay(function() {that.doEffect();}, param.delay || 3000);
				return;
			}
			
			if ( this.pointer < this.targetCount ) { // next
				this.defferd = new Animation.smoothly(this.ul, { marginLeft : -this.perWidth }, { duration : 1, easing : 'easeInOutCubic', callback : function() {
					that.pointer++;
					captions && captions.update(that.pointer);
					that.timerID = delay(function() {that.doEffect();}, param.delay || 3000);
				}});
			} else {
				this.pointer = 0;
				this.defferd = new Animation.smoothly(this.ul, { marginLeft : this.moveToDefault }, { duration : 1, easing : 'easeInOutCubic', callback : function() {
					captions && captions.update(that.pointer);
					that.timerID = delay(function() {that.doEffect();}, param.delay || 3000);
				}});
			}
		},
		moveSlide : function(num) {
			//alert(num);
			this.defferd && this.defferd.abort();
			this.timerID && unDelay(this.timerID);
			//return;
			this.pointer = num;
			var current = this.ul.readStyle('marginLeft', true),
				target = -(num * this.perWidth) - current - 1;
				that = this;
			
			this.defferd = new Animation.smoothly(this.ul, { marginLeft : target }, { duration : 1, easing : 'easeInOutCubic', callback : function() {
				that.timerID = delay(function() {that.doEffect();}, param.delay || 3000);
			}});
		}
	};

	/* ========= slide upper move slideshow ==========*/
	function slideUpper() {
		this.ul = base.first();
		this.li = this.ul.children(0);
		this.targetCount = this.ul.children().length - 1;
		this.pointer = 0;

		// calculate total Height
		var maxHeight = 0;
		
		this.ul.children().foreach(function() {
			maxHeight = ( maxHeight < this.offsetHeight ) ? this.offsetHeight : maxHeight;
		})

		this.totalHeight = maxHeight * this.ul.children().length;
		this.perHeight = maxHeight;
		this.moveToDefault = maxHeight * this.targetCount;
		this.ul.addStyle('height', this.totalHeight + 'px');
	};

	slideUpper.prototype.doEffect = function() {
		var that = this, li;

		if (window.IS_EDIT && IS_EDIT === true) {
			delay(function() {that.doEffect();}, param.delay || 3000);
			return;
		}
		
		if ( this.pointer < this.targetCount ) { // next
			new Animation.smoothly(this.ul, { marginTop : -this.perHeight }, { duration : 1, easing : 'easeInOutCubic', callback : function() {
				that.pointer += 1;
				delay(function() {that.doEffect();}, param.delay || 3000);
			}});
		} else {
			that.pointer = 0;
			new Animation.smoothly(this.ul, { marginTop : this.moveToDefault }, { duration : 1, easing : 'easeInOutCubic', callback : function() {
				delay(function() {that.doEffect();}, param.delay || 3000);
			}});
		}
	};

	/* ==================== Garalley Class ================== */
	function Garalley() {
		var base = DOM.id('sz_slide_garalley_' + param.bid);
		this.currentImage = base.getOne('img');
		this.thumbnails = base.next().detect('li');
		this.active = this.thumbnails.get(0);
		this.init();
	}

	Garalley.prototype = {
		init : function() {
			this.thumbnails.event('click', this.__changeImage, this)
									.event('mouseover', function() {
										DOM(this).addClass('hover')
									})
									.event('mouseout', function() {
										DOM(this).removeClass('hover');
									});
		},
		__changeImage : function(ev) {
			var e = DOM(ev.currentTarget).addClass('active');

			this.active.removeClass('active');
			this.currentImage.attr('src', e.first().get().src.replace('thumbnail/', ''));
			this.active = e;
		}
	};

	// set up class functions of slide types
	FL.event.set(document, 'DOMReady', function(ev) {
		base = DOM.id('sz_slideshow_block_id_' + param.bid);
		if (param.play == 3) {
			new Garalley();
		} else {
			handle = new types[param.type]();
			delay(function() { handle.doEffect();}, param.delay || 3000);
		}
		captions = ( Caption.exists(base) ) ? new Caption(base) : null;
	});

})();