(function(doc) {
	
	
	var FL = getInstance(),
		DOM = FL.DOM,
		that = this;
	
	FL.load.module('layer');
	FL.load.ajax();
	
	// main function
	function init() {
		var method = FL.uri.segment(2);
		
		that[method] ? that[method] : that.show(method);
	}
	
	// private function ===============================
	function setUpImageUpdate(ev) {
		ev.preventDefault();
		
		var layer = new Module.layer(true),
			box = DOM.create('div')
						.addClass('pf_image_box')
						.appendTo()
						.addStyle('position', 'fixed'),
			html;
		
		html = [
		          '<h3>プロフィール画像の変更</h3>',
		          '<p>画像をアップロードしてください。<br />',
		          '使用できる形式はGIF,JPG,PNGフォーマット、サイズは100px&nbsp;x&nbsp;100px推奨、100KBまでです。</p>',
		          '<iframe src="', ev.currentTarget.href, '" scrolling="no" frameborder="0" style="width:100%;height:80px;"></iframe>',
		          	'<a href="javascript:void(0)" id="up_close">&nbsp;</a>'
		        ];
		
		box.html(html.join(''));
		DOM.id('up_close')
			.event('click', function(ev) {
				box.remove();
				layer.hide();
			});
	}
	
	
	this.show = function() {
		if ( doc.getElementById('change_image') ) {
			DOM.id('change_image')
				.event('click', setUpImageUpdate);
		}
	};
	
	
	doc.body ? init() : FL.event.set(document, 'DOMReady', init);
	
})(document);