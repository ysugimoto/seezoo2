(function() {

	var FL = getInstance(),
		blocks, urls, cnt = 0,
		globalTimer = 3000;
	
	FL.load.ajax();

	function init() {
		blocks = DOM('div.sz_twitter_block').foreach(function() {
			var a = DOM(this).getOne('a.sz_twitter_url');

			new getTwit(DOM(this), a.readAttr('href'), a.readAttr('rel'));
			a.attr('href', 'http://twitter.com/#!/' + a.readAttr('href').slice(a.readAttr('href').lastIndexOf('/') + 1));
		});
	};


	/* ================== get twit class =============== */

	function getTwit(block, url, limit) {
		this.block = block;
		this.url = url + '.json';
		this.limit = limit || 10;
		this.first = true;
		this.stack = {};
		this.contentArea = block.getOne('div.sz_twitter_block_contents');
		this.__init();
	};

	getTwit.prototype = {
		__init : function() {
			FL.ajax.jsonp(this.url, this.callback, this);
		},
		callback : function(obj) {
			if (!obj || typeof obj !== 'object') {
				DOM('div.sz_twitter_block').get(0).html('データが取得できませんでした。');
				return;
			}

			if (this.first === true) {
				this.__buildFirst(obj);
				this.first = false;
			} else {
				this.__buildSecondAfter(obj);
			}
		},
		// first, build with list HTML
		__buildFirst : function(obj) {
			var len = (obj.length > this.limit) ? this.limit : obj.length, i = 0, twit, that = this, d,
				html = ['<ul class="sz_twitter_block_list">'];

			for (; i < len; i++) {
				twit = obj[i];
				d = this.dateFormat(twit.created_at);
				html.push('<li><div class="twit">' + this.linkFormat(twit.text) + '</div><p>' + d + '</p></li>');
			}
			html.push('</ul>');
			this.contentArea.html(html.join(''))
				.addStyle('overflow', 'hidden')
				.addStyle('height', '0px');
			new Animation(
							this.contentArea,
							{ height : 200 },
							{
								speed : 20,
								easing : 50,
								callback : function() {
									that.contentArea.first().get().scrollTop = 0;
									that.contentArea.addStyle('overflow', '')
														.addStyle('background', 'none');
								}
							}
						);
//			this.contentArea.animate('blindDown', {height : 200, speed : 10, easing : 50, callback : function() {
//				that.contentArea.first().get().scrollTop = 0;
//			}});
			this.block.getOne('div.sz_twitter_block_user_image').html('<img src="' + twit.user.profile_image_url + '" alt="" />');
		},
		// second or after, build LI element Only
		__buildSecondAfter : function(obj) {
			var twit = obj[0], ul = this.contentArea.getOne('ul.sz_twitter_block_list'), li;

			li = DOM.create('li').html('<div class="twit">' + this.linkFormat(twit.text) + '</div><p>' + twit.screen_name + '</p>')
						.addStyle('height', '0px');
			li.prependTo(ul).animate('expand', {mode : 'h', speed : 20});
		},
		linkFormat : function(str) {
			return str.replace(/\b(https?:\/\/[\w\.\/]+)([\/|\?]?[\-_.!~\*a-zA-Z0-9\/\?:;@&=+$,%#]+)?\b/g, '<a href="$1$2" target="blank">$1$2</a>')
							.replace(/@([\w]+)/g, '@<a href="http://twitter.com/#!/$1" target="_blank">$1</a>');
		},
		dateFormat : function(ds) {
				d = new Date(ds),
				y = d.getFullYear()+'',
				m = (d.getMonth()+1)+'',
				dt = d.getDate()+'',
				h = d.getHours()+'',
				i = d.getMinutes()+'',
				s = d.getSeconds()+'';

			return [
			        y, '-',
			        (m.length == 1) ? '0' + m : m, '-',
			        (dt.length == 1) ? '0' + dt : dt, ' ',
			        (h.length == 1) ? '0' + h : h, ':',
			        (i.length == 1) ? '0' + i : i, ':',
			        (s.length == 1) ? '0' + s : s
			        ].join('');
		}
	};
	
	// IE Date format override
	// IE unrecognized "Wed May 10 17:14:02 2011" like twitter API returns date format...
	// So, we try to parse and format basic datetime.
	( FL.ua.IE ) && (function() {
		// format mapper
		var monthMap = {
							'Jan' : 0, 'Feb' : 1, 'Mar' : 2, 'Apr' : 3, 'May' : 4, 'Jun' : 5, 'Jul' : 6, 'Aug' : 7,
							'Sep' : 8, 'Oct' : 9, 'Nov' : 10, 'Dec' : 11
						};
		
		getTwit.prototype.dateFormat = function(ds) {
			var dateS = ds.split(' '),
				his = dateS[3].split(':'),
				d = new Date(dateS[5], monthMap[dateS[1]], dateS[2], his[2], his[1], his[0]),
				y = d.getFullYear()+'',
				m = (d.getMonth()+1)+'',
				dt = d.getDate()+1 + '',
				h = d.getHours()+'',
				i = d.getMinutes()+'',
				s = d.getSeconds()+'';
			
			return [
			        y, '-',
			        (m.length == 1) ? '0' + m : m, '-',
			        (dt.length == 1) ? '0' + dt : dt, ' ',
			        (h.length == 1) ? '0' + h : h, ':',
			        (i.length == 1) ? '0' + i : i, ':',
			        (s.length == 1) ? '0' + s : s
			        ].join('');
		};
			
		
		
	})();

	FL.event.set(document, 'DOMReady', init);
})();
