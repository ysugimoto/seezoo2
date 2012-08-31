(function() {
	var FL = getInstance(),
		DOM = FL.DOM;
	
	DOM('a.button_right').get(0)
		.event('click', function(ev) {
			var a = document.getElementsByName('description_area_name')[0],
				b = document.getElementsByName('head_text')[0],
				err = [];
			
			if ( a.value === '' ) {
				err[err.length] = '概要エリア名は必須入力です。';
			}
			if ( b.value === '' ) {
				err[err.length] = '見出しテキストは必須入力です。';
			}
			
			if ( err.length > 0 ) {
				ev.preventDefault();
				return !!alert(err.join('\n'));
			}
			
		});
})();