(function() {

	var FL = getInstance(), DOM = FL.DOM;

	var PP, current, currentB, tabs, previewCache = {};

	var displayModeMap = {
		1 : '通常の縦並びメニューです。',
		2 : 'グローバルメニュー用に横並びに配置します。',
		3 : 'パンくずナビを表示します。'
	};

	// generate preiview
	var preview = function(e) {
		var param = {
				'sort_order' : DOM.id('sort_order').getValue(),
				'page_id'		: DOM('div.sz_page_api_block input').get(0).getValue(),
				'subpage_level' : DOM.id('subpage_level').getValue(),
				'handle_class' : DOM.id('handle_class').getValue(),
				'display_mode' : DOM.id('display_mode').getValue(),
				'show_base_page' : (function() { return (DOM.id('show_base_page').get().checked === true) ? 1 : 0;})(),
				'current_page_id' : SZ_PAGE_ID
			};

		if (param.display_mode != 3 && (!param.page_id || param.page_id == '')) {
			alert('基点ページが選択されていません。');
			return;
		}
		FL.ajax.post('ajax/generate_navigation/' + SZ_PAGE_ID, {
			param : param,
			success : function(resp) {
				DOM.id('sz_autonav_preview').removeClass('loading');
				e.html(resp.responseText);
			}
		});
	}

//	DOM.id('manual_selected_page').event('click', openWindowPP);
	tabs = DOM('ul.autonav').get(0);
	current = tabs.getOne('li.active > a');
	(function() {
		var href = current.readAttr('href');
		href = href.slice(href.indexOf('#') + 1);
		currentB = DOM.id(href);
	})();
	tabs.event('click', function(ev) {
		ev.preventDefault();
		var e = DOM(ev.target), href;
		if (e.tag !== 'a' || e.parent().hasClass('active')) { return;}
		current.parent().removeClass('active');
		e.parent().addClass('active');
		current =  e;
		href = ev.target.href;
		href = href.slice(href.indexOf('#') + 1);
		currentB.hide();
		currentB = DOM.id(href).show();
		if (e.hasClass('preview')) {
			preview(DOM.id('sz_autonav_preview'));
		} else {
			DOM.id('sz_autonav_preview').addClass('loading').html('');
		}
	});
	DOM.id('display_mode').event('change', function() {
		DOM.id('display_mode_caption').html(displayModeMap[this.value]);
	});
})();