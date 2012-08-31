// tab control function
(function() {
	
	var FL = getInstance();
	
	var ipts = DOM('input.txt-long'), count = ipts.length;
	
	var frame = DOM.id('tab_contents_frame');
	
	// add tab event handler
	var addTab = function(ev) {
		var p = DOM.create('p').append('<label><input type="text" name="tab_contents_name[]" class="txt-long" /></label><a href="javascript:void(0)" class="tab_delete"><img src="' + FL.config.baseUrl() + 'images/delete.png" />&nbsp;削除</a>')
		p.appendTo(frame);
	};
	
	// delete tab event handler
	var judgeDelete = function(ev) {
		var e = DOM(ev.target);
		if (e.tag !=='img' && !e.hasClass('tab_delete')) { return; }
		if (frame.detect('input').length === 1) {
			return alert('これ以上は削除できません。');
		}
		e.parent().remove();
	};
	
	
	// set events
	DOM.id('add_tab').event('click', addTab);
	
	// frame live event
	frame.event('click', judgeDelete);
	
	// validation
	DOM('a.button_right').get(0).event('click', function(ev) {
		var areas = DOM('div.cmsi_add_block span'), vals = {}, ivals = [], flag = true;
		
		areas.foreach(function() {
			vals[this.firstChild.nodeValue] = 1;
		});
		
		DOM.byName('tab_contents_name[]').foreach(function() {
			
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
//			if (this.value in vals) {
//				flag = false;
//				ev.preventDefault();
//				alert(this.value + ' はこのページ内で既に使われています。');
//				return false;
//			}
			ivals.push(this.value);
		});
	});
})();