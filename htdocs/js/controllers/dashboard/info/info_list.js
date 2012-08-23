ClassExtend('Controller', function InfoListController() {
	
	this.__construct = function() {
		this.load.ajax();
	};
	
	this.index = function() {
		DOM('a.delete').event('click', function(evt) {
			return confirm('情報を削除します。よろしいですか？');
		});
		
		DOM('a.view').event('click', this.__toggleActive, this);
	};
	
	this.__toggleActive = function(evt) {
		evt.preventDefault();
		var that = this;
		
		this.ajax.get(evt.target.href + '/' + this.config.item('sz_token'), {
			success : function(resp) {
				var node = DOM(evt.target);
				
				if ( resp.responseText === 'success' ) {
					node.parent(2).toggleClass('noactive');
					if ( evt.target.rel > 0 ) {
						evt.target.rel = 0;
						evt.target.innerHTML = '公開にする';
						node.parent().prev().html('<span style="color:#c00">非公開</span>');
					} else {
						evt.target.rel = 1;
						evt.target.innerHTML = '非公開にする';
						node.parent().prev().html('公開');
						
					}
				}
			},
			error : function() {
				alert('通信に失敗しました。');
			}
		});
	};
});
