(function() {

	var FL = getInstance();
	FL.load.ajax();
	var target, handle, tabs;

		tabs = DOM('ul.sz_tabs a');
		// current tab and box
		var currentTab, currentBox;

		tabs.foreach(function() {
			if (this.className === 'active') {
				currentTab = DOM(this);
				currentBox = DOM.id(this.href.slice(this.href.indexOf('#') + 1)).show();
			}
		}).event('click', function(ev) {
			ev.preventDefault();
			var href = this.href;
			currentTab.removeClass('active');
			currentBox.hide();
			currentTab = DOM(this).addClass('active');
			currentBox = DOM.id(href.slice(href.indexOf('#') + 1)).show();
		});


		DOM.id('sz_file_api_block_multiple_results').getOne('table')
		.event('mouseover', function(ev) {
			var e = (ev.target.tagName === 'DIV' || ev.target.tagName === 'A')? ev.target.parentNode : ev.target;
			DOM(e).addClass('hover');
		})
		.event('mouseout', function(ev) {
			var e = (/div|a/i.test(ev.target.tagName)) ? ev.target.parentNode : ev.target;
			DOM(e).removeClass('hover');
		})
		.event('click', function(ev) {
			if (ev.target.tagName !== 'A') { return;}
			var e = DOM(ev.target), p = e.parent(3);

			if (e.hasClass('sz_file_multi_delete')) { // delete
				p.remove();
			} else if (e.hasClass('sz_file_list_sort_order_next')) { // next sort
				if (!p.isLast()) {
					p.appendTo(p.next(), 'after').removeClass('hover');
				}
			} else if (e.hasClass('sz_file_list_sort_order_prev')) { // prev sort
				if (!p.isFirst()) {
					p.appendTo(p.prev(), 'before').removeClass('hover');
				}
			}
		});
		
		DOM.id('sz_slide_play_type').event('change', function() {
			var disable = (this.value == 3) ? true : false;
			DOM(this).parent(2).detect('dd').getRange(1).foreach(function() {
				DOM(this).first().get().disabled = disable;
			})
		});
})();