/**
 * ========================================================================================
 *
 * Seezoo popup window helper
 * create ande control DOM window
 *
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 *
 * ========================================================================================
 */
(function() {

	var FL = getInstance(), Helper = FL.config.getGlobal('Helper'),
		ppCount = 0, ppStack = [], stableIndex = 990, latestIndex = 1100, layer,
		isLoggedIn = FL.config.item('is_login');

	FL.load.module('layer');
	FL.load.module('ui');


	var hidePP = function(callback) {
		var PP;

		if (ppCount === 1) {
			PP = ppStack[ppCount - 1];
			if (PP.doConfirm) {
				if (!confirm('ウインドウを閉じます。編集中のデータは破棄されます。よろしいですか？')) { return;}
			}
			// do callback is exists
			if (PP.closeCallback && FL.ut.isFunction(PP.closeCallback)) {
				PP.closeCallback();
			}
			PP.box.getOne('a.pp_close').unevent('click');
			PP.box.unevent('mouseover').unevent('mouseout').remove();
			ppStack = [];
			ppCount--;
			if (PP.keepLayer === false) {
				try {
					layer.hide();
				} catch(e){}
			}
		} else {
			PP = ppStack[ppCount - 1];
			if (PP.doConfirm) {
				if (!confirm('ウインドウを閉じます。編集中のデータは破棄されます。よろしいですか？')) { return;}
			}
			// do callback is exists
			if (PP.closeCallback && FL.ut.isFunction(PP.closeCallback)) {
				PP.closeCallback();
			}
			PP.box.getOne('a.pp_close').unevent('click');
			PP.box.unevent('mouseover').unevent('mouseout').remove();
			ppStack[ppCount - 2].box.addStyle('zIndex', latestIndex);
			if (FL.ua.IE6) {
				ppStack[ppCount - 2].box.detect('select').foreach(function() {
					this.style.visibility = 'visible';
				});
			}
			ppStack.pop();
			ppCount--;
		}

	};

	if (!functionExists('createDOMWindow')) {
		Helper.createDOMWindow = function(title, body, w, h, withLayer, cancelDrag, fixPosition) {
			if (ppCount === 10) { return alert('これ以上はウインドウは増やせません！');}
			var pp = {},
				tt = title || 'no title',
				bd = body || '',
				sc = FL.ut.getScrollPosition(),
				cs = FL.ut.getContentSize(),
				left = (cs.width / 2) | 0,
				IE6FixFlag = false,
				width, height, ml, mt, html;

			// width ,height format
			if (!w) {
				width = '600px'; ml = 300;
			} else if ((w + '').indexOf('%') > 0) {
				width = w; ml = cs.width * parseInt(w, 10) / 100 / 2;
			} else {
				width = w + 'px'; ml = w / 2;
			}
			if (!h) {
				height = '400px'; mt = 200;
			} else if ((h + '').indexOf('%') > 0) {
				height = h; mt = cs.height * parseInt(h, 10) / 100 / 2;
				IE6FixFlag = true;
			} else {
				height = h + 'px'; mt = h / 2;
			}
			// hide to stable
			if (ppCount > 0) {
				DOM('div.sz-popup-content').foreach(function() {
					DOM(this).addStyle('zIndex', ++stableIndex);
					if (FL.ua.IE6) {
						DOM(this).detect('select').foreach(function() {
							this.style.visibility = 'hidden';
						});
					}
				});
				stableIndex = 990;
			};
			pp.box = DOM.create('div', {'class' : 'sz-popup-content'}).appendTo(window['IEFIX'] ? IEFIX.body : document.body);

			if (FL.ua.IE6) {
				pp.box.addStyle('position', 'fixed');
				sc = {x : 0, y : 0};
				/*
				html = ['<div class="sz-pp-tl" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'', FL.config.baseUrl(), 'images/ppbox/rad_top_left.png\',sizingMethod=\'scale\');"></div>',
				        '<div class="sz-pp-tc">', tt, '</div>',
				        '<div class="sz-pp-tr" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'', FL.config.baseUrl(), 'images/ppbox/rad_top_right.png\',sizingMethod=\'scale\')"></div>',
				           '<div class="sz-pp-contents" id="sz_pp_contents">', bd, '</div>',
				        '<div class="sz-pp-bl" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'', FL.config.baseUrl(), 'images/ppbox/rad_bottom_left.png\',sizingMethod=\'image\')"></div>',
				        '<div class="sz-pp-bc"></div>',
				        '<div class="sz-pp-br" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'', FL.config.baseUrl(), 'images/ppbox/rad_bottom_right.png\',sizingMethod=\'image\')"></div>',
				        '<a href="javascript:void(0)" class="pp_close"></a>'
				         ];
				*/
			} else {
				/*
				html = ['<div class="sz-pp-tl"></div><div class="sz-pp-tc">', tt, '</div><div class="sz-pp-tr"></div>',
				           '<div class="sz-pp-contents" id="sz_pp_contents">', bd, '</div><div class="sz-pp-bl"></div><div class="sz-pp-bc"></div><div class="sz-pp-br"></div><a href="javascript:void(0)" class="pp_close"></a>'
				            ];
				*/
			}
			html = [
			        '<h3 class="sz-pp-head">', tt, '</h3>',
			        '<div class="sz-pp-contents">', bd, '</div>',
			        '<a href="javascript:void(0)" class="pp_close">',
			          '<span data-icon="x" class="icon small white" style="display:inline-block">',
			            '<span aria-hidden="true">x</span>',
			          '</span>',
			        '</a>'
			       ];
			pp.box.html(html.join(''));
			pp.hide = hidePP;
			pp.keepLayer = withLayer || false;
			pp.setContent = function(e) {
				pp.body.append(e);
				if (FL.ua.IE6) {
					pp.box.addStyle('zoom', '1');
					if (IE6FixFlag === false) {
						pp.body.addStyle('height', height);
					}
				}
			};
			pp.setOnClose = function(fn, conf) {
				pp.doConfirm = conf || false;
				pp.closeCallback = fn || null;
			};
			pp.box.addStyle({
				width : width,
				height : height,
				marginLeft : -ml + 'px',
				display : 'block',
				top : sc.y + ppCount * 10 + 40 + 'px',
				left : left + 'px',
				zIndex : latestIndex
			});
			ppCount++;
			ppStack.push(pp);
			if (ppCount === 1) {
				layer = new Module.layer(true);
			}
			pp.title = pp.box.getOne('h3.sz-pp-head');
			pp.body = pp.box.getOne('div.sz-pp-contents');
			pp.box.getOne('a.pp_close').once('click', hidePP);
			pp.body.addStyle('height', pp.box.prop('offsetHeight') - pp.title.prop('offsetHeight') - 30 + 'px');

			if (!cancelDrag && !FL.ua.IE6) {
				new Module.draggable(pp.box, {handle : pp.title});
			}
			return pp;
		};
	}

	if (!functionExists('hideDOMWindow')) {
		Helper.hideDOMWindow = function(pp) {
			hidePP();
		};
	}
	
	if ( ! functionExists('createNotify') ) {
		Helper.createNotify = function(msg) {
			var box = DOM.create('div').addClass('sz_notification'),
				exists = DOM('div.sz_notification');
			
			if ( exists.length > 0 ) {
				exists.get(0).remove();
			}
			box.addStyle({
				position : 'fixed',
				opacity  : 0
			});
			box.html(msg);
			box.appendTo();
			Animation.appear(box, {to : 0.9});
			setTimeout(function () {
				Animation.fade(box, { callback : function() {
					box.remove();
					box = null;
				}});
			}, 3600);
		}
	}
	
	if ( ! functionExists('createLoginWindow') ) {
		Helper.createLoginWindow = function() {
			if ( ! isLoggedIn ) {
				return;
			}
			var box = DOM.create('div').attr('id', 'sz_loginwindow'),
				html,
				layer = new Module.layer(),
				is = layer.isHidden();
			
			box.appendTo();
			html = [
			        '<h5>ログイン状態が解除されました。もう一度ログインしてください。</h5>',
			        '<form class="center">',
			        '<p>ID:&nbsp;&nbsp;<input type="text" name="username" class="middle-text" value="" /></p>',
			        '<p style="margin-left:-11px">Pass:&nbsp;&nbsp;<input type="password" class="middle-text" name="password" value="" /></p>',
			        '<button class="blue">',
			          '<span data-icon="Q" class="icon white medium" style="display:inline-block">',
			            '<span aria-hidden="true">Q</span>',
			          '</span>',
			          'ログイン',
			        '</button>',
			        '</form>'
			];
			box.html(html.join(''));
			layer.show();
			// re:login event
			box.getOne('button').event('click', function (evt) {
				evt.preventDefault();
				box.addClass('loading');
				FL.ajax.post('login/index/' + FL.config.item('sz_token'), {
					param : box.getOne('form').serialize(),
					success: function(resp) {
						var json;
						
						try {
							json = FL.json.parse(resp.responseText);
							if ( json.status === 'success' ) {
								box.remove();
								FL.config.setItem('sz_token', json.token);
								is && layer.hide();
							} else {
								alert('ログインに失敗しました。');
								box.removeClass('loading');
							}
						} catch ( e ) {
							alert('システムエラーが発生しました。');
						}
						
					},
					error : function(resp) {
						alert(resp.responseText);
						box.removeClass('loading');
						
					}
				});
			});
		}
	}

})();