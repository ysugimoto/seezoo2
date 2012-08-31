// tab control function

(function() {
	
	var FL = getInstance(),
		ipts = DOM('input.txt-long'),
		count = ipts.length,
		frame = DOM.id('as_contents_frame'),
		preview = DOM.id('sz_asp_previews_wrapper'),
		previews = preview.detect('div.sz_asp_previews'),
		borderOffset = 2,
		wrapWidth = 450,
		mf = Math.floor,
		mr = Math.round,
		mc = Math.ceil;
	
	// add as event handler
	var addTab = function(ev) {
		DOM.create('p')
			.append('<label><input type="text" name="as_contents_name[]" class="txt-long" /></label><a href="javascript:void(0)" class="as_delete"><img src="' + FL.config.baseUrl() + 'images/delete.png" />&nbsp;削除</a>')
			.appendTo(frame);
		
		// add preview
		var d = DOM.create('div')
					.addClass('sz_asp_previews asp_last')
					.html('<p><em></em>%<input type="hidden" name="as_contents_pers[]" value="0" /></p><span></span>')
					.appendTo(preview);
		previews.nodeList.push(d.get());
		previews.length++;
		_recalculatePreviews();
	};
	
	// delete as event handler
	var judgeDelete = function(ev) {
		var e = DOM(ev.target);
		
		if (e.tag !=='img' && !e.hasClass('as_delete')) {
			return;
		}
		if (frame.detect('input').length === 2) {
			return alert('これ以上は削除できません。');
		}
		e.parent().remove();
		previews.get(previews.length - 1).remove();
		previews.nodeList.pop();
		previews.length--;
		_recalculatePreviews();
	};
	
	function _recalculatePreviews() {
		var len = previews.length,
			div = mf(wrapWidth / len),
			per = mf(1 / len * 100),
			suf = mc((FL.ua.IE6 ? 99 : 100) % len),
			e, c, t;
		
		previews.foreach(function(num) {
			e = DOM(this);
			e.addStyle('width', div - borderOffset + 'px')
						.getOne('em').html((suf > 0) ? per + 1 : per)
						.next().setValue((suf > 0) ? per + 1 : per);
			--suf;
			c = e.getOne('span');
			if (num + 1 === len) {
				c.hide();
			} else {
				if (num + 2 === len) {
					t = c;
				}
				e.removeClass('asp_last')
				c.show();
			}
		});
		
		t && new AspWidthSetting(t);
	}
	
	// tab changer ========================================================
	var currentTab,
		currentBox,
		tabs,
		href;
	
	tabs = DOM('ul.sz_tabs a');
	// current tab and box
	tabs.foreach(function() {
		if (this.className === 'active') {
			currentTab = DOM(this);
			currentBox = DOM.id(this.href.slice(this.href.indexOf('#') + 1)).show();
		}
	}).event('click', function(ev) {
		ev.preventDefault();
		 href = this.href;
		currentTab.removeClass('active');
		currentBox.hide();
		currentTab = DOM(this).addClass('active');
		currentBox = DOM.id(href.slice(href.indexOf('#') + 1)).show();
	});
	
	// tab changer end ======================================================
	
	// set events
	DOM.id('add_as').event('click', addTab);
	
	
	// frame live event
	frame.event('click', judgeDelete);
	
	// validation
	DOM('a.button_right').get(0).event('click', function(ev) {
		var areas = DOM('div.cmsi_add_block > span'),
			vals = {},
			ivals = [],
			flag = true;
		
		areas.foreach(function() {
			vals[this.firstChild.nodeValue] = 1;
		});
		
		DOM.byName('as_contents_name[]').foreach(function() {
			
			if (this.value === '') {
				flag = false;
				ev.preventDefault();
				alert('未入力のコンテンツ名があります。');
				return false;
			}
			if (FL.ut.inArray(this.value, ivals)) {
				flag = false;
				ev.preventDefault();
				alert(this.value + ' が重複しています。');
				return false;
			}
			ivals.push(this.value);
		});
	});
	
	// inner Class ============================================================

	function AspWidthSetting(e) {
		this.handle = e;
		this.__parent = e.parent();
		this.__parentN = this.__parent.get();
		this.next = this.__parent.next();
		this.start = 0;
		this.startWidth = 0;
		if (this.next) {
			this.__construct();
		}
	}
	
		AspWidthSetting.prototype = {
			__construct : function() {
				this.handle.event('mousedown', this.__draginit, this);
			},
			__draginit : function(ev) {
				ev.stopPropagation();
				ev.preventDefault();
				this.start = ev.clientX;
				this.startWidth = this.__parent.readStyle('width', true);
				this.nextW = this.next.readStyle('width', true);
				this.handle.unevent('mousedown', this.__draginit);
				FL.event.set(document, 'mousemove', this.__setting, this);
				FL.event.set(document, 'mouseup', this.__dragend, this);
			},
			__setting : function(ev) {
				ev.stopPropagation();
				ev.preventDefault();
				var of = this.start - ev.clientX,
					w = this.startWidth - of,
					e, v;
				
				this.__parent.addStyle('width', w + 'px')
				this.next.addStyle('width', this.nextW + of + 'px');
				previews.foreach(function() {
					e = DOM(this);
					v = mc(e.readStyle('width', true) / wrapWidth * 100);
					e.getOne('em').html(v)
					.next().setValue(v);
				});
			},
			__dragend : function(ev) {
				this.nextW = this.next.readStyle('width', true);
				FL.event.remove(document, 'mousemove', this.__setting);
				FL.event.remove(document, 'mouseup', this.__dragend);
				this.handle.event('mousedown', this.__draginit, this);
			},
			__recalculatePercent : function() {
				
			}
		};
	
	
	// inner Class end ========================================================
	
	// set event
	previews.foreach(function() {
		new AspWidthSetting(DOM(this).getOne('span'));
	});
	
	DOM.id('reset_division').event('click', _recalculatePreviews);

})();
