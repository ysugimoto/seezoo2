(function() {
	
	var FL = getInstance();
	FL.load.ajax();
	var target, handle, tabs, toggles;
	
	var createMultiPopup = function() {
		var current = DOM('div.sz-popup-content').get(1, true).addStyle('zIndex', 999);
		var win = current.copy(true).addStyle({'zIndex': 1001, width : '800px', height : '500px', display : 'block', 'marginLeft' : '-400px'})
							.appendTo(window.IEFIX ? IEFIX.body : document.body)
							.detect('div.sz-pp-tc').get(0, true).html('画像の選択');
		//new Module.draggable(win, {handle : win.detect('div.sz-pp-tc').get(0, true)});
		currentP++;
		return win;
	};
	
	
	// toggle page_link type
	if ( document.getElementById('toggle_link') ) {
		toggles = DOM.id('toggle_link').parent();
		toggles.event('click', function() {
			var chk = this.getOne('input').get().checked;
			
			toggles.next()[chk ? 'hide' : 'show']()
					.next()[chk ? 'show' : 'hide']();
		}, toggles);
	}
	
	
	
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

	DOM('a.button_right').get(0)
	.event('click', function(ev) {
		var a = document.getElementsByName('action_method')[0],
			b = document.getElementsByName('action_file_id')[0],
			c = document.getElementsByName('file_id')[0],
			err = [];

		if ( c.value === '' ) {
			err[err.length] = '画像ファイルが選択されていません。';
		}
		
		if ( a.value === '0' ) {
			return;
		}
		if ( b.value === '' ) {
			err[err.length] = 'アクションを設定する場合、アクション後の画像ファイルも指定してください。';
		}
		
		if ( err.length > 0 ) {
			ev.preventDefault();
			return !!alert(err.join('\n'));
		}
		
	});

})();