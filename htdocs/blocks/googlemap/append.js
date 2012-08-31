// google map api key check empty
(function() {
	
	var FL = getInstance(), DOM = FL.DOM;
	
	// validation
	DOM('a.button_right').get(0).event('click', function(ev) {
		
		var e = DOM.id('api_key');
		
		if (e.getValue() === '' && DOM.id('version').getValue() == 2) {
			alert('APIキーは必須入力です。');
			ev.preventDefault();
		}
	});
	
	// change event
	DOM.id('version').event('change', function() {
		var t = DOM(this).parent()
						.next()
						.next()
						.detect('input');
		
		if (this.value == 3) {
			t.foreach(function() {
				this.disabled = true;
			});
		} else {
			t.foreach(function() {
				this.disabled = false;
			})
		}
	});
})();