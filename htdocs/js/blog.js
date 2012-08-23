(function() {
	
	var FL = getInstance(),
		DOM = FL.DOM,
		cache = {};
	
	FL.load.ajax();
	
	function toggleComment(ev) {
		ev.preventDefault();
		var state = this.rel,
			target = DOM.id('toggle_comment').next();
		
		if (state === 'open') {
			target.animate('blindDown', {speed : 20});
			this.rel = 'close';
		} else {
			target.animate('blindUp', {speed : 20});
			this.rel = 'open';
		}
	}
	
	function createMessage() {
		var d = DOM.create('div')
					.attr('id', 'sz_comment_posted_message')
					.html('<p>コメントを投稿しました。</p>')
					.appendTo()
					.addStyle('position', 'fixed');
		
		setTimeout(function() {
			d.animate('blindUp', {speed : 20});
		}, 4000);
	}
	
	function setSortMenu() {
		Module.ready('ui', function() {
			new Module.sortable({
				sortClass : 'sz_blogparts_sortable',
				callback : saveOrder
			});
		});
	}
	
	function saveOrder() {
		var param = [],
			layer = new Module.layer(),
			save = DOM.id('sz_saving');
		
		DOM('div.sz_blogparts_sortable')
			.foreach(function(num) {
				param[param.length] = [this.getAttribute('type'), num + 1].join(':');
			});
	
		layer.show();
		save.show();
	
		FL.ajax.post('blog/ajax_update_blog_menu_order/' + FL.config.item('sz_token'), {
			param : {setting : param},
			success: function(resp) {
				layer.hide();
				save.hide();
			},
			error : function() {
				layer.hide();
				save.hide();
			}
		});
	}
	
	function init() {
		var tb = DOM.byName('tb_uri');
	
		if (DOM.id('sz_blog_calendar')) {
			FL.event.exprLive('div#sz_blog_calendar tr.head_row a', 'click', function(ev) {
				ev.preventDefault();
				var href = ev.target.href,
					wrap = DOM.id('sz_blog_calendar');
				
				if (cache[href]) {
					wrap.html(cache[href]);
				} else {
					FL.ajax.get(ev.target.href, {
						success : function(resp) {
							DOM.id('sz_blog_calendar').html(resp.responseText);
							cache[href] = resp.responseText;
						}
					});
				}
			});
		}
		
		if (DOM.id('toggle_comment')) {
			DOM.id('toggle_comment')
				.first()
				.event('click', toggleComment);
		}
		
		if (DOM.id('comment_posted')) {
			createMessage();
		}
		
		if (DOM('div.sz_blogparts_sortable').length > 0) {
			setSortMenu();
		}
		
		if ( tb.length > 0 ) {
			tb.get(0).event('click', function() {
				this.select();
			});
		}
	}
	
	FL.event.set(document, 'DOMReady', init);
	
})();
