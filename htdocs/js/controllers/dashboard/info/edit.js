ClassExtend('Controller', function InfoEditController() {
	
	var target, handle;
	
	this.__construct = function() {
		this.load.library(['editor', 'page_operator', 'file_operator']);
		this.load.ajax();
	};
	
	this.index = function() {
		var that = this;
		
		this.ready('page_operator', function() {
			this.ready('file_operator', function() {
				this.setUpAPIHandle();
			});
		});
		
		this.ready('editor', function () {
			this.editor.setUp(document.getElementById('info_body'), {width : 660, height : 400,emoji:true});
		});
	
		if ( document.getElementById('toggle_external') ) {
			DOM.id('toggle_external').event('click', this.__toggleExternal);
		}
	};
	
	this.confirm = function() {
		if ( document.getElementById('info_form') ) {
			this.index();
		}
	}
	
	this.__toggleExternal = function(evt) {
		var chk = this.checked,
			elm = DOM(this),
			p = elm.parent().prev(),
			pp = p.prev();
			
		p[(chk === true) ? 'show' : 'hide']();
		pp[(chk === false) ? 'show' : 'hide']();
	};

	this.setUpAPIHandle = function() {
		var that   = this,
		    INITED = false,
		    pageAPIHandleClass       = 'div.sz_page_api_block, div.sz-pp-contents span.sz_page_api_block_name',
		    removePageSelectionClass = 'div.sz_page_api_block > a.remove_selection',
		    fileAPIHandleClass       = 'div.sz_file_api_block, div.sz-pp-contents span.sz_file_api_block_name',
		    removeFileSelectionClass = 'div.sz_file_api_block > a.remove_selection';

		// file API handler
		function setImageCallback() {
			that.file_operator.init(target, handle, 'simple');
		}

		
		this.event.exprLive(pageAPIHandleClass, 'click', function(ev) {
			var pid;
			
			handle = Helper.createDOMWindow('ページの選択', '', 720, '85%', false, true);
			target = DOM(this);
			if ( target.tag === 'span' ) { 
				target = target.parent();
			}
			
			pid = target.detect('input').get(0).getValue();
			if (pid == '') {
				pid = 1;
			}
			that.ajax.get('ajax/get_sitemap/' + pid + '/' + that.config.item('sz_token'), {
				success : function(resp) {
					handle.setContent(resp.responseText);
					if (INITED === false) {
						that.page_operator.init('block');
						INITED = true;
					}
					that.setPageCallback();
				}
			});
		});

		this.event.exprLive(removePageSelectionClass, 'click', function(ev) {
			DOM(ev.target).parent().getOne('input').setValue('');
			DOM(ev.target).parent().getOne('span').html('ページを選択');
		});
		
		this.event.exprLive(fileAPIHandleClass, 'click', function(ev) {
			ev.preventDefault();
			
			handle = Helper.createDOMWindow('画像の選択', '', 918, '85%', false, true);
			target = DOM(ev.target);
			if ( target.tag === 'span' ) {
				target = target.parent();
			}
			
			that.ajax.get('ajax/get_files_image_dir/' + (target.getOne('input').getValue() || 0) + '/' + that.config.item('sz_token'), {
				success : function(resp) {
					handle.setContent(resp.responseText);
					setImageCallback();
				}
			});
		});

		this.event.exprLive(removeFileSelectionClass, 'click', function(ev) {
			DOM(ev.target).parent().getOne('input').setValue('');
			DOM(ev.target).parent().getOne('span').html('ファイルを選択');
		});
	};
	
	// page API handler
	this.setPageCallback = function() {
		var that = this;
		
		this.event.exprdeLive('span.ttl', 'click');
		this.event.exprdeLive('span.ttl', 'mouseout');
		this.event.exprdeLive('span.ttl', 'mousover');
		this.event.exprLive('span.ttl', 'click', spanTtlClickHandler);
		
		function spanTtlClickHandler(ev) {
			ev.preventDefault();
			
			var fn  = spanTtlClickHandler,
				e   = DOM(ev.target),
				pid = e.readAttr('pid'),
				json;

			that.ajax.get('ajax/get_page/' + pid + '/' + that.config.item('sz_token'), {
				success : function(resp) {
					//eval('var json=' + resp.responseText);
					json = that.json.parse(resp.responseText);
					target.first().html(json.page_title);
					target.getOne('input').setValue(json.page_id);
					that.event.exprdeLive('span.ttl', 'click', fn);
					handle.hide();
				}
			});
		}
		this.event.exprLive('span.ttl', 'mouseover', function(ev) {
			DOM(ev.target).addClass('hover');
		});
		this.event.exprLive('span.ttl', 'mouseout', function(ev) {
			DOM(ev.target).removeClass('hover');
		});
		
		function toggleSearch(flag) {
			var f = that.ut.isBool(flag);

			DOM.id('sitemap')[f ? 'hide' : 'show']();
			DOM.id('sitemap_search_result')[f ? 'show' : 'hide']();

			if ( ! f ) {
				DOM('div#sz_sitemap_search_result_box div.sz_section')
					.unevent('mouseover')
					.unevent('mouseout')
					.unevent('click');
			}
		}
		
		function doSearchPage(ev) {
			that.ajax.post('ajax/search_page_sitemap/' + that.config.item('sz_token'), {
				param : DOM.id('sz_sitemap_search_menu').serialize(),
				error : function() { alert('ページ検索に失敗しました。'); },
				success : function(resp) {
					DOM.id('sz_sitemap_search_result_box').html(resp.responseText);
					toggleSearch(true);
					DOM('div#sz_sitemap_search_result_box div.sz_section').event('mouseover', function(ev) {
						DOM(this).addClass('sz_section_hover');
					}).event('mouseout', function(ev) {
						DOM(this).removeClass('sz_section_hover');
					}).event('click', function(ev) {
						var fn = arguments.callee,
							e = DOM(ev.target),
							pid = e.readAttr('pid'),
							json;

						that.ajax.get('ajax/get_page/' + pid + '/' + that.config.item('sz_token'), {
							success : function(resp) {
								//eval('var json=' + resp.responseText);
								json = that.json.parse(resp.responseText);
								target.first().html(json.page_title);
								target.getOne('input').setValue(json.page_id);
								DOM('div#sz_sitemap_search_result_box div.sz_section').unevent('click');
								handle.hide();
							}
						});
					});
				}
			});
		}

		DOM.id('sz_sitemap_search_do').event('click', doSearchPage);
		DOM.id('toggle_search').event('click', toggleSearch);
		handle.setOnClose(function() {
			DOM.id('sz_sitemap_search_do').unevent('click');
			DOM.id('toggle_search').unevent('click');
		});
	};
	
});